@extends('layouts.admin')

@section('title', 'Pending Users')
@section('page_title', 'Pending Users')

@section('styles')
    <style>
        .page-header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.4rem 0.8rem;
            width: 280px;
        }

        .search-input {
            background: none;
            border: none;
            color: var(--text-main);
            outline: none;
            font-size: 0.85rem;
            width: 100%;
            margin-left: 0.5rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.88rem;
        }

        .data-table th {
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            background: rgba(15, 23, 42, 0.3);
        }

        .data-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-main);
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 0.35rem 0.55rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }

        .action-btn-approve:hover {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.4);
        }

        .action-btn-reject:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.4);
        }

        .payment-link {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            color: #818cf8;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
        }

        .payment-link:hover {
            text-decoration: underline;
        }

        /* Laravel Pagination Style overrides */
        .pagination-wrapper {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
        }
    </style>
@endsection

@section('content')

    <div class="page-header-actions">
        <form action="{{ route('admin.pending_users') }}" method="GET" style="margin: 0;" id="searchForm">
            <div class="search-box">
                <button type="submit"
                    style="background:none; border:none; padding:0; cursor:pointer; display:flex; align-items:center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" x2="16.65" y1="21" y2="16.65" />
                    </svg>
                </button>
                <input type="text" name="search" class="search-input" placeholder="Search by name or email..."
                    value="{{ $search }}">
                @if($search)
                    <a href="{{ route('admin.pending_users') }}"
                        style="color: #64748b; text-decoration: none; display: flex; align-items: center; margin-left: 0.5rem;"
                        title="Clear Search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Pending Users Table Card -->
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Phone</th>
                        <th>Payment Proof</th>
                        <th>Registered Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td style="font-weight: 500;">{{ $u->first_name }} {{ $u->last_name }}</td>
                            <td style="font-family: monospace; color: #cbd5e1;">{{ $u->email }}</td>
                            <td>{{ $u->phone ?? 'N/A' }}</td>
                            <td>
                                @if($u->payment_proof)
                                    <a href="{{ url('storage/' . $u->payment_proof) }}" target="_blank" class="payment-link">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        View File
                                    </a>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">None</span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted); font-size: 0.82rem;">
                                {{ $u->created_at ? $u->created_at->format('M d, Y h:i A') : 'N/A' }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.4rem;">
                                    <form action="{{ route('admin.pending_users.approve', $u->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn action-btn-approve" title="Approve User">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.pending_users.reject', $u->id) }}" method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to REJECT user {{ addslashes($u->first_name) }} {{ addslashes($u->last_name) }}?');">
                                        @csrf
                                        <button type="submit" class="action-btn action-btn-reject" title="Reject User">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" opacity="0.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" x2="12" y1="8" y2="12" />
                                        <line x1="12" x2="12.01" y1="16" y2="16" />
                                    </svg>
                                    <div>No pending users found.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links('vendor.pagination.simple-tailwind') }}
            </div>
        @endif
    </div>

@endsection