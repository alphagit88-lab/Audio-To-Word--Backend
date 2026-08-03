@extends('layouts.admin')

@section('title', 'Error Logs')
@section('page_title', 'Error Logs')

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
    
    /* Table Styling */
    .table-container {
        width: 100%;
        overflow-x: auto;
        border-radius: 12px;
    }
    
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        text-align: left;
    }
    
    .data-table th {
        background: rgba(15, 23, 42, 0.6);
        padding: 1rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    
    .data-table td {
        padding: 1rem 1.25rem;
        font-size: 0.88rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        vertical-align: middle;
    }
    
    .data-table tbody tr {
        transition: background-color 0.2s;
    }
    
    .data-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }
    
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .error-msg {
        max-width: 400px;
        word-wrap: break-word;
        white-space: pre-wrap;
        color: var(--danger);
        font-family: monospace;
        font-size: 0.8rem;
        background: rgba(239, 68, 68, 0.1);
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state p {
        font-size: 0.95rem;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .pagination-info {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">AI Error Logs</div>
</div>

<div class="glass-card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th width="20%">User</th>
                <th width="50%">Error Message</th>
                <th width="30%">Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">{{ $log->user->name ?? 'Unknown User' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">{{ $log->user->email ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="error-msg">{{ $log->error_message }}</div>
                    </td>
                    <td>
                        <div style="color: var(--text-main);">{{ $log->created_at->format('M d, Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $log->created_at->format('h:i A') }}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            <p>No error logs recorded yet.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries
        </div>
        <div>
            {{ $logs->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endif

@endsection
