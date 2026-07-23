@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('styles')
    <style>
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .metric-val {
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .metric-lbl {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-responsive {
            overflow-x: auto;
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

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-active {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
        }

        .badge-admin {
            background: rgba(99, 102, 241, 0.18);
            color: #818cf8;
        }
    </style>
@endsection

@section('content')

    <!-- Metrics Cards Grid -->
    <div class="metrics-grid">
        <div class="glass-card metric-card">
            <div class="metric-icon"
                style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div>
                <div class="metric-val">{{ $totalUsers }}</div>
                <div class="metric-lbl">Total User Accounts</div>
            </div>
        </div>

        <div class="glass-card metric-card">
            <div class="metric-icon"
                style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <div>
                <div class="metric-val">{{ $activeUsers }}</div>
                <div class="metric-lbl">Active Users</div>
            </div>
        </div>

        <div class="glass-card metric-card">
            <div class="metric-icon" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fca5a5" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
            </div>
            <div>
                <div class="metric-val">{{ $inactiveUsers }}</div>
                <div class="metric-lbl">Inactive Users</div>
            </div>
        </div>

        <div class="glass-card metric-card">
            <div class="metric-icon"
                style="background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.3);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
            </div>
            <div>
                <div class="metric-val">{{ $adminCount }}</div>
                <div class="metric-lbl">Admin Accounts</div>
            </div>
        </div>
    </div>

    <!-- Recent Registered Accounts -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="section-title">
            <span>Recent Accounts</span>
            <a href="{{ route('admin.users') }}" class="btn-primary" style="padding: 0.45rem 0.9rem; font-size: 0.8rem;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
                Manage All Users
            </a>
        </div>

        <div class="glass-card table-responsive" style="padding: 0; overflow: hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: right;">Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $u)
                        <tr>
                            <td style="font-weight: 500;">{{ $u->name }}</td>
                            <td style="font-family: monospace; color: var(--text-muted);">{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin')
                                    <span class="badge badge-admin">Admin</span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">User</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($u->is_active)
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td style="text-align: right; color: var(--text-muted); font-size: 0.8rem;">
                                {{ $u->created_at ? $u->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                No user accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection