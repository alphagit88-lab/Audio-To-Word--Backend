import React, { useState, useEffect } from 'react';
import { Users, UserPlus, Edit3, Trash2, X, Check, Eye, EyeOff, AlertCircle, Clock, CheckCircle2, XCircle, FileText, Phone } from 'lucide-react';
import { useTranslation } from '../context/LanguageContext';

interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  is_active: boolean;
  created_at: string;
}

interface PendingUser {
  id: number;
  name: string;
  first_name: string;
  last_name: string;
  phone: string;
  email: string;
  status: string;
  payment_proof: string | null;
  created_at: string;
}

interface UserManagementProps {
  token: string;
}

export const UserManagement: React.FC<UserManagementProps> = ({ token }) => {
  const { t } = useTranslation();
  const [activeTab, setActiveTab] = useState<'approved' | 'pending'>('approved');
  const [users, setUsers] = useState<User[]>([]);
  const [pendingUsers, setPendingUsers] = useState<PendingUser[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [pendingBadge, setPendingBadge] = useState(0);
  const [viewingProof, setViewingProof] = useState<string | null>(null);

  // Form states
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingUser, setEditingUser] = useState<User | null>(null);
  const [formData, setFormData] = useState({ first_name: '', last_name: '', email: '', password: '', is_active: true });
  const [showPassword, setShowPassword] = useState(false);
  const [formError, setFormError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const fetchUsers = async () => {
    setIsLoading(true);
    setError(null);
    try {
      const baseUrl = import.meta.env.VITE_API_URL || '';
      const [usersRes, pendingRes] = await Promise.all([
        fetch(`${baseUrl}/api/admin/users`, { headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } }),
        fetch(`${baseUrl}/api/admin/pending-users`, { headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } }),
      ]);
      if (usersRes.ok) {
        const d = await usersRes.json();
        setUsers(d.users || []);
      }
      if (pendingRes.ok) {
        const d = await pendingRes.json();
        setPendingUsers(d.users || []);
        setPendingBadge((d.users || []).length);
      }
    } catch (err: any) {
      setError(err.message || 'Failed to load users.');
    } finally {
      setIsLoading(false);
    }
  };

  const handleApprove = async (id: number) => {
    const baseUrl = import.meta.env.VITE_API_URL || '';
    try {
      const res = await fetch(`${baseUrl}/api/admin/pending-users/${id}/approve`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
      });
      if (res.ok) fetchUsers();
      else throw new Error((await res.json()).message);
    } catch (err: any) { alert(err.message || 'Failed to approve.'); }
  };

  const handleReject = async (id: number) => {
    if (!confirm('Reject this registration?')) return;
    const baseUrl = import.meta.env.VITE_API_URL || '';
    try {
      const res = await fetch(`${baseUrl}/api/admin/pending-users/${id}/reject`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
      });
      if (res.ok) fetchUsers();
      else throw new Error((await res.json()).message);
    } catch (err: any) { alert(err.message || 'Failed to reject.'); }
  };

  useEffect(() => {
    fetchUsers();
  }, [token]);

  const handleOpenCreate = () => {
    setEditingUser(null);
    setFormData({ first_name: '', last_name: '', email: '', password: '', is_active: true });
    setFormError(null);
    setIsFormOpen(true);
  };

  const handleOpenEdit = (user: User) => {
    setEditingUser(user);
    setFormData({ first_name: user.first_name || '', last_name: user.last_name || '', email: user.email, password: '', is_active: user.is_active });
    setFormError(null);
    setIsFormOpen(true);
  };

  const handleCloseForm = () => {
    setIsFormOpen(false);
    setEditingUser(null);
    setFormError(null);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setFormError(null);

    if (!formData.first_name.trim() || !formData.last_name.trim() || !formData.email.trim()) {
      setFormError('First Name, Last Name and Email are required.');
      return;
    }

    if (!editingUser && !formData.password.trim()) {
      setFormError('Password is required for new users.');
      return;
    }

    setIsSubmitting(true);
    const baseUrl = import.meta.env.VITE_API_URL || '';
    const method = editingUser ? 'PUT' : 'POST';
    const url = editingUser
      ? `${baseUrl}/api/admin/users/${editingUser.id}`
      : `${baseUrl}/api/admin/users`;

    const payload: any = {
      first_name: formData.first_name,
      last_name: formData.last_name,
      email: formData.email,
      is_active: formData.is_active,
    };

    if (formData.password.trim()) {
      payload.password = formData.password;
    }

    try {
      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.message || 'Failed to save user.');
      }

      handleCloseForm();
      fetchUsers();
    } catch (err: any) {
      setFormError(err.message || 'Failed to save user.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleDelete = async (userId: number) => {
    if (!confirm(`${t('users.confirm_delete')}?`)) return;

    try {
      const baseUrl = import.meta.env.VITE_API_URL || '';
      const response = await fetch(`${baseUrl}/api/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
      });

      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.message || 'Failed to delete user.');
      }

      fetchUsers();
    } catch (err: any) {
      alert(err.message || 'Failed to delete user.');
    }
  };

  return (
    <div style={{ width: '100%', animation: 'fadeIn 0.3s ease-out' }}>

      {/* Payment proof lightbox */}
      {viewingProof && (
        <div onClick={() => setViewingProof(null)} style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.88)', zIndex: 99999, display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'zoom-out' }}>
          {viewingProof.endsWith('.pdf') ? (
            <iframe src={viewingProof} style={{ width: '80vw', height: '85vh', border: 'none', borderRadius: '10px' }} />
          ) : (
            <img src={viewingProof} alt="Payment proof" style={{ maxWidth: '85vw', maxHeight: '85vh', borderRadius: '10px', objectFit: 'contain' }} />
          )}
          <button onClick={() => setViewingProof(null)} style={{ position: 'absolute', top: '1rem', right: '1.5rem', background: 'rgba(255,255,255,0.1)', border: 'none', borderRadius: '50%', width: 36, height: 36, color: '#fff', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <X size={18} />
          </button>
        </div>
      )}

      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.25rem' }}>
        {/* Tabs */}
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button
            onClick={() => setActiveTab('approved')}
            style={{ background: activeTab === 'approved' ? 'rgba(99,102,241,0.15)' : 'none', border: activeTab === 'approved' ? '1px solid rgba(99,102,241,0.3)' : '1px solid transparent', borderRadius: '7px', color: activeTab === 'approved' ? '#a5b4fc' : '#94a3b8', padding: '0.4rem 0.9rem', fontSize: '0.82rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.4rem', transition: 'all 0.2s' }}
          >
            <Users size={13} />
            Active Users
          </button>
          <button
            onClick={() => setActiveTab('pending')}
            style={{ background: activeTab === 'pending' ? 'rgba(245,158,11,0.15)' : 'none', border: activeTab === 'pending' ? '1px solid rgba(245,158,11,0.3)' : '1px solid transparent', borderRadius: '7px', color: activeTab === 'pending' ? '#fbbf24' : '#94a3b8', padding: '0.4rem 0.9rem', fontSize: '0.82rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.4rem', transition: 'all 0.2s', position: 'relative' }}
          >
            <Clock size={13} />
            Pending
            {pendingBadge > 0 && (
              <span style={{ background: '#f59e0b', color: '#000', borderRadius: '10px', fontSize: '0.65rem', fontWeight: 700, padding: '0.05rem 0.4rem', marginLeft: '0.2rem' }}>{pendingBadge}</span>
            )}
          </button>
        </div>

        {activeTab === 'approved' && (
          <button
            onClick={handleOpenCreate}
            style={{ background: 'linear-gradient(135deg, var(--primary-accent) 0%, var(--secondary-accent) 100%)', color: '#ffffff', border: 'none', borderRadius: '8px', padding: '0.5rem 1rem', fontSize: '0.85rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.4rem', boxShadow: '0 4px 12px rgba(99, 102, 241, 0.2)' }}
          >
            <UserPlus size={16} />
            {t('users.btn_add')}
          </button>
        )}
      </div>

      {error && (
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', background: 'rgba(239, 68, 68, 0.1)', border: '1px solid rgba(239, 68, 68, 0.2)', padding: '0.8rem 1rem', borderRadius: '10px', color: '#fca5a5', fontSize: '0.85rem', marginBottom: '1.5rem' }}>
          <AlertCircle size={16} />
          <span>{error}</span>
        </div>
      )}

      {/* ── Pending Users Tab ── */}
      {activeTab === 'pending' && (
        isLoading ? (
          <div style={{ textAlign: 'center', padding: '3rem 0', color: '#94a3b8' }}>Loading...</div>
        ) : pendingUsers.length === 0 ? (
          <div className="glass-panel" style={{ textAlign: 'center', padding: '3rem 2rem', color: '#94a3b8' }}>
            <CheckCircle2 size={32} color="#34d399" style={{ marginBottom: '0.75rem' }} />
            <div>No pending registrations</div>
          </div>
        ) : (
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
            {pendingUsers.map(pu => (
              <div key={pu.id} className="glass-panel" style={{ padding: '1.1rem 1.25rem', display: 'flex', flexDirection: 'column', gap: '0.6rem' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', flexWrap: 'wrap', gap: '0.5rem' }}>
                  <div>
                    <div style={{ fontWeight: 600, color: '#f8fafc', fontSize: '0.95rem' }}>{pu.first_name} {pu.last_name}</div>
                    <div style={{ color: '#818cf8', fontFamily: 'monospace', fontSize: '0.82rem' }}>{pu.email}</div>
                    {pu.phone && (
                      <div style={{ color: '#94a3b8', fontSize: '0.8rem', display: 'flex', alignItems: 'center', gap: '0.3rem', marginTop: '0.2rem' }}>
                        <Phone size={11} /> {pu.phone}
                      </div>
                    )}
                    <div style={{ color: '#475569', fontSize: '0.75rem', marginTop: '0.2rem' }}>Registered: {new Date(pu.created_at).toLocaleDateString()}</div>
                  </div>
                  <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center', flexWrap: 'wrap' }}>
                    {pu.payment_proof && (
                      <button
                        onClick={() => setViewingProof(pu.payment_proof!)}
                        style={{ background: 'rgba(99,102,241,0.1)', border: '1px solid rgba(99,102,241,0.3)', borderRadius: '6px', color: '#a5b4fc', padding: '0.35rem 0.7rem', fontSize: '0.78rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.35rem' }}
                      >
                        <FileText size={13} /> Payment Proof
                      </button>
                    )}
                    <button
                      onClick={() => handleApprove(pu.id)}
                      style={{ background: 'rgba(16,185,129,0.12)', border: '1px solid rgba(16,185,129,0.35)', borderRadius: '6px', color: '#34d399', padding: '0.35rem 0.7rem', fontSize: '0.78rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.35rem' }}
                    >
                      <CheckCircle2 size={13} /> Approve
                    </button>
                    <button
                      onClick={() => handleReject(pu.id)}
                      style={{ background: 'rgba(239,68,68,0.1)', border: '1px solid rgba(239,68,68,0.3)', borderRadius: '6px', color: '#fca5a5', padding: '0.35rem 0.7rem', fontSize: '0.78rem', fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '0.35rem' }}
                    >
                      <XCircle size={13} /> Reject
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )
      )}

      {/* ── Active Users Tab ── */}
      {activeTab === 'approved' && (
        isLoading ? (
        <div style={{ textAlign: 'center', padding: '3rem 0', color: '#94a3b8' }}>
          Loading users...
        </div>
      ) : users.length === 0 ? (
        <div className="glass-panel" style={{ textAlign: 'center', padding: '3rem 2rem', color: '#94a3b8' }}>
          {t('users.no_users')}
        </div>
      ) : (
        <div className="glass-panel" style={{ overflow: 'hidden', padding: 0 }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left', fontSize: '0.9rem' }}>
            <thead>
              <tr style={{ borderBottom: '1px solid rgba(255, 255, 255, 0.1)', background: 'rgba(15, 23, 42, 0.2)' }}>
                <th style={{ padding: '1rem', fontWeight: 600, color: '#94a3b8' }}>{t('users.col_name')}</th>
                <th style={{ padding: '1rem', fontWeight: 600, color: '#94a3b8' }}>{t('users.col_email')}</th>
                <th style={{ padding: '1rem', fontWeight: 600, color: '#94a3b8', textAlign: 'center' }}>Status</th>
                <th style={{ padding: '1rem', fontWeight: 600, color: '#94a3b8', textAlign: 'right' }}>{t('users.col_actions')}</th>
              </tr>
            </thead>
            <tbody>
              {users.map((user) => (
                <tr key={user.id} style={{ borderBottom: '1px solid rgba(255, 255, 255, 0.05)', transition: 'background 0.2s' }} onMouseEnter={(e) => e.currentTarget.style.background = 'rgba(255, 255, 255, 0.02)'} onMouseLeave={(e) => e.currentTarget.style.background = 'none'}>
                  <td style={{ padding: '1rem', fontWeight: 500, color: '#f8fafc' }}>{user.first_name} {user.last_name}</td>
                  <td style={{ padding: '1rem', color: '#cbd5e1', fontFamily: 'monospace' }}>{user.email}</td>
                  <td style={{ padding: '1rem', textAlign: 'center' }}>
                    <span style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      padding: '0.2rem 0.6rem',
                      borderRadius: '12px',
                      fontSize: '0.75rem',
                      fontWeight: 600,
                      background: user.is_active ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)',
                      color: user.is_active ? '#34d399' : '#fca5a5',
                    }}>
                      {user.is_active ? 'Active' : 'Inactive'}
                    </span>
                  </td>
                  <td style={{ padding: '1rem', textAlign: 'right' }}>
                    <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '0.5rem' }}>
                      <button
                        onClick={() => handleOpenEdit(user)}
                        title={t('users.btn_edit')}
                        style={{ background: 'rgba(255, 255, 255, 0.05)', border: 'none', borderRadius: '6px', padding: '0.4rem', color: '#6366f1', cursor: 'pointer', display: 'flex' }}
                        onMouseEnter={(e) => e.currentTarget.style.background = 'rgba(99, 102, 241, 0.1)'}
                        onMouseLeave={(e) => e.currentTarget.style.background = 'rgba(255, 255, 255, 0.05)'}
                      >
                        <Edit3 size={15} />
                      </button>
                      <button
                        onClick={() => handleDelete(user.id)}
                        title={t('users.btn_delete')}
                        style={{ background: 'rgba(255, 255, 255, 0.05)', border: 'none', borderRadius: '6px', padding: '0.4rem', color: '#ef4444', cursor: 'pointer', display: 'flex' }}
                        onMouseEnter={(e) => e.currentTarget.style.background = 'rgba(239, 68, 68, 0.1)'}
                        onMouseLeave={(e) => e.currentTarget.style.background = 'rgba(255, 255, 255, 0.05)'}
                      >
                        <Trash2 size={15} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ))}

      {/* Modal Dialog Form */}
      {isFormOpen && (
        <div style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, background: 'rgba(15, 23, 42, 0.7)', backdropFilter: 'blur(4px)', display: 'flex', justifyContent: 'center', alignItems: 'center', zIndex: 1000, padding: '1rem' }}>
          <div className="glass-panel" style={{ width: '100%', maxWidth: '420px', padding: '2rem', display: 'flex', flexDirection: 'column', gap: '1.25rem', position: 'relative' }}>
            <button
              onClick={handleCloseForm}
              style={{ position: 'absolute', right: '1.25rem', top: '1.25rem', background: 'none', border: 'none', color: '#94a3b8', cursor: 'pointer' }}
            >
              <X size={20} />
            </button>

            <h3 style={{ fontSize: '1.15rem', fontWeight: 600, color: '#f8fafc' }}>
              {editingUser ? t('modal.edit_title') : t('modal.add_title')}
            </h3>

            {formError && (
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: '0.5rem', background: 'rgba(239, 68, 68, 0.1)', border: '1px solid rgba(239, 68, 68, 0.2)', padding: '0.7rem 0.9rem', borderRadius: '8px', color: '#fca5a5', fontSize: '0.8rem' }}>
                <AlertCircle size={16} style={{ flexShrink: 0, marginTop: '0.1rem' }} />
                <span>{formError}</span>
              </div>
            )}

            <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.75rem' }}>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
                  <label style={{ fontSize: '0.75rem', fontWeight: 600, color: '#94a3b8', textTransform: 'uppercase' }}>First Name</label>
                  <input
                    type="text"
                    required
                    value={formData.first_name}
                    onChange={(e) => setFormData({ ...formData, first_name: e.target.value })}
                    placeholder="John"
                    style={{
                      width: '100%',
                      background: 'rgba(15, 23, 42, 0.6)',
                      border: '1px solid rgba(255, 255, 255, 0.1)',
                      borderRadius: '8px',
                      padding: '0.7rem 0.9rem',
                      fontSize: '0.85rem',
                      color: '#e2e8f0',
                      outline: 'none',
                      boxSizing: 'border-box',
                    }}
                  />
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
                  <label style={{ fontSize: '0.75rem', fontWeight: 600, color: '#94a3b8', textTransform: 'uppercase' }}>Last Name</label>
                  <input
                    type="text"
                    required
                    value={formData.last_name}
                    onChange={(e) => setFormData({ ...formData, last_name: e.target.value })}
                    placeholder="Doe"
                    style={{
                      width: '100%',
                      background: 'rgba(15, 23, 42, 0.6)',
                      border: '1px solid rgba(255, 255, 255, 0.1)',
                      borderRadius: '8px',
                      padding: '0.7rem 0.9rem',
                      fontSize: '0.85rem',
                      color: '#e2e8f0',
                      outline: 'none',
                      boxSizing: 'border-box',
                    }}
                  />
                </div>
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
                <label style={{ fontSize: '0.75rem', fontWeight: 600, color: '#94a3b8', textTransform: 'uppercase' }}>{t('modal.email_label')}</label>
                <input
                  type="email"
                  required
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  placeholder={t('modal.email_placeholder')}
                  style={{
                    width: '100%',
                    background: 'rgba(15, 23, 42, 0.6)',
                    border: '1px solid rgba(255, 255, 255, 0.1)',
                    borderRadius: '8px',
                    padding: '0.7rem 0.9rem',
                    fontSize: '0.85rem',
                    color: '#e2e8f0',
                    outline: 'none',
                    boxSizing: 'border-box',
                  }}
                />
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
                <label style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.75rem', fontWeight: 600, color: '#94a3b8', textTransform: 'uppercase' }}>
                  <span>{t('modal.password_label')}</span>
                  {editingUser && <span style={{ textTransform: 'none', fontWeight: 400, color: '#64748b' }}>({t('modal.password_placeholder_edit')})</span>}
                </label>
                <div style={{ position: 'relative' }}>
                  <input
                    type={showPassword ? 'text' : 'password'}
                    required={!editingUser}
                    value={formData.password}
                    onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                    placeholder={editingUser ? t('modal.password_placeholder_edit') : t('modal.password_placeholder_add')}
                    style={{
                      width: '100%',
                      background: 'rgba(15, 23, 42, 0.6)',
                      border: '1px solid rgba(255, 255, 255, 0.1)',
                      borderRadius: '8px',
                      padding: '0.7rem 2.5rem 0.7rem 0.9rem',
                      fontSize: '0.85rem',
                      color: '#e2e8f0',
                      outline: 'none',
                      boxSizing: 'border-box',
                    }}
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    style={{ position: 'absolute', right: '0.75rem', top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: '#64748b', display: 'flex' }}
                  >
                    {showPassword ? <EyeOff size={16} /> : <Eye size={16} />}
                  </button>
                </div>
              </div>

              {editingUser && (
                <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginTop: '0.25rem' }}>
                  <input
                    type="checkbox"
                    id="isActiveCheck"
                    checked={formData.is_active}
                    onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
                    style={{ cursor: 'pointer' }}
                  />
                  <label htmlFor="isActiveCheck" style={{ fontSize: '0.85rem', color: '#cbd5e1', cursor: 'pointer' }}>
                    Active User Account
                  </label>
                </div>
              )}

              <button
                type="submit"
                disabled={isSubmitting}
                style={{
                  marginTop: '0.5rem',
                  background: 'linear-gradient(135deg, var(--primary-accent) 0%, var(--secondary-accent) 100%)',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '8px',
                  padding: '0.8rem',
                  fontSize: '0.9rem',
                  fontWeight: 600,
                  cursor: isSubmitting ? 'not-allowed' : 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '0.4rem',
                  opacity: isSubmitting ? 0.7 : 1,
                  boxShadow: '0 4px 12px rgba(99, 102, 241, 0.2)',
                }}
              >
                {isSubmitting ? t('modal.btn_saving') : t('modal.btn_save')}
                {!isSubmitting && <Check size={16} />}
              </button>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
