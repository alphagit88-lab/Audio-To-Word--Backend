<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Happy Steno — Convert audio files into perfectly formatted Microsoft Word documents in minutes. AI-powered transcription for Windows.">
    <meta name="keywords"
        content="audio to word, transcription, speech to text, audio transcription, docx, Happy Steno">
    <meta property="og:title" content="Happy Steno — Audio to Word, Effortlessly">
    <meta property="og:description"
        content="AI-powered desktop app that converts audio files into formatted Word documents. Fast, accurate, and private.">
    <meta property="og:type" content="website">
    <title>Happy Steno — Audio to Word, Effortlessly</title>

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/images/logo.jpg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        /* ── Design Tokens ─────────────────────────────────────────────── */
        :root {
            --bg-dark: #0f172a;
            --bg-mid: #1e1b4b;
            --panel-bg: rgba(30, 41, 59, 0.65);
            --panel-border: rgba(255, 255, 255, 0.08);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.35);
            --secondary: #8b5cf6;
            --gradient: linear-gradient(135deg, #6366f1, #8b5cf6);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --success: #10b981;
            --border-subtle: rgba(255, 255, 255, 0.06);
        }

        /* ── Reset & Base ──────────────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-dark);
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(99, 102, 241, 0.25) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 90% 90%, rgba(139, 92, 246, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 10% 70%, rgba(99, 102, 241, 0.1) 0%, transparent 60%);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Animated background dots grid ──────────────────────────────── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Scrollbar ───────────────────────────────────────────────────── */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.4);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.7);
        }

        /* ── Glass Utility ───────────────────────────────────────────────── */
        .glass {
            background: var(--panel-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--panel-border);
        }

        /* ── Gradient text ───────────────────────────────────────────────── */
        .gradient-text {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── NAV ─────────────────────────────────────────────────────────── */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 0 max(24px, calc(50% - 640px));
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border-subtle);
            transition: background 0.3s;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo img {
            width: 36px;
            height: 36px;
            border-radius: 8px;
        }

        .nav-logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--text-primary);
        }

        .nav-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 22px;
            background: var(--gradient);
            color: #fff !important;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s !important;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .nav-cta:hover {
            opacity: 0.9 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 30px var(--primary-glow) !important;
        }

        /* ── HERO ────────────────────────────────────────────────────────── */
        .hero {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px max(24px, calc(50% - 640px)) 80px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #a5b4fc;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 32px;
        }

        .hero-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--primary);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.4);
            }
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(3rem, 7vw, 5.5rem);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -0.03em;
            margin-bottom: 24px;
            max-width: 860px;
        }

        .hero-sub {
            font-size: clamp(1rem, 2vw, 1.25rem);
            color: var(--text-secondary);
            max-width: 560px;
            line-height: 1.7;
            margin-bottom: 48px;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 72px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 32px;
            background: var(--gradient);
            color: #fff;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 0 30px var(--primary-glow), 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: glowPulse 3s infinite ease-in-out;
        }

        @keyframes glowPulse {

            0%,
            100% {
                box-shadow: 0 0 30px var(--primary-glow), 0 4px 20px rgba(0, 0, 0, 0.3);
            }

            50% {
                box-shadow: 0 0 50px rgba(99, 102, 241, 0.6), 0 4px 20px rgba(0, 0, 0, 0.3);
            }
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            animation: none;
            box-shadow: 0 0 60px rgba(99, 102, 241, 0.7), 0 8px 30px rgba(0, 0, 0, 0.4);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--panel-border);
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .btn-secondary:hover {
            border-color: rgba(99, 102, 241, 0.5);
            color: var(--text-primary);
            background: rgba(99, 102, 241, 0.08);
        }

        /* ── Hero Workflow Visual ─────────────────────────────────────────── */
        .hero-visual {
            position: relative;
            max-width: 900px;
            width: 100%;
        }

        .hero-visual::before {
            content: '';
            position: absolute;
            inset: -60px;
            background: radial-gradient(ellipse at center, rgba(99, 102, 241, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Workflow container */
        .workflow {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto 1fr;
            align-items: center;
            gap: 0;
            padding: 40px 32px;
            background: var(--panel-bg);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 24px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5), 0 0 60px rgba(99, 102, 241, 0.1);
        }

        /* Arrow connector */
        .wf-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
        }

        .wf-arrow svg {
            color: var(--primary);
            opacity: 0.5;
            animation: arrowPulse 2s ease-in-out infinite;
        }

        .wf-arrow:nth-of-type(2) svg {
            animation-delay: 0.4s;
        }

        @keyframes arrowPulse {

            0%,
            100% {
                opacity: 0.3;
                transform: translateX(0);
            }

            50% {
                opacity: 0.9;
                transform: translateX(4px);
            }
        }

        /* Each workflow step card */
        .wf-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            padding: 28px 20px;
            border-radius: 18px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--panel-border);
            transition: border-color 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .wf-step:hover {
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.1);
        }

        .wf-step-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .wf-step-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            text-align: center;
        }

        /* ── Step 1: Audio File ── */
        .audio-card {
            width: 100%;
            background: rgba(99, 102, 241, 0.06);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .audio-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .audio-info {
            flex: 1;
            min-width: 0;
        }

        .audio-filename {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .audio-meta {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Waveform bars */
        .waveform {
            display: flex;
            align-items: center;
            gap: 3px;
            height: 32px;
            margin-top: 4px;
        }

        .wave-bar {
            width: 3px;
            border-radius: 2px;
            background: linear-gradient(to top, #6366f1, #8b5cf6);
            animation: waveAnim 1.2s ease-in-out infinite;
            transform-origin: bottom;
        }

        .wave-bar:nth-child(1) {
            height: 8px;
            animation-delay: 0.0s;
        }

        .wave-bar:nth-child(2) {
            height: 18px;
            animation-delay: 0.1s;
        }

        .wave-bar:nth-child(3) {
            height: 26px;
            animation-delay: 0.2s;
        }

        .wave-bar:nth-child(4) {
            height: 14px;
            animation-delay: 0.3s;
        }

        .wave-bar:nth-child(5) {
            height: 30px;
            animation-delay: 0.15s;
        }

        .wave-bar:nth-child(6) {
            height: 20px;
            animation-delay: 0.25s;
        }

        .wave-bar:nth-child(7) {
            height: 10px;
            animation-delay: 0.05s;
        }

        .wave-bar:nth-child(8) {
            height: 24px;
            animation-delay: 0.35s;
        }

        .wave-bar:nth-child(9) {
            height: 16px;
            animation-delay: 0.2s;
        }

        .wave-bar:nth-child(10) {
            height: 28px;
            animation-delay: 0.4s;
        }

        .wave-bar:nth-child(11) {
            height: 12px;
            animation-delay: 0.1s;
        }

        .wave-bar:nth-child(12) {
            height: 22px;
            animation-delay: 0.3s;
        }

        .wave-bar:nth-child(13) {
            height: 8px;
            animation-delay: 0.45s;
        }

        .wave-bar:nth-child(14) {
            height: 18px;
            animation-delay: 0.15s;
        }

        .wave-bar:nth-child(15) {
            height: 14px;
            animation-delay: 0.25s;
        }

        @keyframes waveAnim {

            0%,
            100% {
                transform: scaleY(0.4);
                opacity: 0.5;
            }

            50% {
                transform: scaleY(1.0);
                opacity: 1;
            }
        }

        /* ── Step 2: AI Processing ── */
        .ai-orb {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #818cf8, #6366f1 40%, #4f46e5);
            box-shadow: 0 0 0 8px rgba(99, 102, 241, 0.12), 0 0 40px rgba(99, 102, 241, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: orbPulse 3s ease-in-out infinite;
        }

        @keyframes orbPulse {

            0%,
            100% {
                box-shadow: 0 0 0 8px rgba(99, 102, 241, 0.12), 0 0 40px rgba(99, 102, 241, 0.3);
            }

            50% {
                box-shadow: 0 0 0 16px rgba(99, 102, 241, 0.06), 0 0 60px rgba(139, 92, 246, 0.6);
            }
        }

        .ai-orb::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: rgba(167, 139, 250, 0.8);
            border-right-color: rgba(99, 102, 241, 0.4);
            animation: orbSpin 2s linear infinite;
        }

        @keyframes orbSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .ai-orb-icon {
            font-size: 2rem;
        }

        .ai-dots {
            display: flex;
            gap: 6px;
            margin-top: 4px;
        }

        .ai-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--primary);
            animation: dotBounce 1.2s ease-in-out infinite;
        }

        .ai-dot:nth-child(2) {
            animation-delay: 0.2s;
            background: #8b5cf6;
        }

        .ai-dot:nth-child(3) {
            animation-delay: 0.4s;
            background: #a78bfa;
        }

        @keyframes dotBounce {

            0%,
            80%,
            100% {
                transform: translateY(0);
                opacity: 0.4;
            }

            40% {
                transform: translateY(-8px);
                opacity: 1;
            }
        }

        /* ── Step 3: Word Document ── */
        .doc-preview {
            width: 100%;
            background: #ffffff;
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .doc-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .doc-logo {
            width: 18px;
            height: 18px;
            background: #2b579a;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 900;
            color: white;
            flex-shrink: 0;
            font-family: 'Outfit', sans-serif;
        }

        .doc-title-text {
            font-size: 0.68rem;
            font-weight: 600;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .doc-lines {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .doc-line {
            height: 6px;
            border-radius: 3px;
            background: #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .doc-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border-radius: 3px;
            animation: typeIn 3s ease-out infinite;
        }

        .doc-line:nth-child(1)::after {
            width: 85%;
            animation-delay: 0.0s;
        }

        .doc-line:nth-child(2)::after {
            width: 72%;
            animation-delay: 0.3s;
        }

        .doc-line:nth-child(3)::after {
            width: 91%;
            animation-delay: 0.6s;
        }

        .doc-line:nth-child(4)::after {
            width: 60%;
            animation-delay: 0.9s;
        }

        .doc-line:nth-child(5)::after {
            width: 78%;
            animation-delay: 1.2s;
        }

        @keyframes typeIn {
            0% {
                width: 0;
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            60%,
            100% {
                opacity: 0.6;
            }
        }

        .doc-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 8px;
            padding: 3px 8px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 700;
            color: #10b981;
        }

        @media (max-width: 700px) {
            .workflow {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }

            .wf-arrow {
                transform: rotate(90deg);
                padding: 4px 0;
            }
        }

        /* ── STATS BAR ────────────────────────────────────────────────────── */
        .stats-bar {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            gap: 0;
            padding: 0 max(24px, calc(50% - 640px));
            margin-top: -1px;
        }

        .stats-inner {
            display: flex;
            align-items: stretch;
            width: 100%;
            max-width: 900px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--panel-border);
            background: var(--panel-bg);
            backdrop-filter: blur(20px);
        }

        .stat-item {
            flex: 1;
            padding: 28px 24px;
            text-align: center;
            border-right: 1px solid var(--panel-border);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ── SECTION COMMON ──────────────────────────────────────────────── */
        section {
            position: relative;
            z-index: 1;
        }

        .section-inner {
            padding: 100px max(24px, calc(50% - 640px));
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .section-subtitle {
            font-size: 1.05rem;
            color: var(--text-secondary);
            line-height: 1.7;
            max-width: 540px;
        }

        /* ── FEATURES ────────────────────────────────────────────────────── */
        #features {
            background: linear-gradient(to bottom, transparent, rgba(30, 27, 75, 0.4), transparent);
        }

        .features-header {
            margin-bottom: 64px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .feature-card {
            padding: 32px;
            border-radius: 20px;
            border: 1px solid var(--panel-border);
            background: var(--panel-bg);
            backdrop-filter: blur(16px);
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), 0 0 30px rgba(99, 102, 241, 0.08);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(99, 102, 241, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .feature-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.65;
        }

        /* ── HOW IT WORKS ────────────────────────────────────────────────── */
        #how-it-works {}

        .steps-header {
            margin-bottom: 42px;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 0;
            position: relative;
        }

        .steps-grid::after {
            content: '';
            position: absolute;
            top: 10px;
            left: calc(16.66% + 24px);
            right: calc(16.66% + 24px);
            height: 1px;
            background: linear-gradient(to right, transparent, var(--primary), transparent);
            opacity: 0.4;
        }

        .step-card {
            text-align: center;
            padding: 40px 32px;
            position: relative;
        }

        .step-number {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            border: 2px solid rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0 auto 24px;
            position: relative;
        }

        .step-number-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            transition: background 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        .step-card:hover .step-number-wrap {
            background: rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.2);
        }

        .step-num-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .step-icon {
            font-size: 1.8rem;
            margin-bottom: 4px;
            display: inline-block;
        }

        .step-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .step-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.65;
        }

        /* ── DOWNLOAD SECTION ────────────────────────────────────────────── */
        #download {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
            border-top: 1px solid var(--border-subtle);
            border-bottom: 1px solid var(--border-subtle);
        }

        .download-inner {
            display: flex;
            align-items: center;
            gap: 64px;
            flex-wrap: wrap;
        }

        .download-text {
            flex: 1;
            min-width: 280px;
        }

        .download-box {
            flex: 0 0 auto;
            min-width: 300px;
        }

        .download-card {
            padding: 36px;
            border-radius: 24px;
            border: 1px solid rgba(99, 102, 241, 0.25);
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            text-align: center;
        }

        .os-icon {
            font-size: 3rem;
            margin-bottom: 16px;
            display: block;
        }

        .os-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .version-badge {
            display: inline-block;
            padding: 3px 12px;
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #34d399;
            margin-bottom: 24px;
        }

        .download-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            background: var(--gradient);
            color: #fff;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
            margin-bottom: 12px;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.6);
        }

        .download-requirements {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* ── TESTIMONIAL / QUOTE STRIP ───────────────────────────────────── */
        .quote-strip {
            position: relative;
            z-index: 1;
            overflow: hidden;
            padding: 60px max(24px, calc(50% - 640px));
            text-align: center;
        }

        .quote-text {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.4rem, 3vw, 2.2rem);
            font-weight: 700;
            color: var(--text-secondary);
            max-width: 720px;
            margin: 0 auto;
            line-height: 1.4;
        }

        .quote-text em {
            font-style: normal;
            color: var(--text-primary);
        }

        /* ── FOOTER ──────────────────────────────────────────────────────── */
        footer {
            position: relative;
            z-index: 1;
            padding: 48px max(24px, calc(50% - 640px));
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .footer-brand img {
            width: 30px;
            height: 30px;
            border-radius: 6px;
        }

        .footer-brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-primary);
        }

        .footer-links {
            display: flex;
            gap: 24px;
            list-style: none;
        }

        .footer-links a {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--text-secondary);
        }

        .footer-copy {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        /* ── FLOATING PARTICLES ──────────────────────────────────────────── */
        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            animation: float-particle linear infinite;
        }

        @keyframes float-particle {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 0.6;
            }

            90% {
                opacity: 0.2;
            }

            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0;
            }
        }

        /* ── RESPONSIVE ──────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .steps-grid::after {
                display: none;
            }

            .download-inner {
                flex-direction: column;
                gap: 40px;
            }

            .download-box {
                width: 100%;
            }

            footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-inner {
                flex-direction: column;
            }

            .stat-item {
                border-right: none;
                border-bottom: 1px solid var(--panel-border);
            }

            .stat-item:last-child {
                border-bottom: none;
            }
        }

        /* ── Entrance animations ─────────────────────────────────────────── */
        [data-reveal] {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        [data-reveal].is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <!-- ── Floating Particles ── -->
    <div id="particles-container"></div>

    <!-- ── Navigation ── -->
    <nav>
        <a href="#" class="nav-logo">
            <img src="/images/logo.jpg" alt="Happy Steno Logo">
            <span class="nav-logo-text">Happy Steno</span>
        </a>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#how-it-works">How It Works</a></li>
            <li><a href="#download" class="nav-cta">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Download
                </a></li>
        </ul>
    </nav>

    <!-- ── HERO ── -->
    <section class="hero">
        <div class="hero-badge" data-reveal>
            <span class="hero-badge-dot"></span>
            AI-Powered Audio Transcription
        </div>

        <h1 data-reveal>
            Turn Any Audio Into<br>
            <span class="gradient-text">Polished Word Docs</span>
        </h1>

        <p class="hero-sub" data-reveal>
            Happy Steno listens, understands, and delivers — converting your audio files into perfectly formatted
            Microsoft Word documents with AI precision. No typist needed.
        </p>

        <div class="hero-actions" data-reveal>
            <a href="#download" class="btn-primary" id="hero-download-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Download for Windows
            </a>
            <a href="#features" class="btn-secondary" id="learn-more-btn">
                Learn more
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- ── Animated Workflow Visual ── -->
        <div class="hero-visual" data-reveal>
            <div class="workflow">

                <!-- Step 1: Audio Input -->
                <div class="wf-step">
                    <span class="wf-step-label">Step 01</span>
                    <div class="audio-card">
                        <div class="audio-icon">🎵</div>
                        <div class="audio-info">
                            <div class="audio-filename">Interview_Recording.mp3</div>
                            <div class="audio-meta">58:14 &nbsp;·&nbsp; 45 MB &nbsp;·&nbsp; MP3</div>
                        </div>
                    </div>
                    <div class="waveform">
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                    </div>
                    <span class="wf-step-title">Audio File</span>
                </div>

                <!-- Arrow 1 -->
                <div class="wf-arrow">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </div>

                <!-- Step 2: AI Processing -->
                <div class="wf-step">
                    <span class="wf-step-label">Step 02</span>
                    <div class="ai-orb">
                        <span class="ai-orb-icon">🤖</span>
                    </div>
                    <div class="ai-dots">
                        <div class="ai-dot"></div>
                        <div class="ai-dot"></div>
                        <div class="ai-dot"></div>
                    </div>
                    <span class="wf-step-title">Gemini AI<br>Transcribes</span>
                </div>

                <!-- Arrow 2 -->
                <div class="wf-arrow">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </div>

                <!-- Step 3: Word Document Output -->
                <div class="wf-step">
                    <span class="wf-step-label">Step 03</span>
                    <div class="doc-preview">
                        <div class="doc-header">
                            <div class="doc-logo">W</div>
                            <span class="doc-title-text">Interview_Recording.docx</span>
                        </div>
                        <div class="doc-lines">
                            <div class="doc-line"></div>
                            <div class="doc-line"></div>
                            <div class="doc-line"></div>
                            <div class="doc-line"></div>
                            <div class="doc-line"></div>
                        </div>
                        <div class="doc-badge">
                            <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            Ready to download
                        </div>
                    </div>
                    <span class="wf-step-title">Word Document</span>
                </div>

            </div>
        </div>
    </section>

    <!-- ── Stats Bar ── -->
    <div class="stats-bar">
        <div class="stats-inner glass" data-reveal>
            <div class="stat-item">
                <div class="stat-value">5+</div>
                <div class="stat-label">Audio Formats</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">95%</div>
                <div class="stat-label">Accuracy Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">5x</div>
                <div class="stat-label">Faster than Manual</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">.docx</div>
                <div class="stat-label">Native Word Output</div>
            </div>
        </div>
    </div>

    <!-- ── FEATURES ── -->
    <section id="features">
        <div class="section-inner">
            <div class="features-header" data-reveal>
                <div class="section-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Features
                </div>
                <h2 class="section-title">Everything you need to<br><span class="gradient-text">transcribe with
                        confidence</span></h2>
                <p class="section-subtitle">Built for professionals who need speed, accuracy, and clean output — every
                    single time.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">🎙️</div>
                    <h3 class="feature-title">Multi-Format Audio Support</h3>
                    <p class="feature-desc">Works with MP3, WAV, M4A, FLAC, OGG, AAC, and 40+ more audio formats. Drop
                        your file and go.</p>
                </div>
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">🤖</div>
                    <h3 class="feature-title">Gemini AI Engine</h3>
                    <p class="feature-desc">Powered by Google's Gemini AI for state-of-the-art speech recognition and
                        contextual understanding with near-human accuracy.</p>
                </div>
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">📄</div>
                    <h3 class="feature-title">Native Word Output</h3>
                    <p class="feature-desc">Exports directly to .docx with proper formatting, headings, speaker labels,
                        and paragraph structure — ready to share.</p>
                </div>
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">🏷️</div>
                    <h3 class="feature-title">Speaker Identification</h3>
                    <p class="feature-desc">Automatically detect and label different speakers in the audio. Perfect for
                        interviews, meetings, and multi-person recordings.</p>
                </div>
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">⚡</div>
                    <h3 class="feature-title">Batch Processing</h3>
                    <p class="feature-desc">Queue multiple audio files and convert them all in one session. Handles
                        large files by splitting them intelligently.</p>
                </div>
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">📂</div>
                    <h3 class="feature-title">Project Management</h3>
                    <p class="feature-desc">Organise transcriptions into projects. Resume, review, and finalise any
                        conversion — your work is always saved.</p>
                </div>
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Privacy First</h3>
                    <p class="feature-desc">Your audio files never leave your device. All processing happens locally
                        with the desktop app, ensuring complete privacy.</p>
                </div>
                <div class="feature-card" data-reveal>
                    <div class="feature-icon">🔄</div>
                    <h3 class="feature-title">Continuous Updates</h3>
                    <p class="feature-desc">Get the latest AI improvements and app features automatically delivered
                        through our built-in updater system.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ── QUOTE STRIP ── -->
    <div class="quote-strip">
        <p class="quote-text" data-reveal>
            "From raw audio to a <em>polished Word document</em> — in minutes, not hours. That's the Happy Steno
            promise."
        </p>
    </div>

    <!-- ── HOW IT WORKS ── -->
    <section id="how-it-works">
        <div class="section-inner">
            <div class="steps-header" data-reveal style="text-align:center;">
                <div class="section-tag" style="justify-content:center;">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2v-4M9 21H5a2 2 0 0 1-2-2v-4m0 0h18" />
                    </svg>
                    How It Works
                </div>
                <h2 class="section-title">Three steps to your<br><span class="gradient-text">finished document</span>
                </h2>
            </div>

            <div class="steps-grid">
                <div class="step-card" data-reveal>
                    <div class="step-number-wrap">
                        <span class="step-num-text">01</span>
                    </div>
                    <span class="step-icon">🎵</span>
                    <h3 class="step-title">Drop Your Audio</h3>
                    <p class="step-desc">Select one or more audio files from your computer. Any format, any length —
                        Happy Steno handles it automatically.</p>
                </div>
                <div class="step-card" data-reveal>
                    <div class="step-number-wrap">
                        <span class="step-num-text">02</span>
                    </div>
                    <span class="step-icon">🧠</span>
                    <h3 class="step-title">AI Transcribes</h3>
                    <p class="step-desc">Our Gemini AI engine processes the audio, identifies speakers, and generates
                        clean, structured text with exceptional accuracy.</p>
                </div>
                <div class="step-card" data-reveal>
                    <div class="step-number-wrap">
                        <span class="step-num-text">03</span>
                    </div>
                    <span class="step-icon">📥</span>
                    <h3 class="step-title">Download .docx</h3>
                    <p class="step-desc">Review the transcription in the built-in editor, make any edits, then export
                        your perfectly formatted Word document.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ── DOWNLOAD ── -->
    <section id="download">
        <div class="section-inner">
            <div class="download-inner">
                <div class="download-text" data-reveal>
                    <div class="section-tag">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                        Download
                    </div>
                    <h2 class="section-title">Ready to start<br><span class="gradient-text">transcribing smarter?</span>
                    </h2>
                    <p class="section-subtitle" style="margin-bottom:32px;">
                        Happy Steno is a native Windows desktop application. Download, install, and you're ready — no
                        browser, no cloud uploads, your audio stays on your machine.
                    </p>
                    <ul style="list-style:none; display:flex; flex-direction:column; gap:12px;">
                        <li
                            style="display:flex;align-items:center;gap:10px;color:var(--text-secondary);font-size:0.9rem;">
                            <span style="color:var(--success);font-size:1rem;">✓</span> Free to download and install
                        </li>
                        <li
                            style="display:flex;align-items:center;gap:10px;color:var(--text-secondary);font-size:0.9rem;">
                            <span style="color:var(--success);font-size:1rem;">✓</span> Auto-updates built in
                        </li>
                        <li
                            style="display:flex;align-items:center;gap:10px;color:var(--text-secondary);font-size:0.9rem;">
                            <span style="color:var(--success);font-size:1rem;">✓</span> Your audio never leaves your
                            device
                        </li>
                        <li
                            style="display:flex;align-items:center;gap:10px;color:var(--text-secondary);font-size:0.9rem;">
                            <span style="color:var(--success);font-size:1rem;">✓</span> Supports all common audio
                            formats
                        </li>
                    </ul>
                </div>

                <div class="download-box" data-reveal>
                    <div class="download-card">
                        <span class="os-icon">🪟</span>
                        <div class="os-name">Windows</div>
                        <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:8px;">
                            v{{ config('app.version', '1.0.5') }}</div>
                        <div class="version-badge">Latest Stable</div>

                        <a href="/releases/HappyStenoSetup.exe" class="download-btn" id="win-download-btn">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Download Installer (.exe)
                        </a>

                        <a href="/releases/HappyStenoPortable.exe" class="download-btn" id="portable-download-btn"
                            style="background:transparent;border:1px solid rgba(99,102,241,0.35);color:var(--text-secondary);box-shadow:none;font-size:0.9rem;padding:12px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Portable Version (.exe)
                        </a>

                        <p class="download-requirements">Requires Windows 10 / 11 &nbsp;·&nbsp; 64-bit</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer>
        <a href="#" class="footer-brand">
            <img src="/images/logo.jpg" alt="Happy Steno Logo">
            <span class="footer-brand-name">Happy Steno</span>
        </a>

        <ul class="footer-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#how-it-works">How It Works</a></li>
            <li><a href="#download">Download</a></li>
        </ul>

        <p class="footer-copy">&copy; {{ date('Y') }} Happy Steno. All rights reserved.</p>
    </footer>

    <script>
        /* ── Scroll reveal ── */
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    // Stagger delay based on sibling index
                    const siblings = [...entry.target.parentElement.querySelectorAll('[data-reveal]')];
                    const idx = siblings.indexOf(entry.target);
                    entry.target.style.transitionDelay = (idx * 0.08) + 's';
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('[data-reveal]').forEach(el => revealObserver.observe(el));

        /* ── Floating particles ── */
        const container = document.getElementById('particles-container');
        const COLORS = ['rgba(99,102,241,0.6)', 'rgba(139,92,246,0.5)', 'rgba(167,139,250,0.4)'];

        function spawnParticle() {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 4 + 2;
            const duration = Math.random() * 12 + 8;
            const delay = Math.random() * 4;
            p.style.cssText = `
                width:${size}px; height:${size}px;
                left:${Math.random() * 100}vw;
                bottom:-20px;
                background:${COLORS[Math.floor(Math.random() * COLORS.length)]};
                animation-duration:${duration}s;
                animation-delay:${delay}s;
            `;
            container.appendChild(p);
            setTimeout(() => p.remove(), (duration + delay) * 1000);
        }

        for (let i = 0; i < 20; i++) spawnParticle();
        setInterval(spawnParticle, 800);

        /* ── Nav background on scroll ── */
        const nav = document.querySelector('nav');
        window.addEventListener('scroll', () => {
            nav.style.background = window.scrollY > 60
                ? 'rgba(15, 23, 42, 0.95)'
                : 'rgba(15, 23, 42, 0.8)';
        }, { passive: true });

        /* ── Smooth scroll for anchor links ── */
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>

    <!-- ── Live Chat Widget ── -->
    <div id="chatWidget" class="chat-widget">
        <!-- Chat Icon Button -->
        <button id="chatToggleBtn" class="chat-toggle-btn" aria-label="Open Live Chat">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
            </svg>
            <span id="chatBadge" class="chat-badge" style="display: none;">!</span>
        </button>

        <!-- Chat Window -->
        <div id="chatWindow" class="chat-window">
            <div class="chat-window-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="chat-avatar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1rem; color: #fff;">Happy Steno Support</h4>
                        <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7);">Usually replies in a few minutes
                        </div>
                    </div>
                </div>
                <button id="chatCloseBtn" class="chat-close-btn">&times;</button>
            </div>

            <div id="chatBody" class="chat-body">
                <!-- Welcome State -->
                <div id="chatWelcome" class="chat-welcome">
                    <p style="margin-bottom: 15px;">👋 Hi there! Need help? Start a chat with our support team.</p>
                    <input type="text" id="chatNameInput" placeholder="Your Name" class="chat-input-field"
                        style="margin-bottom: 10px;">
                    <input type="email" id="chatEmailInput" placeholder="Your Email (optional)" class="chat-input-field"
                        style="margin-bottom: 15px;">
                    <button id="chatStartBtn" class="chat-start-btn">Start Chat</button>
                </div>

                <!-- Messages Area -->
                <div id="chatMessages" class="chat-messages-area" style="display: none;"></div>
            </div>

            <div id="chatFooter" class="chat-footer" style="display: none;">
                <input type="text" id="chatMsgInput" class="chat-input-field" placeholder="Type your message..."
                    style="flex: 1;">
                <button id="chatSendBtn" class="chat-send-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13" />
                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <style>
        .chat-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            font-family: 'Inter', sans-serif;
        }

        .chat-toggle-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .chat-toggle-btn:hover {
            transform: scale(1.1);
        }

        .chat-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 2px var(--bg-dark);
        }

        .chat-window {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 350px;
            height: 500px;
            max-height: calc(100vh - 120px);
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px) scale(0.95);
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-origin: bottom right;
        }

        .chat-window.open {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .chat-window-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .chat-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .chat-close-btn:hover {
            opacity: 1;
        }

        .chat-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            position: relative;
            background: rgb(15 23 42);
        }

        .chat-welcome {
            text-align: center;
            color: var(--text-muted);
            margin-top: 20px;
        }

        .chat-input-field {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-main);
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .chat-input-field:focus {
            border-color: var(--primary);
        }

        .chat-start-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .chat-start-btn:hover {
            background: var(--primary-hover);
        }

        .chat-messages-area {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .cw-msg {
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 0.9rem;
            line-height: 1.4;
            animation: fadeIn 0.3s;
        }

        .cw-msg-guest {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 2px;
        }

        .cw-msg-admin {
            align-self: flex-start;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            border-bottom-left-radius: 2px;
        }

        .chat-footer {
            padding: 15px;
            border-top: 1px solid var(--border-color);
            background: rgb(20 31 56);
            display: flex;
            gap: 10px;
        }

        .chat-send-btn {
            background: var(--primary);
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .chat-send-btn:hover {
            background: var(--primary-hover);
        }

        @media (max-width: 480px) {
            .chat-widget {
                bottom: 20px;
                right: 20px;
            }

            .chat-window {
                position: fixed;
                bottom: 0;
                right: 0;
                width: 100%;
                height: 100%;
                max-height: 100%;
                border-radius: 0;
                z-index: 1001;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('chatToggleBtn');
            const windowEl = document.getElementById('chatWindow');
            const closeBtn = document.getElementById('chatCloseBtn');
            const startBtn = document.getElementById('chatStartBtn');
            const sendBtn = document.getElementById('chatSendBtn');
            const msgInput = document.getElementById('chatMsgInput');

            const welcomeArea = document.getElementById('chatWelcome');
            const messagesArea = document.getElementById('chatMessages');
            const footerArea = document.getElementById('chatFooter');
            const badge = document.getElementById('chatBadge');

            let sessionId = localStorage.getItem('chat_session_id');
            let lastMsgId = 0;
            let pollInterval = null;
            let isOpen = false;

            toggleBtn.addEventListener('click', () => {
                isOpen = !isOpen;
                if (isOpen) {
                    windowEl.classList.add('open');
                    badge.style.display = 'none';
                    if (sessionId && !pollInterval) startPolling();
                } else {
                    windowEl.classList.remove('open');
                }
            });

            closeBtn.addEventListener('click', () => {
                isOpen = false;
                windowEl.classList.remove('open');
            });

            startBtn.addEventListener('click', async () => {
                const name = document.getElementById('chatNameInput').value;
                const email = document.getElementById('chatEmailInput').value;

                try {
                    const res = await fetch('/api/chat/start', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ name, email })
                    });
                    const data = await res.json();
                    sessionId = data.session_id;
                    localStorage.setItem('chat_session_id', sessionId);

                    showChatInterface();
                    startPolling();
                } catch (e) {
                    console.error('Failed to start chat', e);
                }
            });

            sendBtn.addEventListener('click', sendMessage);
            msgInput.addEventListener('keypress', e => {
                if (e.key === 'Enter') sendMessage();
            });

            async function sendMessage() {
                const text = msgInput.value.trim();
                if (!text || !sessionId) return;

                msgInput.value = '';

                try {
                    const res = await fetch(`/api/chat/${sessionId}/message`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: text })
                    });
                    if (res.ok) {
                        fetchMessages();
                    }
                } catch (e) {
                    console.error('Failed to send msg', e);
                }
            }

            async function fetchMessages() {
                if (!sessionId) return;

                try {
                    const res = await fetch(`/api/chat/${sessionId}/messages?after=${lastMsgId}`);
                    if (res.status === 404) {
                        // Session closed or not found
                        stopPolling();
                        localStorage.removeItem('chat_session_id');
                        sessionId = null;
                        showWelcomeInterface();
                        return;
                    }
                    const data = await res.json();

                    if (data.status === 'closed') {
                        stopPolling();
                        localStorage.removeItem('chat_session_id');
                        sessionId = null;
                        const el = document.createElement('div');
                        el.style.textAlign = 'center';
                        el.style.fontSize = '0.8rem';
                        el.style.color = '#94a3b8';
                        el.style.marginTop = '10px';
                        el.innerText = 'This chat has been closed.';
                        messagesArea.appendChild(el);
                        return;
                    }

                    if (data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            const el = document.createElement('div');
                            el.className = 'cw-msg ' + (msg.sender_type === 'guest' ? 'cw-msg-guest' : 'cw-msg-admin');
                            el.innerText = msg.message;
                            messagesArea.appendChild(el);
                            lastMsgId = msg.id;

                            if (msg.sender_type === 'admin' && !isOpen) {
                                badge.style.display = 'flex';
                            }
                        });

                        const body = document.getElementById('chatBody');
                        body.scrollTop = body.scrollHeight;
                    }
                } catch (e) {
                    console.error('Failed to fetch msgs', e);
                }
            }

            function startPolling() {
                if (pollInterval) clearInterval(pollInterval);
                fetchMessages();
                pollInterval = setInterval(fetchMessages, 3000);
            }
            function stopPolling() {
                if (pollInterval) clearInterval(pollInterval);
                pollInterval = null;
            }

            function showChatInterface() {
                welcomeArea.style.display = 'none';
                messagesArea.style.display = 'flex';
                footerArea.style.display = 'flex';
            }

            function showWelcomeInterface() {
                welcomeArea.style.display = 'block';
                messagesArea.style.display = 'none';
                footerArea.style.display = 'none';
                messagesArea.innerHTML = '';
            }

            // Auto resume session if exists
            if (sessionId) {
                showChatInterface();
                startPolling();
            }
        });
    </script>
</body>

</html>