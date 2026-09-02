import React, { useEffect, useState } from 'react';
import { FolderOpen, Trash2, Loader2, FolderKanban, Clock, Layers, AlertCircle } from 'lucide-react';
import { SavedProject } from '../types';

interface Props {
  onOpenProject: (project: SavedProject) => void;
  token: string | null;
}

const BASE_URL = import.meta.env.VITE_API_URL || '';

export const ProjectsPage: React.FC<Props> = ({ onOpenProject, token }) => {
  const [projects, setProjects] = useState<SavedProject[]>([]);
  const [loading, setLoading] = useState(true);
  const [deletingId, setDeletingId] = useState<string | null>(null);
  const [openingId, setOpeningId] = useState<string | null>(null);
  const [error, setError] = useState('');

  const authHeaders = {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };

  const load = async () => {
    setLoading(true);
    setError('');
    try {
      const res = await fetch(`${BASE_URL}/api/projects`, { headers: authHeaders });
      if (!res.ok) throw new Error('Server error');
      const list = await res.json();
      setProjects(list as SavedProject[]);
    } catch {
      setError('Failed to load projects.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => { load(); }, []);

  const handleOpen = async (id: string) => {
    setOpeningId(id);
    try {
      const res = await fetch(`${BASE_URL}/api/projects/${id}`, { headers: authHeaders });
      if (!res.ok) throw new Error('Server error');
      const project = await res.json();
      onOpenProject(project as SavedProject);
    } catch {
      setError('Failed to load project.');
    } finally {
      setOpeningId(null);
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Delete this project? This cannot be undone.')) return;
    setDeletingId(id);
    try {
      const res = await fetch(`${BASE_URL}/api/projects/${id}`, {
        method: 'DELETE',
        headers: authHeaders,
      });
      if (!res.ok) throw new Error('Server error');
      setProjects((prev) => prev.filter((p) => p.id !== id));
    } catch {
      setError('Failed to delete project.');
    } finally {
      setDeletingId(null);
    }
  };

  const formatDate = (iso: string) => {
    try {
      return new Date(iso).toLocaleString();
    } catch {
      return iso;
    }
  };

  return (
    <div style={{ width: '100%', maxWidth: '720px', margin: '0 auto', padding: '1.5rem 0' }}>
      <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', marginBottom: '1.5rem' }}>
        <FolderKanban size={22} color="#818cf8" />
        <h2 style={{ fontSize: '1.25rem', fontWeight: 700, color: '#e2e8f0', margin: 0 }}>Saved Projects</h2>
      </div>

      {error && (
        <div style={{ background: 'rgba(239,68,68,0.1)', border: '1px solid rgba(239,68,68,0.3)', borderRadius: '8px', padding: '0.75rem 1rem', color: '#fca5a5', fontSize: '0.85rem', marginBottom: '1rem', display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <AlertCircle size={14} /> {error}
        </div>
      )}

      {loading ? (
        <div style={{ display: 'flex', justifyContent: 'center', padding: '3rem', color: '#64748b' }}>
          <Loader2 size={24} style={{ animation: 'spin 1s linear infinite' }} />
        </div>
      ) : projects.length === 0 ? (
        <div style={{ textAlign: 'center', padding: '4rem 2rem', color: '#475569' }}>
          <FolderKanban size={48} style={{ opacity: 0.3, marginBottom: '1rem' }} />
          <p style={{ fontSize: '0.95rem' }}>No saved projects yet.</p>
          <p style={{ fontSize: '0.8rem', marginTop: '0.25rem' }}>Use "Save Project" in the transcription review panel to save your work.</p>
        </div>
      ) : (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
          {projects.map((p) => (
            <div
              key={p.id}
              style={{
                background: 'rgba(15,23,42,0.6)',
                border: '1px solid rgba(255,255,255,0.07)',
                borderRadius: '10px',
                padding: '1rem 1.25rem',
                display: 'flex',
                alignItems: 'center',
                gap: '1rem',
                transition: 'border-color 0.15s',
              }}
              onMouseEnter={(e) => (e.currentTarget.style.borderColor = 'rgba(99,102,241,0.35)')}
              onMouseLeave={(e) => (e.currentTarget.style.borderColor = 'rgba(255,255,255,0.07)')}
            >
              <div style={{ flexShrink: 0, background: 'rgba(99,102,241,0.12)', borderRadius: '8px', padding: '0.6rem', display: 'flex' }}>
                <FolderKanban size={22} color="#818cf8" />
              </div>

              <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ fontWeight: 600, color: '#e2e8f0', fontSize: '0.95rem', marginBottom: '0.3rem', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                  {p.name}
                </div>
                <div style={{ display: 'flex', gap: '1rem', fontSize: '0.75rem', color: '#64748b' }}>
                  <span style={{ display: 'flex', alignItems: 'center', gap: '0.3rem' }}>
                    <Clock size={11} /> {formatDate(p.savedAt)}
                  </span>
                  <span style={{ display: 'flex', alignItems: 'center', gap: '0.3rem' }}>
                    <Layers size={11} /> {p.totalChunks} part{p.totalChunks !== 1 ? 's' : ''}
                  </span>
                </div>
              </div>

              <div style={{ display: 'flex', gap: '0.5rem', flexShrink: 0 }}>
                <button
                  onClick={() => handleOpen(p.id)}
                  disabled={openingId === p.id || deletingId === p.id}
                  style={{
                    background: 'rgba(99,102,241,0.12)',
                    border: '1px solid rgba(99,102,241,0.3)',
                    borderRadius: '6px',
                    color: '#a5b4fc',
                    padding: '0.4rem 0.9rem',
                    fontSize: '0.8rem',
                    fontWeight: 600,
                    cursor: openingId === p.id ? 'not-allowed' : 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.35rem',
                  }}
                >
                  {openingId === p.id
                    ? <><Loader2 size={12} style={{ animation: 'spin 1s linear infinite' }} /> Opening…</>
                    : <><FolderOpen size={12} /> Open</>
                  }
                </button>
                <button
                  onClick={() => handleDelete(p.id)}
                  disabled={deletingId === p.id || openingId === p.id}
                  style={{
                    background: 'rgba(239,68,68,0.08)',
                    border: '1px solid rgba(239,68,68,0.25)',
                    borderRadius: '6px',
                    color: '#f87171',
                    padding: '0.4rem 0.5rem',
                    cursor: deletingId === p.id ? 'not-allowed' : 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                  }}
                  title="Delete project"
                >
                  {deletingId === p.id
                    ? <Loader2 size={13} style={{ animation: 'spin 1s linear infinite' }} />
                    : <Trash2 size={13} />
                  }
                </button>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};
