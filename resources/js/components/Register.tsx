import React, { useState } from 'react';
import { User, Phone, Mail, Lock, FileText, LogIn, AlertCircle, CheckCircle2 } from 'lucide-react';

interface RegisterProps {
  onGoToLogin: () => void;
}

export const Register: React.FC<RegisterProps> = ({ onGoToLogin }) => {
  const [form, setForm] = useState({
    first_name: '',
    last_name: '',
    phone: '',
    email: '',
    password: '',
  });
  const [paymentProof, setPaymentProof] = useState<File | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const set = (field: string) => (e: React.ChangeEvent<HTMLInputElement>) =>
    setForm(prev => ({ ...prev, [field]: e.target.value }));

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    if (!form.first_name.trim() || !form.last_name.trim() || !form.phone.trim() || !form.email.trim() || !form.password.trim()) {
      setError('Please fill in all required fields.');
      return;
    }

    setIsLoading(true);
    try {
      const baseUrl = import.meta.env.VITE_API_URL || '';
      const formData = new FormData();
      formData.append('first_name', form.first_name);
      formData.append('last_name', form.last_name);
      formData.append('phone', form.phone);
      formData.append('email', form.email);
      formData.append('password', form.password);
      if (paymentProof) formData.append('payment_proof', paymentProof);

      const res = await fetch(`${baseUrl}/api/register`, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: formData,
      });

      const data = await res.json();
      if (!res.ok) {
        const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Registration failed.');
        throw new Error(msg as string);
      }
      setSuccess(true);
    } catch (err: any) {
      setError(err.message || 'Could not connect to server.');
    } finally {
      setIsLoading(false);
    }
  };

  const inputStyle: React.CSSProperties = {
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
  };

  const labelStyle: React.CSSProperties = {
    fontSize: '0.75rem',
    fontWeight: 600,
    color: '#94a3b8',
    textTransform: 'uppercase',
    letterSpacing: '0.05em',
  };

  const iconStyle: React.CSSProperties = {
    position: 'absolute',
    left: '0.9rem',
    top: '50%',
    transform: 'translateY(-50%)',
    color: '#64748b',
  };

  if (success) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', width: '100%', minHeight: '80vh', padding: '1rem' }}>
        <div className="glass-panel" style={{ width: '100%', maxWidth: '480px', padding: '2.5rem 2rem', display: 'flex', flexDirection: 'column', gap: '1.5rem', animation: 'fadeIn 0.5s ease-out', textAlign: 'center' }}>
          <div style={{ display: 'flex', justifyContent: 'center' }}>
            <div style={{ width: 64, height: 64, borderRadius: '50%', background: 'rgba(16,185,129,0.15)', border: '2px solid rgba(16,185,129,0.4)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <CheckCircle2 size={32} color="#34d399" />
            </div>
          </div>
          <div>
            <h2 style={{ fontSize: '1.6rem', fontWeight: 700, background: 'linear-gradient(135deg, #34d399 0%, #10b981 100%)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent', marginBottom: '0.75rem' }}>
              Registration Submitted!
            </h2>
            <p style={{ color: '#94a3b8', fontSize: '0.95rem', lineHeight: 1.7 }}>
              Your account is <strong style={{ color: '#fbbf24' }}>pending approval</strong>. Please contact admin via the live chat for account activation. You'll be able to log in once your account has been approved.
            </p>
          </div>
          <button
            onClick={onGoToLogin}
            style={{
              background: 'linear-gradient(135deg, var(--primary-accent) 0%, var(--secondary-accent) 100%)',
              color: '#fff',
              border: 'none',
              borderRadius: '10px',
              padding: '0.9rem',
              fontSize: '0.95rem',
              fontWeight: 600,
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
              boxShadow: '0 2px 6px rgba(99, 102, 241, 0.15)',
            }}
          >
            <LogIn size={16} />
            Go to Login
          </button>
        </div>
      </div>
    );
  }

  return (
    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'flex-start', width: '100%', minHeight: '80vh', padding: '1.5rem 1rem', overflowY: 'auto' }}>
      <div className="glass-panel" style={{ width: '100%', maxWidth: '480px', padding: '2.5rem 2rem', display: 'flex', flexDirection: 'column', gap: '1.5rem', animation: 'fadeIn 0.5s ease-out' }}>
        <div style={{ textAlign: 'center' }}>
          <h2 style={{ fontSize: '1.8rem', fontWeight: 700, background: 'linear-gradient(135deg, #a5b4fc 0%, #6366f1 100%)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent', marginBottom: '0.5rem' }}>
            Create Account
          </h2>
          <p style={{ fontSize: '0.9rem', color: '#94a3b8' }}>
            Fill in your details to request access
          </p>
        </div>

        {error && (
          <div style={{ display: 'flex', alignItems: 'flex-start', gap: '0.75rem', background: 'rgba(239, 68, 68, 0.1)', border: '1px solid rgba(239, 68, 68, 0.2)', padding: '0.8rem 1rem', borderRadius: '10px', color: '#fca5a5', fontSize: '0.85rem' }}>
            <AlertCircle size={18} style={{ flexShrink: 0, marginTop: '0.1rem' }} />
            <span>{error}</span>
          </div>
        )}

        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.1rem' }}>
          {/* First + Last Name row */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.75rem' }}>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
              <label style={labelStyle}>First Name *</label>
              <div style={{ position: 'relative' }}>
                <User size={15} style={iconStyle} />
                <input type="text" value={form.first_name} onChange={set('first_name')} disabled={isLoading}
                  placeholder="John" style={inputStyle}
                  onFocus={e => (e.currentTarget.style.borderColor = 'var(--primary-accent)')}
                  onBlur={e => (e.currentTarget.style.borderColor = 'rgba(255,255,255,0.1)')} />
              </div>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
              <label style={labelStyle}>Last Name *</label>
              <div style={{ position: 'relative' }}>
                <User size={15} style={iconStyle} />
                <input type="text" value={form.last_name} onChange={set('last_name')} disabled={isLoading}
                  placeholder="Doe" style={inputStyle}
                  onFocus={e => (e.currentTarget.style.borderColor = 'var(--primary-accent)')}
                  onBlur={e => (e.currentTarget.style.borderColor = 'rgba(255,255,255,0.1)')} />
              </div>
            </div>
          </div>

          {/* Phone */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
            <label style={labelStyle}>Phone Number *</label>
            <div style={{ position: 'relative' }}>
              <Phone size={15} style={iconStyle} />
              <input type="tel" value={form.phone} onChange={set('phone')} disabled={isLoading}
                placeholder="+94 77 123 4567" style={inputStyle}
                onFocus={e => (e.currentTarget.style.borderColor = 'var(--primary-accent)')}
                onBlur={e => (e.currentTarget.style.borderColor = 'rgba(255,255,255,0.1)')} />
            </div>
          </div>

          {/* Email */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
            <label style={labelStyle}>Email Address *</label>
            <div style={{ position: 'relative' }}>
              <Mail size={15} style={iconStyle} />
              <input type="email" value={form.email} onChange={set('email')} disabled={isLoading}
                placeholder="john@example.com" style={inputStyle}
                onFocus={e => (e.currentTarget.style.borderColor = 'var(--primary-accent)')}
                onBlur={e => (e.currentTarget.style.borderColor = 'rgba(255,255,255,0.1)')} />
            </div>
          </div>

          {/* Password */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
            <label style={labelStyle}>Password *</label>
            <div style={{ position: 'relative' }}>
              <Lock size={15} style={iconStyle} />
              <input type="password" value={form.password} onChange={set('password')} disabled={isLoading}
                placeholder="Min. 6 characters" style={inputStyle}
                onFocus={e => (e.currentTarget.style.borderColor = 'var(--primary-accent)')}
                onBlur={e => (e.currentTarget.style.borderColor = 'rgba(255,255,255,0.1)')} />
            </div>
          </div>

          {/* Payment proof */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.4rem' }}>
            <label style={labelStyle}>Payment Proof <span style={{ color: '#475569', fontWeight: 400, textTransform: 'none' }}>(optional – PDF or image)</span></label>
            <div style={{ position: 'relative' }}>
              <FileText size={15} style={{ ...iconStyle, top: '1.1rem', transform: 'none' }} />
              <input
                type="file"
                accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                disabled={isLoading}
                onChange={e => setPaymentProof(e.target.files?.[0] || null)}
                style={{
                  ...inputStyle,
                  padding: '0.6rem 1rem 0.6rem 2.5rem',
                  cursor: 'pointer',
                  color: paymentProof ? '#e2e8f0' : '#64748b',
                }}
              />
            </div>
            {paymentProof && (
              <span style={{ fontSize: '0.75rem', color: '#34d399', paddingLeft: '0.5rem' }}>
                ✓ {paymentProof.name}
              </span>
            )}
          </div>

          <button
            type="submit"
            disabled={isLoading}
            style={{
              marginTop: '0.25rem',
              background: 'linear-gradient(135deg, var(--primary-accent) 0%, var(--secondary-accent) 100%)',
              color: '#fff',
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
            onMouseDown={e => { if (!isLoading) e.currentTarget.style.transform = 'scale(0.98)'; }}
            onMouseUp={e => { if (!isLoading) e.currentTarget.style.transform = 'none'; }}
          >
            {isLoading ? 'Submitting…' : 'Submit Registration'}
          </button>
        </form>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.6rem' }}>
          <button
            onClick={onGoToLogin}
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
            Already have an account? Sign in →
          </button>
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
