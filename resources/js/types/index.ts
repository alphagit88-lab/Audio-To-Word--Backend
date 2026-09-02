export interface AudioFileInfo {
  filePath: string;
  fileName: string;
  fileSize: number;
  extension: string;
  startTime?: string;
  endTime?: string;
  needsClipping?: boolean;
  fileObject?: File;
}

export interface TranscriptionPart {
  chunkIndex: number;
  text: string;
  audioChunkPaths: string[];
  docxPath?: string;
  primaryAudioFilePath: string;
  total: number;
}

export interface ConversionResumeState {
  startFromChunkIndex: number;
  existingTranscription: string;
  allChunks: string[][];
  files: AudioFileInfo[];
}

export interface ConversionPromptOptions {
  transcriptionModel?: string;
  additionalInstructions?: string;
  exampleDocx?: {
    fileName: string;
    mimeType: string;
    dataBase64: string;
  };
}

export interface SavedProject {
  id: string;           // timestamp-based unique id
  name: string;
  savedAt: string;      // ISO date string
  primaryAudioFilePath: string;
  parts: TranscriptionPart[];
  totalChunks: number;
}

export type ConversionStatus = 'idle' | 'reading_file' | 'transcribing' | 'generating_docx' | 'completed' | 'error';

export interface ConversionProgress {
  status: ConversionStatus;
  message: string;
  percentage: number;
}

export interface ConversionResult {
  success: boolean;
  partial?: boolean;
  docxPath?: string;
  error?: string;
  transcription?: string;
  completedChunks?: number;
  totalChunks?: number;
  resumeState?: ConversionResumeState;
}

export interface ElectronAPI {
  selectAudioFile: (allowMultiple?: boolean) => Promise<AudioFileInfo[] | null>;
  convertAudioToDocx: (
    files: AudioFileInfo[],
    apiKeys: string[],
    authToken?: string,
    resumeState?: ConversionResumeState,
    promptOptions?: ConversionPromptOptions
  ) => Promise<ConversionResult>;
  openFolder: (filePath: string) => Promise<void>;
  onProgress: (callback: (progress: ConversionProgress) => void) => () => void;
  startUpdateDownload: () => void;
  quitAndInstallUpdate: () => void; // kept for compatibility
  runDownloadedUpdate: () => Promise<boolean>;
  cancelConversion: () => Promise<void>;
  onUpdateAvailable: (callback: (version: string) => void) => () => void;
  onUpdateDownloaded: (callback: (filePath: string) => void) => () => void;
  onUpdateProgress: (callback: (percent: number) => void) => () => void;
  // Review panel APIs
  onChunkTranscribed: (callback: (part: TranscriptionPart) => void) => () => void;
  resubmitChunk: (
    chunkIndex: number,
    audioPaths: string[],
    userPrompt: string,
    apiKeys: string[],
    transcriptionModel: string,
    primaryAudioFilePath: string,
    authToken?: string,
    existingText?: string,
    isPartial?: boolean,
    fullChunkText?: string
  ) => Promise<{ text: string; docxPath: string }>;
  transcribeDictation: (
    chunkIndex: number,
    base64Audio: string,
    apiKeys: string[],
    transcriptionModel: string,
    primaryAudioFilePath: string,
    lineText: string,
    lineIdx: number,
    allLines: string[],
    selectedText?: string
  ) => Promise<{ text: string; docxPath: string }>;
  finalizeTranscription: (
    parts: Array<{ chunkIndex: number; text: string }>,
    primaryAudioFilePath: string
  ) => Promise<string>;
  // Project APIs
  saveProject: (name: string, parts: TranscriptionPart[], totalChunks: number, existingId?: string) => Promise<SavedProject>;
  listProjects: () => Promise<SavedProject[]>;
  loadProject: (projectId: string) => Promise<SavedProject>;
  deleteProject: (projectId: string) => Promise<void>;
}

declare global {
  interface Window {
    electronAPI: ElectronAPI;
  }
}
