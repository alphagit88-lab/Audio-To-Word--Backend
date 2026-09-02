<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // GET /api/projects
    public function index(Request $request)
    {
        $projects = Project::where('user_id', $request->user()->id)
            ->orderBy('saved_at', 'desc')
            ->get()
            ->map(fn($p) => $this->toClientShape($p));

        return response()->json($projects);
    }

    // POST /api/projects
    public function store(Request $request)
    {
        $data = $request->validate([
            'id'                     => 'nullable|string|max:64',
            'name'                   => 'required|string|max:255',
            'savedAt'                => 'required|string',
            'primaryAudioFilePath'   => 'nullable|string',
            'totalChunks'            => 'required|integer',
            'parts'                  => 'required|array',
        ]);

        $projectId = $data['id'] ?? (string) Str::ulid();

        $project = Project::updateOrCreate(
            ['id' => $projectId, 'user_id' => $request->user()->id],
            [
                'name'                   => $data['name'],
                'saved_at'               => $data['savedAt'],
                'primary_audio_file_path'=> $data['primaryAudioFilePath'] ?? null,
                'total_chunks'           => $data['totalChunks'],
                'parts'                  => $data['parts'],
            ]
        );

        return response()->json($this->toClientShape($project), 201);
    }

    // GET /api/projects/{id}
    public function show(Request $request, string $id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json($this->toClientShape($project));
    }

    // PUT /api/projects/{id}
    public function update(Request $request, string $id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $data = $request->validate([
            'name'                   => 'sometimes|string|max:255',
            'savedAt'                => 'sometimes|string',
            'primaryAudioFilePath'   => 'nullable|string',
            'totalChunks'            => 'sometimes|integer',
            'parts'                  => 'sometimes|array',
        ]);

        $project->update([
            'name'                   => $data['name']                   ?? $project->name,
            'saved_at'               => $data['savedAt']                ?? $project->saved_at,
            'primary_audio_file_path'=> $data['primaryAudioFilePath']   ?? $project->primary_audio_file_path,
            'total_chunks'           => $data['totalChunks']            ?? $project->total_chunks,
            'parts'                  => $data['parts']                  ?? $project->parts,
        ]);

        return response()->json($this->toClientShape($project));
    }

    // DELETE /api/projects/{id}
    public function destroy(Request $request, string $id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $project->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    private function toClientShape(Project $p): array
    {
        return [
            'id'                    => $p->id,
            'name'                  => $p->name,
            'savedAt'               => $p->saved_at,
            'primaryAudioFilePath'  => $p->primary_audio_file_path,
            'totalChunks'           => $p->total_chunks,
            'parts'                 => $p->parts ?? [],
        ];
    }
}
