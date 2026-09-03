@extends('layouts.admin')

@section('title', 'Usage Logs')
@section('page_title', 'Usage Logs')

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
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
    }
    .filter-select {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        padding: 0.5rem 0.85rem;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
        cursor: pointer;
        min-width: 200px;
        transition: border-color 0.2s;
    }
    .filter-select:focus {
        border-color: var(--primary-accent);
    }
    .btn-filter {
        background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .btn-filter:hover { opacity: 0.9; }
    .btn-clear {
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-clear:hover { background: rgba(255,255,255,0.08); color: var(--text-main); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem;
    }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-main);
        font-family: 'Outfit', sans-serif;
    }
    .stat-sub {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }

    .table-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
    }
    .table-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .table-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
    }
    .table-count {
        font-size: 0.78rem;
        color: var(--text-muted);
        background: rgba(99,102,241,0.1);
        border: 1px solid rgba(99,102,241,0.25);
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead th {
        padding: 0.85rem 1.5rem;
        text-align: left;
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 1px solid var(--border-color);
    }
    tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.04);
        transition: background 0.15s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(255,255,255,0.025); }
    tbody td {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: var(--text-main);
        vertical-align: middle;
    }
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .user-avatar-sm {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(99,102,241,0.15);
        border: 1px solid rgba(99,102,241,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #a5b4fc;
        flex-shrink: 0;
    }
    .user-name-sm {
        font-weight: 600;
        font-size: 0.85rem;
    }
    .user-email-sm {
        font-size: 0.72rem;
        color: var(--text-muted);
    }
    .duration-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(99,102,241,0.1);
        border: 1px solid rgba(99,102,241,0.2);
        color: #a5b4fc;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .file-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(16,185,129,0.1);
        border: 1px solid rgba(16,185,129,0.2);
        color: #34d399;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .date-text {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-icon {
        margin: 0 auto 1rem;
        opacity: 0.3;
    }
    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: center;
    }
    .pagination-wrapper .pagination {
        display: flex;
        gap: 0.4rem;
        list-style: none;
    }
    .pagination-wrapper .page-item .page-link {
        padding: 0.45rem 0.85rem;
        border-radius: 8px;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.83rem;
        transition: all 0.2s;
    }
    .pagination-wrapper .page-item.active .page-link {
        background: var(--primary-accent);
        color: #fff;
        border-color: var(--primary-accent);
    }
    .pagination-wrapper .page-item .page-link:hover {
        background: rgba(99,102,241,0.15);
        color: var(--text-main);
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Usage Logs</div>
        <div class="page-subtitle">Monitor all conversion activities per user</div>
    </div>
</div>

{{-- Summary Stats --}}
@php
    $totalConversions = \App\Models\Usage::when($filterUser, fn($q) => $q->where('user_id', $filterUser))->count();
    $totalSeconds     = \App\Models\Usage::when($filterUser, fn($q) => $q->where('user_id', $filterUser))->sum('total_seconds');
    $totalFiles       = \App\Models\Usage::when($filterUser, fn($q) => $q->where('user_id', $filterUser))->sum('file_count');
    $totalMinutes     = round($totalSeconds / 60, 1);
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Conversions</div>
        <div class="stat-value">{{ number_format($totalConversions) }}</div>
        <div class="stat-sub">{{ $filterUser ? 'For selected user' : 'All users' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Audio Processed</div>
        <div class="stat-value">{{ $totalMinutes }}</div>
        <div class="stat-sub">minutes</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Files</div>
        <div class="stat-value">{{ number_format($totalFiles) }}</div>
        <div class="stat-sub">audio files processed</div>
    </div>
</div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.usage') }}" class="filter-bar">
    <span class="filter-label">Filter by User:</span>
    <select name="user_id" class="filter-select">
        <option value="">— All Users —</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" {{ $filterUser == $u->id ? 'selected' : '' }}>
                {{ $u->first_name }} {{ $u->last_name }} ({{ $u->email }})
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn-filter">Apply Filter</button>
    @if($filterUser)
        <a href="{{ route('admin.usage') }}" class="btn-clear">Clear</a>
    @endif
</form>

{{-- Logs Table --}}
<div class="table-card">
    <div class="table-header">
        <span class="table-title">Conversion Logs</span>
        <span class="table-count">{{ $usages->total() }} record{{ $usages->total() !== 1 ? 's' : '' }}</span>
    </div>

    @if($usages->isEmpty())
        <div class="empty-state">
            <svg class="empty-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
            <p>No usage logs found{{ $filterUser ? ' for this user' : '' }}.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Duration</th>
                    <th>Files</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usages as $usage)
                <tr>
                    <td style="color: #64748b; font-size: 0.78rem;">{{ $usage->id }}</td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar-sm">{{ strtoupper(substr($usage->user?->first_name ?? '?', 0, 1)) }}</div>
                            <div>
                                <div class="user-name-sm">{{ $usage->user ? ($usage->user->first_name . ' ' . $usage->user->last_name) : 'Deleted User' }}</div>
                                <div class="user-email-sm">{{ $usage->user?->email ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="duration-badge">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ gmdate('H:i:s', $usage->total_seconds) }}
                        </span>
                    </td>
                    <td>
                        <span class="file-badge">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                            {{ $usage->file_count }} {{ $usage->file_count === 1 ? 'file' : 'files' }}
                        </span>
                    </td>
                    <td>
                        <span class="date-text" title="{{ $usage->created_at }}">
                            {{ $usage->created_at->format('d M Y, H:i') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($usages->hasPages())
        <div class="pagination-wrapper">
            {{ $usages->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
