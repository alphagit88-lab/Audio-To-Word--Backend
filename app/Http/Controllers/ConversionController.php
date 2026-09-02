<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConversionController extends Controller
{
    /**
     * POST /api/convert/prepare
     *
     * Accepts an audio file + optional startTime / endTime.
     * Decides if server-side processing is needed:
     *   - File is not MP3
     *   - File has start/end time clipping
     *   - File duration exceeds PARTICLE_SIZE_MINUTES
     *
     * If no processing needed: returns { needsProcessing: false }
     * If processing needed:
     *   - Converts to MP3 (with clipping if required)
     *   - Splits into chunks
     *   - Returns base64 of chunk 0 + sessionFolder + totalChunks
     */
    public function prepare(Request $request)
    {
        $request->validate([
            'audio'     => 'required|file',
            'startTime' => 'nullable|string',
            'endTime'   => 'nullable|string',
        ]);

        try {
            $file      = $request->file('audio');
            $extension = strtolower($file->getClientOriginalExtension() ?: 'mp3');

            $chunkSizeMinutes = (int) env('PARTICLE_SIZE_MINUTES', 15);
            $chunkSizeSec     = $chunkSizeMinutes * 60;

            $startTime = $request->input('startTime');
            $endTime   = $request->input('endTime');
            $startSec  = $this->timeToSeconds($startTime);

            // Store uploaded file temporarily
            $inputPath         = $file->storeAs('temp_audio', uniqid('input_') . '.' . $extension, 'public');
            $absoluteInputPath = storage_path('app/public/' . $inputPath);

            // Get duration via ffprobe
            $duration = $this->getDuration($absoluteInputPath);

            $endSec = $request->filled('endTime') ? $this->timeToSeconds($endTime) : $duration;
            if ($duration > 0 && $endSec > $duration) {
                $endSec = $duration;
            }
            $effectiveDuration = $endSec - $startSec;

            $needsClipping      = $request->filled('startTime') || $request->filled('endTime');
            $needsSplit         = $effectiveDuration > $chunkSizeSec;
            $needsConversion    = $extension !== 'mp3';
            $needsProcessing    = $needsClipping || $needsSplit || $needsConversion;

            if (!$needsProcessing) {
                // Clean up uploaded temp
                Storage::disk('public')->delete($inputPath);
                return response()->json(['needsProcessing' => false]);
            }

            // ── Server-side processing required ──────────────────────────────

            // Step 1: Convert to MP3 (with clipping if needed)
            $sessionId      = uniqid('session_');
            $outputDir      = 'chunks/' . $sessionId;
            Storage::disk('public')->makeDirectory($outputDir);
            $absoluteOutDir = storage_path('app/public/' . $outputDir);

            $mp3Path = $absoluteOutDir . '/source.mp3';

            if ($needsConversion || $needsClipping) {
                $ffmpegPath = env('FFMPEG_PATH', 'C:\ffmpeg\bin\ffmpeg.exe');
                if (!file_exists($ffmpegPath)) $ffmpegPath = 'ffmpeg'; // fallback to PATH

                $ffmpegCmd = escapeshellarg($ffmpegPath) . ' -y';
                if ($request->filled('startTime')) $ffmpegCmd .= " -ss {$startSec}";
                $ffmpegCmd .= ' -i ' . escapeshellarg($absoluteInputPath);
                if ($request->filled('endTime') && $endSec > 0) $ffmpegCmd .= " -to {$endSec}";
                $ffmpegCmd .= ' -ar 44100 -ab 128k -f mp3 ' . escapeshellarg($mp3Path) . ' 2>&1';
                
                $output = shell_exec($ffmpegCmd);
                Log::info("FFMpeg cmd executed: " . $ffmpegCmd);
                Log::info("FFMpeg output: " . $output);
            } else {
                // Already MP3, no clipping — just copy
                copy($absoluteInputPath, $mp3Path);
            }

            // Clean up original upload
            Storage::disk('public')->delete($inputPath);

            if (!file_exists($mp3Path)) {
                return response()->json(['success' => false, 'error' => 'Audio conversion failed on server.'], 500);
            }

            // Step 2: Split if needed
            $chunkPaths = [];
            $mp3Duration = $this->getDuration($mp3Path);

            if ($mp3Duration > $chunkSizeSec) {
                $numChunks = (int) ceil($mp3Duration / $chunkSizeSec);
                for ($i = 0; $i < $numChunks; $i++) {
                    $chunkStart    = $i * $chunkSizeSec;
                    $chunkDuration = min($chunkSizeSec, $mp3Duration - $chunkStart);
                    if ($chunkDuration <= 0) break;

                    $chunkPath = $absoluteOutDir . "/chunk_{$i}.mp3";
                    $cmd       = "ffmpeg -y -i " . escapeshellarg($mp3Path) .
                                 " -ss {$chunkStart} -t {$chunkDuration} -c copy " .
                                 escapeshellarg($chunkPath) . " 2>&1";
                    shell_exec($cmd);

                    if (file_exists($chunkPath)) {
                        $chunkPaths[] = $chunkPath;
                    }
                }
                unlink($mp3Path); // remove source mp3, keep only chunks
            } else {
                // No split needed — source.mp3 is the single chunk
                $singleChunk = $absoluteOutDir . '/chunk_0.mp3';
                rename($mp3Path, $singleChunk);
                $chunkPaths[] = $singleChunk;
            }

            $totalChunks = count($chunkPaths);
            if ($totalChunks === 0) {
                return response()->json(['success' => false, 'error' => 'No audio chunks were generated.'], 500);
            }

            // Return chunk 0 as base64 immediately
            $chunk0Base64 = base64_encode(file_get_contents($chunkPaths[0]));

            return response()->json([
                'needsProcessing' => true,
                'sessionFolder'   => $sessionId,
                'totalChunks'     => $totalChunks,
                'chunkIndex'      => 0,
                'base64'          => $chunk0Base64,
                'mimeType'        => 'audio/mp3',
            ]);

        } catch (\Exception $e) {
            Log::error('ConversionController@prepare: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/convert/chunk?session={sessionFolder}&index={chunkIndex}
     *
     * Returns the base64-encoded audio for a specific chunk in a session.
     */
    public function chunk(Request $request)
    {
        $request->validate([
            'session' => 'required|string|alpha_dash',
            'index'   => 'required|integer|min:0',
        ]);

        $sessionId  = $request->input('session');
        $chunkIndex = (int) $request->input('index');
        $chunkPath  = storage_path("app/public/chunks/{$sessionId}/chunk_{$chunkIndex}.mp3");

        if (!file_exists($chunkPath)) {
            return response()->json(['success' => false, 'error' => 'Chunk not found.'], 404);
        }

        return response()->json([
            'success'    => true,
            'chunkIndex' => $chunkIndex,
            'base64'     => base64_encode(file_get_contents($chunkPath)),
            'mimeType'   => 'audio/mp3',
        ]);
    }

    /**
     * DELETE /api/convert/session?session={sessionFolder}
     *
     * Cleans up server-side files after transcription is done.
     */
    public function cleanup(Request $request)
    {
        $request->validate(['session' => 'required|string|alpha_dash']);
        $sessionId = $request->input('session');
        $dir = "chunks/{$sessionId}";
        if (Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->deleteDirectory($dir);
        }
        return response()->json(['success' => true]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function getDuration(string $filePath): float
    {
        $ffprobePath = env('FFPROBE_PATH', 'C:\ffmpeg\bin\ffprobe.exe');
        if (!file_exists($ffprobePath)) $ffprobePath = 'ffprobe'; // fallback to PATH

        $cmd    = escapeshellarg($ffprobePath) . " -i " . escapeshellarg($filePath) . " -show_entries format=duration -v quiet -of csv=\"p=0\"";
        $output = shell_exec($cmd);
        return (float) trim($output);
    }

    private function timeToSeconds(?string $timeStr): float
    {
        if (!$timeStr) return 0;
        $parts = explode(':', $timeStr);
        if (count($parts) === 3) return ($parts[0] * 3600) + ($parts[1] * 60) + (float) $parts[2];
        if (count($parts) === 2) return ($parts[0] * 60) + (float) $parts[1];
        return (float) $timeStr;
    }
}
