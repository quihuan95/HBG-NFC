<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#070b14">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $employee->name }} | THE HBG NFC Card</title>

    <!-- Open Graph / SEO -->
    <meta name="description" content="Danh thiếp điện tử của {{ $employee->name }} - {{ $employee->job_title ?: 'Nhân sự' }} tại THE HBG">
    <meta property="og:title" content="{{ $employee->name }} - THE HBG">
    <meta property="og:description" content="{{ $employee->job_title ?: 'THE HBG' }} {{ $employee->department ? '· '.$employee->department : '' }}">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($employee->photo_path)
    <meta property="og:image" content="{{ asset('storage/'.$employee->photo_path) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #070b14;
            --bg-card: rgba(15, 23, 42, 0.82);
            --bg-card-solid: #0d1527;
            --bg-glass: rgba(255, 255, 255, 0.04);
            --bg-glass-hover: rgba(255, 255, 255, 0.08);
            --border-glass: rgba(255, 255, 255, 0.09);
            --border-gold: rgba(229, 184, 105, 0.35);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --gold-1: #fbe2a7;
            --gold-2: #e5b869;
            --gold-3: #c38c32;
            --brand-fb: #1877F2;
            --brand-wa: #25D366;
            --brand-li: #0A66C2;
            --brand-zalo: #0068FF;
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
            background-color: var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 20px 14px 44px;
            position: relative;
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* Ambient Lighting Backdrop */
        .ambient-glow {
            position: fixed;
            pointer-events: none;
            z-index: 0;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.55;
        }
        .glow-1 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(30, 58, 138, 0.6) 0%, rgba(30, 58, 138, 0) 70%);
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
        }
        .glow-2 {
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(229, 184, 105, 0.22) 0%, rgba(229, 184, 105, 0) 70%);
            top: 250px;
            right: -80px;
        }
        .glow-3 {
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, rgba(56, 189, 248, 0) 70%);
            bottom: 40px;
            left: -80px;
        }

        /* Main Card Container */
        .card-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-glass);
            border-radius: 28px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.75),
                        0 0 0 1px rgba(255, 255, 255, 0.05),
                        inset 0 1px 0 rgba(255, 255, 255, 0.12);
            overflow: hidden;
            animation: cardEntrance 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntrance {
            0% {
                opacity: 0;
                transform: translateY(24px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Hero Banner */
        .card-banner {
            position: relative;
            height: 140px;
            background: linear-gradient(135deg, #0b1736 0%, #172d63 50%, #0a1329 100%);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(229, 184, 105, 0.18);
            overflow: hidden;
        }

        .card-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 85% 15%, rgba(229, 184, 105, 0.25) 0%, transparent 45%),
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 100% 100%, 20px 20px, 20px 20px;
            pointer-events: none;
        }

        .brand-badge {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 5px 12px;
            border-radius: 12px;
            border: 1px solid rgba(229, 184, 105, 0.45);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
        }

        .brand-logo-img {
            height: 24px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .nfc-status-pill {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 5px 12px;
            border-radius: 999px;
            border: 1px solid rgba(52, 211, 153, 0.25);
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
            margin-top: -60px;
            padding: 0 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            z-index: 3;
        }

        .avatar-frame {
            position: relative;
            width: 116px;
            height: 116px;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(145deg, var(--gold-1) 0%, var(--gold-2) 40%, #0d1527 100%);
            box-shadow: 0 12px 28px -6px rgba(0, 0, 0, 0.6),
                        0 0 25px rgba(229, 184, 105, 0.25);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0b1324;
            display: block;
            background-color: #0d1527;
        }

        .avatar-initial {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid #0b1324;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 30% 30%, #1e3a8a, #0b1736);
            color: var(--gold-1);
            font-size: 42px;
            font-weight: 800;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }

        .verified-badge {
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #0d1527;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.5);
        }

        /* Profile Meta */
        .profile-name {
            font-size: 23px;
            font-weight: 800;
            margin-top: 14px;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .profile-job {
            font-size: 14px;
            font-weight: 600;
            margin-top: 4px;
            background: linear-gradient(90deg, #fcebc2, var(--gold-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.2px;
        }

        .profile-dept-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            padding: 4px 13px;
            background: rgba(229, 184, 105, 0.08);
            border: 1px solid rgba(229, 184, 105, 0.25);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            color: var(--gold-1);
        }

        /* Social Quick Connect Strip (Direct Brand Logos) */
        .social-quick-strip {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .social-logo-btn {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .social-logo-btn img {
            width: 30px;
            height: 30px;
            object-fit: contain;
            display: block;
            transition: transform 0.25s ease;
        }

        .social-logo-btn:hover {
            transform: translateY(-3px) scale(1.06);
            border-color: rgba(229, 184, 105, 0.4);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .social-logo-btn:active {
            transform: translateY(0) scale(0.98);
        }

        /* Main CTA: Save to Contacts */
        .cta-section {
            padding: 18px 20px 10px;
        }

        .btn-vcard {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 15px 22px;
            border-radius: 16px;
            background: linear-gradient(135deg, #f7d58a 0%, #e5b869 45%, #c48e35 100%);
            color: #101828;
            font-size: 15px;
            font-weight: 750;
            letter-spacing: 0.3px;
            text-decoration: none;
            box-shadow: 0 10px 24px -6px rgba(229, 184, 105, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.4);
            border: none;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-vcard::after {
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

        .btn-vcard:hover::after {
            left: 140%;
        }

        .btn-vcard:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -5px rgba(229, 184, 105, 0.55);
            color: #0b121e;
        }

        .btn-vcard:active {
            transform: translateY(0);
            box-shadow: 0 6px 14px -3px rgba(229, 184, 105, 0.4);
        }

        /* Quick Action Bar */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 10px 20px 20px;
        }

        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 12px 6px;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            backdrop-filter: blur(12px);
        }

        .quick-btn .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .quick-btn:hover {
            background: var(--bg-glass-hover);
            border-color: rgba(229, 184, 105, 0.3);
            transform: translateY(-2px);
            color: #ffffff;
        }

        .quick-btn:active {
            transform: translateY(0);
        }

        .quick-btn.call .icon-box { background: rgba(16, 185, 129, 0.16); color: #34d399; }
        .quick-btn.mail .icon-box { background: rgba(99, 102, 241, 0.16); color: #818cf8; }
        .quick-btn.zalo .icon-box { background: rgba(0, 104, 255, 0.16); color: #38bdf8; }
        .quick-btn.share .icon-box { background: rgba(229, 184, 105, 0.16); color: var(--gold-2); }

        /* Section Cards List */
        .content-section {
            padding: 0 20px 18px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 16px 4px 10px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .section-title::before {
            content: '';
            width: 3px;
            height: 12px;
            background: var(--gold-2);
            border-radius: 2px;
            display: inline-block;
        }

        /* List Items with Official Full-Color Logos */
        .list-card {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .link-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 18px;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.22s ease;
            position: relative;
            overflow: hidden;
        }

        .link-row:hover {
            background: var(--bg-glass-hover);
            border-color: rgba(229, 184, 105, 0.35);
            transform: translateX(3px);
            color: #ffffff;
        }

        .link-row:active {
            transform: scale(0.99);
        }

        .link-row-left {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
            flex: 1;
        }

        .brand-logo-wrap {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease;
            background: rgba(255, 255, 255, 0.04);
            padding: 4px;
        }

        .brand-logo-wrap img,
        .brand-logo-img-icon {
            width: 36px;
            height: 36px;
            display: block;
            object-fit: contain;
        }

        .link-row:hover .brand-logo-wrap {
            transform: scale(1.05);
        }

        .link-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
            flex: 1;
        }

        .link-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .link-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        .link-action-tag {
            font-size: 11px;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            color: var(--gold-1);
            border: 1px solid rgba(229, 184, 105, 0.25);
            display: flex;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;
            margin-left: 8px;
            transition: all 0.2s ease;
        }

        .link-row:hover .link-action-tag {
            background: rgba(229, 184, 105, 0.18);
            border-color: var(--gold-2);
            color: #ffffff;
        }

        .link-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
            border: none;
            cursor: pointer;
            margin-left: 8px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .link-action-btn:hover {
            background: rgba(229, 184, 105, 0.2);
            color: var(--gold-1);
        }

        /* Bio Box */
        .bio-box {
            padding: 16px;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-left: 3px solid var(--gold-2);
            border-radius: 14px;
            color: #cbd5e1;
            font-size: 13.5px;
            line-height: 1.65;
            white-space: pre-line;
        }

        /* Footer */
        .card-footer {
            padding: 22px 20px;
            text-align: center;
            border-top: 1px solid var(--border-glass);
            background: rgba(7, 11, 20, 0.5);
        }

        .footer-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 6px 16px;
            border-radius: 12px;
            margin-bottom: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(229, 184, 105, 0.35);
        }

        .footer-logo-img {
            height: 26px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .footer-desc {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* QR & Share Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
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
            background: #0d1527;
            border: 1px solid rgba(229, 184, 105, 0.3);
            border-radius: 24px;
            max-width: 360px;
            width: 100%;
            padding: 26px 22px;
            text-align: center;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8),
                        0 0 30px rgba(229, 184, 105, 0.2);
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
            color: var(--text-secondary);
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
            border-radius: 16px;
            padding: 16px;
            display: inline-block;
            margin: 14px 0 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
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
            color: var(--text-secondary);
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
        }

        .btn-modal.primary {
            background: linear-gradient(135deg, var(--gold-1), var(--gold-2));
            color: #0b121e;
        }

        .btn-modal.secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
            border: 1px solid var(--border-glass);
        }

        .btn-modal:hover {
            opacity: 0.92;
        }

        /* Toast Feedback */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #1e293b;
            color: #f8fafc;
            padding: 12px 22px;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
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
            color: #10b981;
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
        <!-- Card Top Banner -->
        <div class="card-banner">
            <div class="brand-badge">
                <img src="{{ asset('images/logo.png') }}" alt="THE HBG" class="brand-logo-img">
            </div>
            <div class="nfc-status-pill">
                <span class="status-dot"></span>
                <span>NFC CARD</span>
            </div>
        </div>

        <!-- Avatar & Identity -->
        <div class="avatar-container">
            <div class="avatar-frame">
                @if($employee->photo_path)
                    <img class="avatar-img" src="{{ asset('storage/'.$employee->photo_path) }}" alt="{{ $employee->name }}">
                @else
                    <div class="avatar-initial">{{ mb_strtoupper(mb_substr($employee->name, 0, 1)) }}</div>
                @endif
                <div class="verified-badge" title="Đã xác thực bởi THE HBG">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
            </div>

            <h1 class="profile-name">{{ $employee->name }}</h1>
            <div class="profile-job">{{ $employee->job_title ?: 'Nhân sự' }}</div>
            @if($employee->department)
                <div class="profile-dept-pill">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <span>{{ $employee->department }}</span>
                </div>
            @endif

            <!-- Quick Logo Connect Bar -->
            @if($employee->zalo || $employee->facebook || $employee->whatsapp || $employee->linkedin || $employee->website)
                <div class="social-quick-strip">
                    @if($employee->zalo)
                        <a href="{{ $employee->zalo_url }}" target="_blank" rel="noopener noreferrer" class="social-logo-btn" title="Chat Zalo">
                            <img src="{{ asset('storage/icon/zalo.webp') }}" alt="Zalo">
                        </a>
                    @endif

                    @if($employee->facebook)
                        <a href="{{ $employee->facebook_url }}" target="_blank" rel="noopener noreferrer" class="social-logo-btn" title="Facebook Profile">
                            <img src="{{ asset('storage/icon/facebook.webp') }}" alt="Facebook">
                        </a>
                    @endif

                    @if($employee->whatsapp)
                        <a href="{{ $employee->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="social-logo-btn" title="Chat WhatsApp">
                            <img src="{{ asset('storage/icon/whatsapp.png') }}" alt="WhatsApp">
                        </a>
                    @endif

                    @if($employee->linkedin)
                        <a href="{{ $employee->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="social-logo-btn" title="LinkedIn Profile">
                            <img src="{{ asset('storage/icon/linkedIn.webp') }}" alt="LinkedIn">
                        </a>
                    @endif

                    @if($employee->website)
                        <a href="{{ $employee->website }}" target="_blank" rel="noopener noreferrer" class="social-logo-btn" title="Website">
                            <img src="{{ asset('storage/icon/web.png') }}" alt="Website" style="filter:brightness(0) invert(1)">
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Primary CTA: Save Contact -->
        <div class="cta-section">
            <a href="{{ route('card.show', $employee->code) }}?download=vcard" id="download-card" class="btn-vcard">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Lưu liên hệ vào danh bạ</span>
            </a>
        </div>

        <!-- Quick Action Grid -->
        <div class="quick-actions">
            @if($employee->phone)
                <a href="tel:{{ preg_replace('/[^+0-9]/', '', $employee->phone) }}" class="quick-btn call">
                    <div class="icon-box">
                        <img src="{{ asset('storage/icon/phone.png') }}" alt="Gọi điện" style="width:22px;height:22px;object-fit:contain">
                    </div>
                    <span>Gọi điện</span>
                </a>
            @else
                <button class="quick-btn call" onclick="copyToClipboard('{{ route('card.show', $employee->code) }}', 'Đã chép link thẻ!')">
                    <div class="icon-box">
                        <img src="{{ asset('storage/icon/phone.png') }}" alt="Gọi điện" style="width:22px;height:22px;object-fit:contain">
                    </div>
                    <span>Liên hệ</span>
                </button>
            @endif

            @if($employee->email)
                <a href="mailto:{{ $employee->email }}" class="quick-btn mail">
                    <div class="icon-box">
                        <img src="{{ asset('storage/icon/email.png') }}" alt="Email" style="width:22px;height:22px;object-fit:contain">
                    </div>
                    <span>Email</span>
                </a>
            @else
                <button class="quick-btn mail" onclick="copyToClipboard('{{ route('card.show', $employee->code) }}', 'Đã chép link thẻ!')">
                    <div class="icon-box">
                        <img src="{{ asset('storage/icon/email.png') }}" alt="Email" style="width:22px;height:22px;object-fit:contain">
                    </div>
                    <span>Email</span>
                </button>
            @endif

            @if($employee->zalo)
                <a href="{{ $employee->zalo_url }}" target="_blank" rel="noopener noreferrer" class="quick-btn zalo">
                    <div class="icon-box">
                        <img src="{{ asset('storage/icon/zalo.webp') }}" alt="Zalo" style="width:24px;height:24px;object-fit:contain">
                    </div>
                    <span>Zalo</span>
                </a>
            @elseif($employee->whatsapp)
                <a href="{{ $employee->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="quick-btn zalo">
                    <div class="icon-box">
                        <img src="{{ asset('storage/icon/whatsapp.png') }}" alt="WhatsApp" style="width:24px;height:24px;object-fit:contain">
                    </div>
                    <span>WhatsApp</span>
                </a>
            @else
                <button class="quick-btn zalo" onclick="openQrModal()">
                    <div class="icon-box">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <span>Mã QR</span>
                </button>
            @endif

            <button class="quick-btn share" onclick="shareCard()">
                <div class="icon-box">
                    <img src="{{ asset('storage/icon/share.png') }}" alt="Chia sẻ" style="width:22px;height:22px;object-fit:contain">
                </div>
                <span>Chia sẻ</span>
            </button>
        </div>

        <!-- Section: Contact Details -->
        <div class="content-section">
            <div class="section-header">
                <span class="section-title">Thông tin liên hệ</span>
            </div>

            <div class="list-card">
                @if($employee->phone)
                    <div class="link-row">
                        <a href="tel:{{ preg_replace('/[^+0-9]/', '', $employee->phone) }}" class="link-row-left">
                            <div class="brand-logo-wrap">
                                <img src="{{ asset('storage/icon/phone.png') }}" alt="Điện thoại" class="brand-logo-img-icon">
                            </div>
                            <div class="link-info">
                                <span class="link-label">Điện thoại</span>
                                <span class="link-value">{{ $employee->phone }}</span>
                            </div>
                        </a>
                        <button class="link-action-btn" title="Sao chép số" onclick="copyToClipboard('{{ $employee->phone }}', 'Đã chép số điện thoại!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($employee->email)
                    <div class="link-row">
                        <a href="mailto:{{ $employee->email }}" class="link-row-left">
                            <div class="brand-logo-wrap">
                                <img src="{{ asset('storage/icon/email.png') }}" alt="Email" class="brand-logo-img-icon">
                            </div>
                            <div class="link-info">
                                <span class="link-label">Email công việc</span>
                                <span class="link-value">{{ $employee->email }}</span>
                            </div>
                        </a>
                        <button class="link-action-btn" title="Sao chép email" onclick="copyToClipboard('{{ $employee->email }}', 'Đã chép email!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($employee->website)
                    <div class="link-row">
                        <a href="{{ $employee->website }}" target="_blank" rel="noopener noreferrer" class="link-row-left">
                            <div class="brand-logo-wrap">
                                <img src="{{ asset('storage/icon/web.png') }}" alt="Website" class="brand-logo-img-icon" style="filter: brightness(0) invert(1)">
                            </div>
                            <div class="link-info">
                                <span class="link-label">Website</span>
                                <span class="link-value">{{ $employee->website }}</span>
                            </div>
                        </a>
                        <div class="link-action-tag">
                            <span>Mở web</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section: Social Media & Messaging Links (Official Logos) -->
        @if($employee->zalo || $employee->facebook || $employee->whatsapp || $employee->linkedin)
        <div class="content-section">
            <div class="section-header">
                <span class="section-title">Mạng xã hội & Kênh liên kết</span>
            </div>

            <div class="list-card">
                <!-- Zalo Official Logo Card -->
                @if($employee->zalo)
                    <a href="{{ $employee->zalo_url }}" target="_blank" rel="noopener noreferrer" class="link-row">
                        <div class="link-row-left">
                            <div class="brand-logo-wrap">
                                <img src="{{ asset('storage/icon/zalo.webp') }}" alt="Zalo" class="brand-logo-img-icon">
                            </div>
                            <div class="link-info">
                                <span class="link-label">Zalo</span>
                                <span class="link-value">{{ $employee->zalo }}</span>
                            </div>
                        </div>
                        <div class="link-action-tag">
                            <span>Nhắn tin</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endif

                <!-- Facebook Official Logo Card -->
                @if($employee->facebook)
                    <a href="{{ $employee->facebook_url }}" target="_blank" rel="noopener noreferrer" class="link-row">
                        <div class="link-row-left">
                            <div class="brand-logo-wrap">
                                <img src="{{ asset('storage/icon/facebook.webp') }}" alt="Facebook" class="brand-logo-img-icon">
                            </div>
                            <div class="link-info">
                                <span class="link-label">Facebook</span>
                                <span class="link-value">{{ $employee->facebook }}</span>
                            </div>
                        </div>
                        <div class="link-action-tag">
                            <span>Kết nối</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endif

                <!-- WhatsApp Official Logo Card -->
                @if($employee->whatsapp)
                    <a href="{{ $employee->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="link-row">
                        <div class="link-row-left">
                            <div class="brand-logo-wrap">
                                <img src="{{ asset('storage/icon/whatsapp.png') }}" alt="WhatsApp" class="brand-logo-img-icon">
                            </div>
                            <div class="link-info">
                                <span class="link-label">WhatsApp</span>
                                <span class="link-value">{{ $employee->whatsapp }}</span>
                            </div>
                        </div>
                        <div class="link-action-tag">
                            <span>Chat ngay</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endif

                <!-- LinkedIn Official Logo Card -->
                @if($employee->linkedin)
                    <a href="{{ $employee->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="link-row">
                        <div class="link-row-left">
                            <div class="brand-logo-wrap">
                                <img src="{{ asset('storage/icon/linkedIn.webp') }}" alt="LinkedIn" class="brand-logo-img-icon">
                            </div>
                            <div class="link-info">
                                <span class="link-label">LinkedIn</span>
                                <span class="link-value">{{ $employee->linkedin }}</span>
                            </div>
                        </div>
                        <div class="link-action-tag">
                            <span>Xem hồ sơ</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Section: Bio / Introduction -->
        @if($employee->bio)
        <div class="content-section">
            <div class="section-header">
                <span class="section-title">Giới thiệu</span>
            </div>
            <div class="bio-box">
                {{ $employee->bio }}
            </div>
        </div>
        @endif

        <!-- Card Footer -->
        <div class="card-footer">
            <div class="footer-logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="THE HBG" class="footer-logo-img">
            </div>
            <div class="footer-desc">NFC Smart Digital Business Card</div>
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
            <p class="modal-subtitle">Quét để mở thẻ NFC của {{ $employee->name }}</p>

            <div class="qr-card-container">
                <img id="qrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=0&data={{ urlencode(route('card.show', $employee->code)) }}" alt="QR Code">
            </div>

            <div class="modal-actions">
                <button class="btn-modal primary" onclick="shareCard()">
                    <img src="{{ asset('storage/icon/share.png') }}" alt="Chia sẻ" style="width:18px;height:18px;object-fit:contain">
                    <span>Chia sẻ danh thiếp</span>
                </button>
                <button class="btn-modal secondary" onclick="copyToClipboard('{{ route('card.show', $employee->code) }}', 'Đã chép link thẻ!')">
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
                title: '{{ $employee->name }} | THE HBG',
                text: 'Danh thiếp điện tử của {{ $employee->name }} ({{ $employee->job_title ?: 'THE HBG' }})',
                url: '{{ route('card.show', $employee->code) }}'
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
</body>
</html>
