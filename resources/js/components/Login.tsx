import React, { useState } from 'react';
import { Mail, Lock, LogIn, AlertCircle } from 'lucide-react';
import { useTranslation } from '../context/LanguageContext';

interface LoginProps {
  onLoginSuccess: (token: string, user: { id: number; first_name: string; last_name: string; email: string; role: 'admin' | 'user'; status?: string; is_active?: boolean }) => void;
  onGoToRegister?: () => void;
}

export const Login: React.FC<LoginProps> = ({ onLoginSuccess, onGoToRegister }) => {
  const { t } = useTranslation();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!email.trim() || !password.trim()) {
      setError('Please fill in all fields.');
      return;
    }

    setIsLoading(true);
    setError(null);

    try {
      const baseUrl = import.meta.env.VITE_API_URL || '';
      const response = await fetch(`${baseUrl}/api/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || t('login.error_invalid'));
      }

      onLoginSuccess(data.token, data.user);
    } catch (err: any) {
      let errorMsg = err.message || 'Cannot connect to authentication server. Make sure the backend is running.';
      if (errorMsg.includes('SQLSTATE') || errorMsg.toLowerCase().includes('database connection')) {
        errorMsg = 'Failed to connect.';
      } else if (errorMsg.includes('Failed to fetch')) {
        errorMsg = 'Cannot reach the authentication server.';
      }
      setError(errorMsg);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'flex-start', width: '100%', minHeight: '80vh', padding: '1.5rem 1rem', overflowY: 'auto' }}>
      <div className="glass-panel" style={{ width: '100%', maxWidth: '420px', padding: '2.5rem 2rem', display: 'flex', flexDirection: 'column', gap: '1.5rem', animation: 'fadeIn 0.5s ease-out' }}>
        <div style={{ textAlign: 'center' }}>
          <h2 style={{ fontSize: '1.8rem', fontWeight: 700, background: 'linear-gradient(135deg, #a5b4fc 0%, #6366f1 100%)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent', marginBottom: '0.5rem' }}>
            {t('login.welcome')}
          </h2>
          <p style={{ fontSize: '0.9rem', color: '#94a3b8' }}>
            {t('login.subtitle')}
          </p>
        </div>

        {error && (
          <div style={{ display: 'flex', alignItems: 'flex-start', gap: '0.75rem', background: 'rgba(239, 68, 68, 0.1)', border: '1px solid rgba(239, 68, 68, 0.2)', padding: '0.8rem 1rem', borderRadius: '10px', color: '#fca5a5', fontSize: '0.85rem' }}>
            <AlertCircle size={18} style={{ flexShrink: 0, marginTop: '0.1rem' }} />
            <span>{error}</span>
          </div>
        )}

        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <label style={{ fontSize: '0.75rem', fontWeight: 600, color: '#94a3b8', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
              {t('login.email_label')}
            </label>
            <div style={{ position: 'relative' }}>
              <Mail size={16} style={{ position: 'absolute', left: '0.9rem', top: '50%', transform: 'translateY(-50%)', color: '#64748b' }} />
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                disabled={isLoading}
                placeholder={t('login.email_placeholder')}
                style={{
                  width: '100%',
                  background: 'rgba(15, 23, 42, 0.6)',
                  border: '1px solid rgba(255, 255, 255, 0.1)',
                  borderRadius: '10px',
                  padding: '0.8rem 1rem 0.8rem 2.5rem',
                  fontSize: '0.9rem',
                  color: '#e2e8f0',
                  outline: 'none',
                  boxSizing: 'border-box',
                  transition: 'border-color 0.2s',
                }}
                onFocus={(e) => (e.currentTarget.style.borderColor = 'var(--primary-accent)')}
                onBlur={(e) => (e.currentTarget.style.borderColor = 'rgba(255, 255, 255, 0.1)')}
              />
            </div>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
            <label style={{ fontSize: '0.75rem', fontWeight: 600, color: '#94a3b8', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
              {t('login.password_label')}
            </label>
            <div style={{ position: 'relative' }}>
              <Lock size={16} style={{ position: 'absolute', left: '0.9rem', top: '50%', transform: 'translateY(-50%)', color: '#64748b' }} />
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                disabled={isLoading}
                placeholder={t('login.password_placeholder')}
                style={{
                  width: '100%',
                  background: 'rgba(15, 23, 42, 0.6)',
                  border: '1px solid rgba(255, 255, 255, 0.1)',
                  borderRadius: '10px',
                  padding: '0.8rem 1rem 0.8rem 2.5rem',
                  fontSize: '0.9rem',
                  color: '#e2e8f0',
                  outline: 'none',
                  boxSizing: 'border-box',
                  transition: 'border-color 0.2s',
                }}
                onFocus={(e) => (e.currentTarget.style.borderColor = 'var(--primary-accent)')}
                onBlur={(e) => (e.currentTarget.style.borderColor = 'rgba(255, 255, 255, 0.1)')}
              />
            </div>
          </div>

          <button
            type="submit"
            disabled={isLoading}
            style={{
              marginTop: '0.5rem',
              background: 'linear-gradient(135deg, var(--primary-accent) 0%, var(--secondary-accent) 100%)',
              color: '#ffffff',
              border: 'none',
              borderRadius: '10px',
              padding: '0.9rem',
              fontSize: '0.95rem',
              fontWeight: 600,
              cursor: isLoading ? 'not-allowed' : 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
              boxShadow: '0 2px 6px rgba(99, 102, 241, 0.15)',
              opacity: isLoading ? 0.7 : 1,
              transition: 'opacity 0.2s, transform 0.1s',
            }}
            onMouseDown={(e) => { if (!isLoading) e.currentTarget.style.transform = 'scale(0.98)'; }}
            onMouseUp={(e) => { if (!isLoading) e.currentTarget.style.transform = 'none'; }}
          >
            {isLoading ? t('login.logging_in') : t('login.button')}
            {!isLoading && <LogIn size={16} />}
          </button>
        </form>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.6rem', marginTop: '0.25rem' }}>
          {onGoToRegister && (
            <button
              onClick={onGoToRegister}
              style={{
                width: '100%',
                background: 'rgba(129,140,248,0.08)',
                color: '#a5b4fc',
                border: '1.5px solid rgba(129,140,248,0.25)',
                borderRadius: '10px',
                padding: '0.9rem',
                fontSize: '0.95rem',
                fontWeight: 600,
                cursor: 'pointer',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: '0.5rem',
                transition: 'background 0.2s, border-color 0.2s, color 0.2s',
              }}
              onMouseEnter={e => {
                e.currentTarget.style.background = 'rgba(129,140,248,0.16)';
                e.currentTarget.style.borderColor = 'rgba(129,140,248,0.5)';
                e.currentTarget.style.color = '#c4b5fd';
              }}
              onMouseLeave={e => {
                e.currentTarget.style.background = 'rgba(129,140,248,0.08)';
                e.currentTarget.style.borderColor = 'rgba(129,140,248,0.25)';
                e.currentTarget.style.color = '#a5b4fc';
              }}
              onMouseDown={e => { e.currentTarget.style.transform = 'scale(0.98)'; }}
              onMouseUp={e => { e.currentTarget.style.transform = 'none'; }}
            >
              Don't have an account? Create one →
            </button>
          )}
          <a
            href="/"
            style={{
              width: '100%',
              background: 'rgba(255,255,255,0.04)',
              color: '#64748b',
              border: '1.5px solid rgba(255,255,255,0.07)',
              borderRadius: '10px',
              padding: '0.9rem',
              fontSize: '0.95rem',
              fontWeight: 600,
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
              textDecoration: 'none',
              transition: 'background 0.2s, border-color 0.2s, color 0.2s',
              boxSizing: 'border-box',
            }}
            onMouseEnter={e => {
              e.currentTarget.style.background = 'rgba(255,255,255,0.08)';
              e.currentTarget.style.borderColor = 'rgba(255,255,255,0.15)';
              e.currentTarget.style.color = '#94a3b8';
            }}
            onMouseLeave={e => {
              e.currentTarget.style.background = 'rgba(255,255,255,0.04)';
              e.currentTarget.style.borderColor = 'rgba(255,255,255,0.07)';
              e.currentTarget.style.color = '#64748b';
            }}
          >
            ← Back to Home
          </a>
        </div>
      </div>
    </div>
  );
};
