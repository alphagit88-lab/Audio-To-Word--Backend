import React, { useState, useEffect, useRef } from 'react';
import { Send, MessageCircle, Loader2 } from 'lucide-react';

interface Message {
  id: number;
  sender_type: 'guest' | 'admin';
  message: string;
  created_at: string;
}

interface InlineSupportChatProps {
  userEmail: string;
  userName: string;
  baseUrl: string;
}

export const InlineSupportChat: React.FC<InlineSupportChatProps> = ({ userEmail, userName, baseUrl }) => {
  const [sessionId, setSessionId] = useState<string | null>(() => localStorage.getItem('support_chat_session_id'));
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState('');
  const [isStarting, setIsStarting] = useState(false);
  const [isSending, setIsSending] = useState(false);
  const [chatStarted, setChatStarted] = useState(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const pollRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const lastMsgIdRef = useRef(0);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  // If we already have a session, restore the chat and start polling
  useEffect(() => {
    if (sessionId) {
      setChatStarted(true);
      fetchMessages();
      startPolling();
    }
    return () => stopPolling();
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const fetchMessages = async (sid?: string) => {
    const id = sid ?? sessionId;
    if (!id) return;
    try {
      const res = await fetch(`${baseUrl}/api/chat/${id}/messages`);
      if (!res.ok) return;
      const data = await res.json();
      const msgs: Message[] = data.messages ?? [];
      if (msgs.length > 0) {
        lastMsgIdRef.current = msgs[msgs.length - 1].id;
      }
      setMessages(msgs);
    } catch {
      // silent
    }
  };

  const startPolling = (sid?: string) => {
    stopPolling();
    pollRef.current = setInterval(async () => {
      const id = sid ?? sessionId;
      if (!id) return;
      try {
        const res = await fetch(`${baseUrl}/api/chat/${id}/messages`);
        if (!res.ok) return;
        const data = await res.json();
        const msgs: Message[] = data.messages ?? [];
        setMessages(msgs);
        if (msgs.length > 0) lastMsgIdRef.current = msgs[msgs.length - 1].id;
      } catch {
        // silent
      }
    }, 3000);
  };

  const stopPolling = () => {
    if (pollRef.current) clearInterval(pollRef.current);
  };

  const handleStartChat = async () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    setIsStarting(true);
    try {
      const res = await fetch(`${baseUrl}/api/chat/start`, {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ name: userName, email: userEmail }),
      });
      const data = await res.json();
      const newSessionId = data.session_id;
      setSessionId(newSessionId);
      localStorage.setItem('support_chat_session_id', newSessionId);
      setChatStarted(true);
      await fetchMessages(newSessionId);
      startPolling(newSessionId);
    } catch {
      // silent
    } finally {
      setIsStarting(false);
    }
  };

  const handleSend = async () => {
    if (!input.trim() || !sessionId) return;
    const text = input.trim();
    setInput('');
    setIsSending(true);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    try {
      await fetch(`${baseUrl}/api/chat/${sessionId}/message`, {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ message: text }),
      });
      await fetchMessages();
    } catch {
      // silent
    } finally {
      setIsSending(false);
    }
  };

  return (
    <div style={{
      background: 'rgba(15, 23, 42, 0.8)',
      border: '1px solid rgba(255, 255, 255, 0.08)',
      borderRadius: '16px',
      overflow: 'hidden',
      display: 'flex',
      flexDirection: 'column',
      width: '100%',
    }}>
      {/* Header */}
      <div style={{
        background: 'linear-gradient(135deg, #6366f1, #8b5cf6)',
        padding: '1.25rem 1.5rem',
        display: 'flex',
        alignItems: 'center',
        gap: '0.75rem',
      }}>
        <div style={{
          width: '40px', height: '40px', borderRadius: '50%',
          background: 'rgba(255,255,255,0.2)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
        }}>
          <MessageCircle size={20} color="white" />
        </div>
        <div>
          <div style={{ fontWeight: 600, color: '#fff', fontSize: '0.95rem' }}>Happy Steno Admin</div>
          <div style={{ fontSize: '0.75rem', color: 'rgba(255,255,255,0.75)' }}>Usually replies in a few minutes</div>
        </div>
      </div>

      {/* Body */}
      <div style={{ flex: 1, minHeight: '260px', maxHeight: '360px', overflowY: 'auto', padding: '1.25rem', display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
        {!chatStarted ? (
          <div style={{ textAlign: 'center', padding: '2rem 1rem' }}>
            <p style={{ color: '#94a3b8', marginBottom: '1.5rem', lineHeight: 1.6 }}>
              👋 Hi <strong style={{ color: '#e2e8f0' }}>{userName}</strong>! Chat with the admin for help with your account.
            </p>
            <button
              onClick={handleStartChat}
              disabled={isStarting}
              style={{
                background: 'linear-gradient(135deg, #6366f1, #8b5cf6)',
                color: '#fff',
                border: 'none',
                borderRadius: '8px',
                padding: '0.75rem 2rem',
                fontSize: '0.9rem',
                fontWeight: 600,
                cursor: isStarting ? 'not-allowed' : 'pointer',
                opacity: isStarting ? 0.7 : 1,
                display: 'inline-flex',
                alignItems: 'center',
                gap: '0.5rem',
              }}
            >
              {isStarting ? <><Loader2 size={16} className="spin" /> Starting...</> : 'Start Chat'}
            </button>
          </div>
        ) : messages.length === 0 ? (
          <div style={{ textAlign: 'center', color: '#64748b', marginTop: '2rem', fontSize: '0.9rem' }}>
            Your chat session is open. Send a message to begin.
          </div>
        ) : (
          <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem', width: '100%' }}>
            {messages.map(msg => {
              const isGuest = msg.sender_type === 'guest';
              return (
                <div key={msg.id} style={{
                  display: 'flex',
                  justifyContent: isGuest ? 'flex-end' : 'flex-start',
                }}>
                  <div style={{
                    maxWidth: '80%',
                    background: isGuest ? '#6366f1' : 'rgba(255,255,255,0.10)',
                    color: isGuest ? '#fff' : '#e2e8f0',
                    borderRadius: isGuest ? '12px 12px 2px 12px' : '12px 12px 12px 2px',
                    padding: '0.6rem 0.9rem',
                    fontSize: '0.875rem',
                    lineHeight: 1.5,
                  }}>
                    {!isGuest && (
                      <div style={{ fontSize: '0.7rem', color: '#818cf8', fontWeight: 600, marginBottom: '0.2rem' }}>Admin</div>
                    )}
                    {msg.message}
                  </div>
                </div>
              );
            })}
          </div>
        )}
        <div ref={messagesEndRef} />
      </div>

      {/* Footer */}
      {chatStarted && (
        <div style={{
          borderTop: '1px solid rgba(255,255,255,0.07)',
          padding: '0.75rem 1rem',
          display: 'flex',
          gap: '0.5rem',
          background: 'rgba(15, 23, 42, 0.6)',
        }}>
          <input
            type="text"
            value={input}
            onChange={e => setInput(e.target.value)}
            onKeyPress={e => e.key === 'Enter' && handleSend()}
            placeholder="Type your message..."
            style={{
              flex: 1,
              background: 'rgba(255,255,255,0.05)',
              border: '1px solid rgba(255,255,255,0.1)',
              borderRadius: '8px',
              padding: '0.65rem 0.9rem',
              color: '#e2e8f0',
              fontSize: '0.875rem',
              outline: 'none',
            }}
          />
          <button
            onClick={handleSend}
            disabled={isSending || !input.trim()}
            style={{
              background: '#6366f1',
              color: '#fff',
              border: 'none',
              borderRadius: '8px',
              width: '42px',
              height: '42px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: isSending || !input.trim() ? 'not-allowed' : 'pointer',
              opacity: isSending || !input.trim() ? 0.5 : 1,
              flexShrink: 0,
            }}
          >
            <Send size={16} />
          </button>
        </div>
      )}
    </div>
  );
};
