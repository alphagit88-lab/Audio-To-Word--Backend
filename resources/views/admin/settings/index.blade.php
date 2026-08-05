@extends('layouts.admin')

@section('title', 'Settings')
@section('page_title', 'Settings')

@section('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-main);
    }
    .page-subtitle {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }
    .settings-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .settings-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .settings-card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a5b4fc;
        flex-shrink: 0;
    }
    .settings-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
    }
    .settings-card-desc {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }
    .settings-card-body {
        padding: 1.5rem;
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .form-hint {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.4rem;
    }
    .form-textarea {
        width: 100%;
        min-height: 120px;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-main);
        padding: 0.85rem 1rem;
        font-size: 0.88rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace, 'Inter', sans-serif;
        resize: vertical;
        outline: none;
        transition: border-color 0.2s;
        line-height: 1.6;
    }
    .form-textarea:focus {
        border-color: var(--primary-accent);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .key-count {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #34d399;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        margin-bottom: 1rem;
    }
    .btn-save {
        background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
        color: #fff;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        transition: transform 0.1s, opacity 0.2s;
    }
    .btn-save:hover { opacity: 0.95; }
    .btn-save:active { transform: scale(0.98); }
    .btn-add {
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
        border: 1px dashed rgba(99, 102, 241, 0.4);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
        margin-top: 0.5rem;
    }
    .btn-add:hover {
        background: rgba(99, 102, 241, 0.25);
    }
    .btn-remove {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        padding: 0.4rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-remove:hover {
        background: rgba(239, 68, 68, 0.25);
    }
    .prompt-row {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        align-items: flex-start;
    }
    .prompt-row .form-textarea {
        min-height: 80px;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Settings</div>
        <div class="page-subtitle">Manage application configuration</div>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    {{-- Gemini API Keys --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-header-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
            </div>
            <div>
                <div class="settings-card-title">Gemini AI API Keys</div>
                <div class="settings-card-desc">Manage the pool of Gemini API keys used for audio transcription</div>
            </div>
        </div>
        <div class="settings-card-body">

            @php
                $keysArray = array_filter(array_map('trim', explode(',', $geminiApiKeys ?? '')));
                $keyCount = count($keysArray);
            @endphp

            <div class="key-count">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                {{ $keyCount }} key{{ $keyCount !== 1 ? 's' : '' }} configured
            </div>

            <div class="form-group">
                <label class="form-label" for="gemini_api_keys">API Keys</label>
                <textarea
                    class="form-textarea"
                    id="gemini_api_keys"
                    name="gemini_api_keys"
                    placeholder="Enter Gemini API keys, separated by commas..."
                >{{ $geminiApiKeys }}</textarea>
                <div class="form-hint">
                    Separate multiple keys with commas. The app will rotate through these keys when one hits a rate limit.
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Prompts --}}
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-header-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <div>
                <div class="settings-card-title">Quick Prompts</div>
                <div class="settings-card-desc">Configure quick instructional prompts that users can select in the desktop app.</div>
            </div>
        </div>
        <div class="settings-card-body">
            <div class="form-group" id="quick-prompts-container">
                <label class="form-label">Prompts</label>
                @if(isset($quickPrompts) && count($quickPrompts) > 0)
                    @foreach($quickPrompts as $index => $prompt)
                        <div class="prompt-row">
                            <textarea class="form-textarea" name="quick_prompts[]" placeholder="Enter instructional prompt...">{{ $prompt }}</textarea>
                            <button type="button" class="btn-remove" onclick="this.parentElement.remove()" title="Remove Prompt">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    @endforeach
                @endif
                <div class="prompt-row" id="prompt-template" style="display: none;">
                    <textarea class="form-textarea" name="quick_prompts[]" placeholder="Enter instructional prompt..." disabled></textarea>
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()" title="Remove Prompt">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
            </div>
            <button type="button" class="btn-add" id="add-prompt-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Add Prompt
            </button>
        </div>
    </div>

    <div style="margin-bottom: 2rem;">
        <button type="submit" class="btn-save">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save Settings
        </button>
    </div>
</form>

<script>
    document.getElementById('add-prompt-btn').addEventListener('click', function() {
        var template = document.getElementById('prompt-template');
        var newRow = template.cloneNode(true);
        newRow.removeAttribute('id');
        newRow.style.display = 'flex';
        var textarea = newRow.querySelector('textarea');
        textarea.removeAttribute('disabled');
        textarea.value = '';
        template.parentNode.insertBefore(newRow, template);
    });
</script>

@endsection
