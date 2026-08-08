@extends('layouts.admin')

@section('title', 'API Keys')
@section('page_title', 'API Keys')

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

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover td {
            background: rgba(99, 102, 241, 0.04);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-paid {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .badge-free {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }

        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }

        .key-mask {
            font-family: monospace;
            font-size: 0.82rem;
            color: #94a3b8;
            max-width: 320px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.35rem 0.75rem;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-edit {
            background: rgba(99, 102, 241, 0.12);
            color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.25);
        }

        .btn-edit:hover {
            background: rgba(99, 102, 241, 0.22);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.22);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
            color: #fff;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
            transition: opacity 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(7, 10, 20, 0.8);
            backdrop-filter: blur(6px);
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: linear-gradient(145deg, #0f172a, #1a2235);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 16px;
            padding: 2rem;
            width: 480px;
            max-width: 95vw;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.6);
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-main);
            padding: 0.7rem 0.9rem;
            font-size: 0.88rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #94a3b8;
            padding: 0.6rem 1.1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #475569;
        }

        .empty-state svg {
            margin-bottom: 0.75rem;
            opacity: 0.4;
        }
    </style>
@endsection

@section('content')

    <div class="page-header-actions">
        <div>
            <div style="font-size:1.1rem;font-weight:700;color:var(--text-main);">Gemini API Keys</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.2rem;">{{ $apiKeys->count() }}
                key{{ $apiKeys->count() !== 1 ? 's' : '' }} configured</div>
        </div>
        <button class="btn-primary" onclick="openAddModal()">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Add Key
        </button>
    </div>

    <div class="table-card">
        @if($apiKeys->isEmpty())
            <div class="empty-state">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path
                        d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3m-3.5 3.5L19 4" />
                </svg>
                <div style="font-size:0.95rem;font-weight:600;color:#64748b;margin-bottom:0.35rem;">No API keys yet</div>
                <div style="font-size:0.8rem;color:#475569;">Click "Add Key" to add your first Gemini API key.</div>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Key</th>
                        <th>Subscription</th>
                        <th>Quota Renewal</th>
                        <th>Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apiKeys as $k)
                        <tr>
                            <td style="color:#64748b;font-size:0.8rem;">{{ $k->id }}</td>
                            <td>
                                <div class="key-mask" title="{{ $k->key }}">
                                    {{ substr($k->key, 0, 6) }}••••••••••••{{ substr($k->key, -4) }}
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $k->subscription === 'Paid' ? 'badge-paid' : 'badge-free' }}">
                                    {{ $k->subscription }}
                                </span>
                            </td>
                            <td style="font-size:0.83rem;color:#94a3b8;">{{ $k->quota_renewal->format('Y-m-d H:i') }}</td>
                            <td style="font-size:0.83rem;color:#64748b;">{{ $k->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display:flex;gap:0.5rem;">
                                    <button class="action-btn btn-edit"
                                        onclick="openEditModal({{ $k->id }}, '{{ addslashes($k->key) }}', '{{ $k->subscription }}', '{{ $k->quota_renewal->format('Y-m-d\TH:i') }}')">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.api_keys.destroy', $k->id) }}" style="margin:0;"
                                        onsubmit="return confirm('Delete this API key?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Add Modal --}}
    <div class="modal-overlay" id="addModal">
        <div class="modal-box">
            <div class="modal-title">Add API Key</div>
            <form method="POST" action="{{ route('admin.api_keys.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="add_key">API Key</label>
                    <input class="form-control" type="text" id="add_key" name="key" placeholder="Enter Gemini API key..."
                        required autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label" for="add_subscription">Subscription</label>
                    <select class="form-control" id="add_subscription" name="subscription" required>
                        <option value="Free">Free</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="add_quota_renewal">Quota Renewal</label>
                    <input class="form-control" type="datetime-local" id="add_quota_renewal" name="quota_renewal" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModals()">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Add Key
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal-overlay" id="editModal">
        <div class="modal-box">
            <div class="modal-title">Edit API Key</div>
            <form method="POST" id="editForm" action="">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="edit_key">API Key</label>
                    <input class="form-control" type="text" id="edit_key" name="key" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_subscription">Subscription</label>
                    <select class="form-control" id="edit_subscription" name="subscription" required>
                        <option value="Free">Free</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_quota_renewal">Quota Renewal</label>
                    <input class="form-control" type="datetime-local" id="edit_quota_renewal" name="quota_renewal" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModals()">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Default to current datetime for add modal
        function openAddModal() {
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            const local = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
            document.getElementById('add_quota_renewal').value = local;
            document.getElementById('addModal').classList.add('open');
        }

        function openEditModal(id, key, subscription, quotaRenewal) {
            document.getElementById('editForm').action = `/admin/api-keys/${id}`;
            document.getElementById('edit_key').value = key;
            document.getElementById('edit_subscription').value = subscription;
            document.getElementById('edit_quota_renewal').value = quotaRenewal;
            document.getElementById('editModal').classList.add('open');
        }

        function closeModals() {
            document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'));
        }

        // Close on backdrop click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function (e) {
                if (e.target === this) closeModals();
            });
        });
    </script>

@endsection