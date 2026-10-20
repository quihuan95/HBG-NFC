<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $card->name }} | Danh thiếp điện tử</title>

    <!-- Open Graph / SEO -->
    <meta name="description" content="Danh thiếp điện tử thông minh của {{ $card->name }} - {{ $card->job_title ?: 'Chuyên gia' }}{{ $card->company ? ' tại '.$card->company : '' }}">
    <meta property="og:title" content="{{ $card->name }} | Digital Business Card">
    <meta property="og:description" content="{{ $card->job_title ?: 'Digital Business Card' }}{{ $card->company ? ' · '.$card->company : '' }}">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($card->photo_path)
        <meta property="og:image" content="{{ asset('storage/'.$card->photo_path) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-body: #0b0f19;
            --bg-card: rgba(17, 24, 39, 0.85);
            --border-card: rgba(255, 255, 255, 0.1);
            --border-accent: rgba(59, 130, 246, 0.4);
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.35);
            --accent-gold: #f59e0b;
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
            --text-muted: #64748b;
            --card-inner: rgba(30, 41, 59, 0.7);
            --card-inner-border: rgba(255, 255, 255, 0.08);
            --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 24px 14px 48px;
            position: relative;
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* Ambient glowing spheres */
        .ambient-glow {
            position: fixed;
            pointer-events: none;
            z-index: 0;
            border-radius: 50%;
            filter: blur(130px);
            opacity: 0.5;
        }
        .glow-1 {
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.45) 0%, transparent 70%);
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
        }
        .glow-2 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.35) 0%, transparent 70%);
            top: 280px;
            right: -60px;
        }
        .glow-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);
            bottom: 40px;
            left: -60px;
        }

        /* Card Container */
        .card-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-card);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            animation: cardEntrance 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntrance {
            0% { opacity: 0; transform: translateY(24px) scale(0.97); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Top Hero Header */
        .card-header-bar {
            position: relative;
            height: 125px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .card-header-bar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.25) 0%, transparent 60%),
                        linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100% 100%, 20px 20px, 20px 20px;
            pointer-events: none;
        }

        .brand-badge {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #93c5fd;
            text-transform: uppercase;
        }

        .nfc-status-pill {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 5px 12px;
            border-radius: 999px;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 8px #10b981;
            animation: pulseDot 2s infinite ease-in-out;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* Avatar Section */
        .avatar-container {
            position: relative;
            margin-top: -55px;
            padding: 0 24px 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            z-index: 3;
        }

        .avatar-frame {
            position: relative;
            width: 112px;
            height: 112px;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
            box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.5),
                        0 0 25px rgba(59, 130, 246, 0.35);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0f172a;
            display: block;
            background-color: #1e293b;
        }

        .avatar-initial {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: #ffffff;
            font-size: 40px;
            font-weight: 800;
        }

        .verified-badge {
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #0f172a;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.5);
        }

        /* Profile Meta */
        .profile-name {
            font-size: 24px;
            font-weight: 800;
            margin-top: 14px;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .profile-job {
            font-size: 14px;
            font-weight: 600;
            margin-top: 4px;
            color: #60a5fa;
            letter-spacing: 0.2px;
        }

        .profile-company-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            padding: 4px 13px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            color: #cbd5e1;
        }

        /* Quick Social Icons Strip */
        .quick-icon-strip {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .quick-icon-btn {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            padding: 10px;
            color: #ffffff;
        }

        .quick-icon-btn img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            transition: transform 0.22s ease;
        }

        .quick-icon-btn svg {
            width: 24px;
            height: 24px;
            display: block;
        }

        .quick-icon-btn:hover {
            transform: translateY(-3px) scale(1.08);
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.15);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25);
        }

        .quick-icon-btn:active {
            transform: translateY(0) scale(0.96);
        }

        /* Hero Action Buttons */
        .hero-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 4px 20px 22px;
        }

        .btn-hero {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 14px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid transparent;
        }

        .btn-hero.primary {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
            color: #ffffff;
            box-shadow: 0 8px 20px -5px rgba(37, 99, 235, 0.5),
                        inset 0 1px 0 rgba(255, 255, 255, 0.25);
            border-color: rgba(59, 130, 246, 0.5);
            position: relative;
            overflow: hidden;
        }

        .btn-hero.primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: skewX(-20deg);
            transition: 0.6s;
        }

        .btn-hero.primary:hover::after {
            left: 140%;
        }

        .btn-hero.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -5px rgba(37, 99, 235, 0.7);
        }

        .btn-hero.secondary {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-hero.secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

        .btn-hero:active {
            transform: translateY(0);
        }

        /* Section Container */
        .content-section {
            padding: 0 20px 20px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0 4px 12px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .section-title::before {
            content: '';
            width: 3px;
            height: 12px;
            background: #3b82f6;
            border-radius: 2px;
            display: inline-block;
        }

        /* Direct Channels List */
        .channels-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .channel-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background: var(--card-inner);
            border: 1px solid var(--card-inner-border);
            border-radius: 18px;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.22s ease;
            position: relative;
        }

        .channel-row:hover {
            background: rgba(45, 55, 75, 0.85);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateX(3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }

        .channel-row:active {
            transform: scale(0.99);
        }

        .channel-main {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
            flex: 1;
            text-decoration: none;
            color: inherit;
        }

        .channel-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            padding: 8px;
            transition: transform 0.2s ease;
            color: #ffffff;
        }

        .channel-icon-wrap img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
        }

        .channel-icon-wrap svg {
            width: 22px;
            height: 22px;
            display: block;
        }

        .channel-row:hover .channel-icon-wrap {
            transform: scale(1.06);
        }

        .channel-details {
            display: flex;
            flex-direction: column;
            min-width: 0;
            flex: 1;
        }

        .channel-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .channel-value {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        .channel-copy-btn {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.07);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            margin-left: 8px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .channel-copy-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.25);
        }

        /* Bio Box */
        .bio-box {
            padding: 16px 18px;
            background: var(--card-inner);
            border: 1px solid var(--card-inner-border);
            border-left: 3px solid #3b82f6;
            border-radius: 16px;
            color: #e2e8f0;
            font-size: 13.5px;
            line-height: 1.65;
            white-space: pre-line;
        }

        /* Footer */
        .card-footer {
            padding: 22px 20px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(15, 23, 42, 0.6);
        }

        .footer-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* QR & Share Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.25s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-body {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            max-width: 360px;
            width: 100%;
            padding: 26px 22px;
            text-align: center;
            position: relative;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6);
            animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes scaleUp {
            from { transform: scale(0.92); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }

        .qr-card-container {
            background: #ffffff;
            border-radius: 18px;
            padding: 14px;
            display: inline-block;
            margin: 14px 0 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .qr-card-container img {
            display: block;
            width: 200px;
            height: 200px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 750;
            color: #ffffff;
        }

        .modal-subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 8px;
        }

        .btn-modal {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 650;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-modal.primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
        }

        .btn-modal.secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .btn-modal.secondary:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        /* Toast Feedback */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #1e293b;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 12px 22px;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.4);
            z-index: 200;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .toast-icon {
            color: #34d399;
        }

        svg {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Ambient glowing spheres -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>

    <div class="card-wrapper">
        <!-- Card Top Bar -->
        <div class="card-header-bar">
            <div class="brand-badge">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                <span>PERSONAL CARD</span>
            </div>
            <div class="nfc-status-pill">
                <span class="status-dot"></span>
                <span>NFC ACTIVE</span>
            </div>
        </div>

        <!-- Avatar & Identity -->
        <div class="avatar-container">
            <div class="avatar-frame">
                @if($card->photo_path)
                    <img class="avatar-img" src="{{ asset('storage/'.$card->photo_path) }}" alt="{{ $card->name }}">
                @else
                    <div class="avatar-initial">{{ mb_strtoupper(mb_substr($card->name, 0, 1)) }}</div>
                @endif
                <div class="verified-badge" title="Thẻ NFC chính chủ">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
            </div>

            <h1 class="profile-name">{{ $card->name }}</h1>
            <div class="profile-job">{{ $card->job_title ?: 'Professional' }}</div>
            @if($card->company)
                <div class="profile-company-pill">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <span>{{ $card->company }}</span>
                </div>
            @endif

            <!-- Quick Icon Strip -->
            <div class="quick-icon-strip">
                @if($card->phone)
                    <a href="tel:{{ preg_replace('/[^+0-9]/', '', $card->phone) }}" class="quick-icon-btn" title="Gọi điện">
                        <img src="{{ asset('storage/icon/phone.png') }}" alt="Điện thoại">
                    </a>
                @endif

                @if($card->zalo)
                    <a href="{{ $card->zalo_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Zalo">
                        <img src="{{ asset('storage/icon/zalo.webp') }}" alt="Zalo">
                    </a>
                @endif

                @if($card->telegram)
                    <a href="{{ $card->telegram_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Telegram">
                        <svg viewBox="0 0 24 24" fill="#29b6f6"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                    </a>
                @endif

                @if($card->facebook)
                    <a href="{{ $card->facebook_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Facebook">
                        <img src="{{ asset('storage/icon/facebook.webp') }}" alt="Facebook">
                    </a>
                @endif

                @if($card->github)
                    <a href="{{ $card->github_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="GitHub">
                        <svg viewBox="0 0 24 24" fill="#f0f6fc"><path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/></svg>
                    </a>
                @endif

                @if($card->website)
                    <a href="{{ $card->website }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Website">
                        <img src="{{ asset('storage/icon/web.png') }}" alt="Website">
                    </a>
                @endif

                @if($card->whatsapp)
                    <a href="{{ $card->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="WhatsApp">
                        <img src="{{ asset('storage/icon/whatsapp.png') }}" alt="WhatsApp">
                    </a>
                @endif

                @if($card->viber)
                    <a href="{{ $card->viber_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Viber">
                        <img src="{{ asset('storage/icon/viber.png') }}" alt="Viber">
                    </a>
                @endif

                @if($card->linkedin)
                    <a href="{{ $card->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="LinkedIn">
                        <img src="{{ asset('storage/icon/linkedIn.webp') }}" alt="LinkedIn">
                    </a>
                @endif
            </div>
        </div>

        <!-- Primary Action Buttons: Save Contact & Share -->
        <div class="hero-actions">
            <a href="{{ route('minh.card.show', $card->code) }}?download=vcard" id="download-vcard" class="btn-hero primary">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Lưu danh bạ</span>
            </a>

            <button type="button" class="btn-hero secondary" onclick="shareCard()">
                <img src="{{ asset('storage/icon/share.png') }}" alt="Chia sẻ" style="width:19px;height:19px;object-fit:contain">
                <span>Chia sẻ thẻ</span>
            </button>
        </div>

        <!-- Direct Contact Channels -->
        <div class="content-section">
            <div class="section-header">
                <span class="section-title">Kênh liên hệ trực tiếp</span>
            </div>

            <div class="channels-list">
                @if($card->phone)
                    <div class="channel-row">
                        <a href="tel:{{ preg_replace('/[^+0-9]/', '', $card->phone) }}" class="channel-main">
                            <div class="channel-icon-wrap">
                                <img src="{{ asset('storage/icon/phone.png') }}" alt="Phone">
                            </div>
                            <div class="channel-details">
                                <span class="channel-label">Số điện thoại</span>
                                <span class="channel-value">{{ $card->phone }}</span>
                            </div>
                        </a>
                        <button class="channel-copy-btn" title="Sao chép số điện thoại" onclick="copyToClipboard('{{ $card->phone }}', 'Đã chép số điện thoại!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($card->email)
                    <div class="channel-row">
                        <a href="mailto:{{ $card->email }}" class="channel-main">
                            <div class="channel-icon-wrap">
                                <img src="{{ asset('storage/icon/email.png') }}" alt="Email">
                            </div>
                            <div class="channel-details">
                                <span class="channel-label">Địa chỉ Email</span>
                                <span class="channel-value">{{ $card->email }}</span>
                            </div>
                        </a>
                        <button class="channel-copy-btn" title="Sao chép email" onclick="copyToClipboard('{{ $card->email }}', 'Đã chép email!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($card->telegram)
                    <div class="channel-row">
                        <a href="{{ $card->telegram_url }}" target="_blank" rel="noopener noreferrer" class="channel-main">
                            <div class="channel-icon-wrap">
                                <svg viewBox="0 0 24 24" fill="#29b6f6"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                            </div>
                            <div class="channel-details">
                                <span class="channel-label">Telegram</span>
                                <span class="channel-value">{{ $card->telegram }}</span>
                            </div>
                        </a>
                        <button class="channel-copy-btn" title="Sao chép Telegram link" onclick="copyToClipboard('{{ $card->telegram_url }}', 'Đã chép link Telegram!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($card->website)
                    <div class="channel-row">
                        <a href="{{ $card->website }}" target="_blank" rel="noopener noreferrer" class="channel-main">
                            <div class="channel-icon-wrap">
                                <img src="{{ asset('storage/icon/web.png') }}" alt="Website">
                            </div>
                            <div class="channel-details">
                                <span class="channel-label">Website / Portfolio</span>
                                <span class="channel-value">{{ $card->website }}</span>
                            </div>
                        </a>
                        <button class="channel-copy-btn" title="Sao chép Website" onclick="copyToClipboard('{{ $card->website }}', 'Đã chép link Website!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bio / Introduction -->
        @if($card->bio)
        <div class="content-section">
            <div class="section-header">
                <span class="section-title">Giới thiệu</span>
            </div>
            <div class="bio-box">
                {{ $card->bio }}
            </div>
        </div>
        @endif

        <!-- Card Footer -->
        <div class="card-footer">
            <div class="footer-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>NFC Smart Card · Private Edition</span>
            </div>
        </div>
    </div>

    <!-- QR Code & Share Modal -->
    <div id="qrModal" class="modal-overlay" onclick="closeModalOnBackdrop(event)">
        <div class="modal-body">
            <button class="modal-close" onclick="closeQrModal()" aria-label="Đóng">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <h3 class="modal-title">Mã QR Danh Thiếp</h3>
            <p class="modal-subtitle">Quét để mở thẻ danh thiếp của {{ $card->name }}</p>

            <div class="qr-card-container">
                <img id="qrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=0&data={{ urlencode(route('minh.card.show', $card->code)) }}" alt="QR Code">
            </div>

            <div class="modal-actions">
                <button class="btn-modal primary" onclick="shareCard()">
                    <img src="{{ asset('storage/icon/share.png') }}" alt="Chia sẻ" style="width:18px;height:18px;object-fit:contain">
                    <span>Chia sẻ danh thiếp</span>
                </button>
                <button class="btn-modal secondary" onclick="copyToClipboard('{{ route('minh.card.show', $card->code) }}', 'Đã chép link thẻ /minh/{{ $card->code }}!')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    <span>Sao chép liên kết</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <svg class="toast-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <span id="toastMsg">Đã sao chép!</span>
    </div>

    <script>
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            toastMsg.innerText = message || 'Đã sao chép vào bộ nhớ tạm!';
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }

        function copyToClipboard(text, customMessage) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast(customMessage);
                }).catch(() => {
                    fallbackCopy(text, customMessage);
                });
            } else {
                fallbackCopy(text, customMessage);
            }
        }

        function fallbackCopy(text, customMessage) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showToast(customMessage);
            } catch (err) {
                showToast('Không thể tự động sao chép!');
            }
            document.body.removeChild(textArea);
        }

        function openQrModal() {
            document.getElementById('qrModal').classList.add('active');
        }

        function closeQrModal() {
            document.getElementById('qrModal').classList.remove('active');
        }

        function closeModalOnBackdrop(e) {
            if (e.target.id === 'qrModal') {
                closeQrModal();
            }
        }

        function shareCard() {
            const shareData = {
                title: '{{ $card->name }} | Digital Business Card',
                text: 'Danh thiếp điện tử của {{ $card->name }} ({{ $card->job_title ?: 'Digital Business Card' }})',
                url: '{{ route('minh.card.show', $card->code) }}'
            };

            if (navigator.share) {
                navigator.share(shareData).catch((err) => {
                    if (err.name !== 'AbortError') {
                        openQrModal();
                    }
                });
            } else {
                openQrModal();
            }
        }
    </script>
    @include('partials.web-push')
</body>
</html>
