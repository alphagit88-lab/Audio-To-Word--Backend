@extends('layouts.admin')

@section('title', 'Active Users')
@section('page_title', 'Active Users')

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
        }

        .action-btn-edit:hover {
            background: rgba(99, 102, 241, 0.2);
            color: #818cf8;
            border-color: rgba(99, 102, 241, 0.4);
        }

        .action-btn-delete:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.4);
        }

        /* Modal Backdrop */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .modal-backdrop.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-card {
            width: 100%;
            max-width: 440px;
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .modal-title {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: var(--text-main);
        }

        .modal-close {
            position: absolute;
            right: 1.25rem;
            top: 1.25rem;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.7rem 0.9rem;
            font-size: 0.88rem;
            color: var(--text-main);
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary-accent);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
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
        <form action="{{ route('admin.users') }}" method="GET" style="margin: 0;" id="searchForm">
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
                    <a href="{{ route('admin.users') }}"
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

        <button class="btn-primary" onclick="openCreateModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
            Add New User
        </button>
    </div>

    <!-- Users Table Card -->
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th style="text-align: center;">Status</th>
                        <th>Created Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td style="font-weight: 500;">{{ $u->first_name }} {{ $u->last_name }}</td>
                            <td style="font-family: monospace; color: #cbd5e1;">{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin')
                                    <span class="badge badge-admin">Admin</span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">User</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($u->is_active)
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted); font-size: 0.82rem;">
                                {{ $u->created_at ? $u->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.4rem;">
                                    <button class="action-btn action-btn-edit" title="Edit User"
                                        onclick="openEditModal({{ $u->id }}, '{{ addslashes($u->first_name) }}', '{{ addslashes($u->last_name) }}', '{{ addslashes($u->email) }}', {{ $u->is_active ? 'true' : 'false' }})">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                    </button>

                                    @if($u->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete user {{ addslashes($u->first_name) }} {{ addslashes($u->last_name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-delete" title="Delete User">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                                No user accounts found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create User Modal -->
    <div class="modal-backdrop" id="createModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeCreateModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h3 class="modal-title">Create New User Account</h3>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="first_name" class="form-control" required placeholder="First Name">
                        <input type="text" name="last_name" class="form-control" required placeholder="Last Name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="user@company.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimum 6 characters">
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="create_is_active" name="is_active" value="1" checked
                        style="cursor: pointer;">
                    <label for="create_is_active" style="font-size: 0.85rem; color: #cbd5e1; cursor: pointer;">
                        Active User Account
                    </label>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                    Save User Account
                </button>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal-backdrop" id="editModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeEditModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h3 class="modal-title">Edit User Account</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="edit_first_name" name="first_name" class="form-control" required placeholder="First Name">
                        <input type="text" id="edit_last_name" name="last_name" class="form-control" required placeholder="Last Name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" id="edit_email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span
                            style="font-weight: 400; text-transform: none; color: #64748b;">(leave blank to keep
                            unchanged)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" style="cursor: pointer;">
                    <label for="edit_is_active" style="font-size: 0.85rem; color: #cbd5e1; cursor: pointer;">
                        Active User Account
                    </label>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                    Update User Account
                </button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.add('active');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.remove('active');
        }

        function openEditModal(id, firstName, lastName, email, isActive) {
            const form = document.getElementById('editForm');
            form.action = `{{ url('') }}/admin/users/${id}`;
            document.getElementById('edit_first_name').value = firstName;
            document.getElementById('edit_last_name').value = lastName;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_is_active').checked = isActive;
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }
    </script>
@endsection