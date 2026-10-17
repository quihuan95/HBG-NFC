<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>{{ $employee->name }} | HoaBinh Group NFC Card</title>

    <!-- Open Graph / SEO -->
    <meta name="description" content="Danh thiếp điện tử của {{ $employee->name }} - {{ $employee->job_title ?: 'Nhân sự' }} tại HoaBinh Group">
    <meta property="og:title" content="{{ $employee->name }} - HoaBinh Group">
    <meta property="og:description" content="{{ $employee->job_title ?: 'HoaBinh Group' }} {{ $employee->department ? '· '.$employee->department : '' }}">
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
            --bg-main: #f0f4f9;
            --bg-card: #ffffff;
            --border-card: #e2e8f0;
            --border-gold: rgba(217, 166, 91, 0.45);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --gold-1: #b45309;
            --gold-2: #c38c32;
            --gold-light: #fcebc2;
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
            filter: blur(120px);
            opacity: 0.65;
        }
        .glow-1 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(191, 219, 254, 0.75) 0%, rgba(219, 234, 254, 0) 70%);
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
        }
        .glow-2 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(254, 243, 199, 0.8) 0%, rgba(254, 243, 199, 0) 70%);
            top: 250px;
            right: -80px;
        }
        .glow-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(224, 231, 255, 0.75) 0%, rgba(224, 231, 255, 0) 70%);
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
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 28px;
            box-shadow: 0 20px 45px -12px rgba(15, 23, 42, 0.12),
                        0 4px 14px -2px rgba(15, 23, 42, 0.05),
                        0 0 0 1px rgba(0, 0, 0, 0.03);
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
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 50%, #f1f5f9 100%);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 85% 15%, rgba(217, 166, 91, 0.18) 0%, transparent 50%),
                linear-gradient(rgba(15, 23, 42, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.03) 1px, transparent 1px);
            background-size: 100% 100%, 20px 20px, 20px 20px;
            pointer-events: none;
        }

        .brand-badge {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            padding: 5px 12px;
            border-radius: 12px;
            border: 1px solid rgba(217, 166, 91, 0.35);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
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
            background: #ecfdf5;
            color: #059669;
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
            margin-top: -60px;
            padding: 0 24px 18px;
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
            background: linear-gradient(145deg, #fbe2a7 0%, #e5b869 50%, #ffffff 100%);
            box-shadow: 0 10px 24px -4px rgba(15, 23, 42, 0.16),
                        0 0 20px rgba(229, 184, 105, 0.25);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ffffff;
            display: block;
            background-color: #f1f5f9;
        }

        .avatar-initial {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 42px;
            font-weight: 800;
            text-shadow: 0 2px 6px rgba(0,0,0,0.15);
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
            border: 2px solid #ffffff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
        }

        /* Profile Meta */
        .profile-name {
            font-size: 23px;
            font-weight: 800;
            margin-top: 14px;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .profile-job {
            font-size: 14px;
            font-weight: 600;
            margin-top: 4px;
            color: #b45309;
            letter-spacing: 0.2px;
        }

        .profile-dept-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            padding: 4px 13px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        /* Quick Icon Strip (Hàng đầu tiên dưới tên: Zalo, Viber, Facebook, Website) */
        .quick-icon-strip {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .quick-icon-btn {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.05);
            padding: 8px;
        }

        .quick-icon-btn img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            transition: transform 0.22s ease;
        }

        .quick-icon-btn:hover {
            transform: translateY(-3px) scale(1.08);
            border-color: #d9a65b;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
            background: #ffffff;
        }

        .quick-icon-btn:active {
            transform: translateY(0) scale(0.96);
        }

        /* Hero Action Buttons (Save Contact & Share) */
        .hero-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 4px 20px 20px;
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
            background: linear-gradient(135deg, #fcebc2 0%, #e5b869 50%, #c48e35 100%);
            color: #1e293b;
            box-shadow: 0 8px 20px -5px rgba(229, 184, 105, 0.5),
                        inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border-color: rgba(229, 184, 105, 0.5);
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: skewX(-20deg);
            transition: 0.6s;
        }

        .btn-hero.primary:hover::after {
            left: 140%;
        }

        .btn-hero.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -5px rgba(229, 184, 105, 0.65);
        }

        .btn-hero.secondary {
            background: #ffffff;
            color: #1e293b;
            border-color: #e2e8f0;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.04);
        }

        .btn-hero.secondary:hover {
            background: #f8fafc;
            border-color: #d9a65b;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
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
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .section-title::before {
            content: '';
            width: 3px;
            height: 12px;
            background: #c38c32;
            border-radius: 2px;
            display: inline-block;
        }

        /* Direct Contact Channel Rows (Phone & Email) */
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
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            color: #0f172a;
            text-decoration: none;
            transition: all 0.22s ease;
            position: relative;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        }

        .channel-row:hover {
            background: #f8fafc;
            border-color: rgba(217, 166, 91, 0.5);
            transform: translateX(3px);
            color: #0f172a;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07);
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
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
            padding: 4px;
            transition: transform 0.2s ease;
        }

        .channel-icon-wrap img {
            width: 32px;
            height: 32px;
            display: block;
            object-fit: contain;
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
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .channel-value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
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
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            margin-left: 8px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .channel-copy-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        /* Company Profile QR Card */
        .company-profile-card {
            background: linear-gradient(145deg, #ffffff 0%, #fbfdf9 100%);
            border: 1px solid rgba(217, 166, 91, 0.4);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
            position: relative;
            overflow: hidden;
        }

        .company-profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #fcebc2, #e5b869, #b45309);
        }

        .profile-card-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            background: #fef9ee;
            border: 1px solid rgba(217, 166, 91, 0.35);
            color: #b45309;
            font-size: 11px;
            font-weight: 750;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .profile-card-desc {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .profile-qr-box {
            display: inline-block;
            background: #ffffff;
            padding: 12px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
            margin-bottom: 16px;
            transition: transform 0.25s ease;
        }

        .profile-qr-box:hover {
            transform: scale(1.03);
        }

        .profile-qr-box img {
            width: 170px;
            height: 170px;
            display: block;
        }

        .btn-company-profile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 18px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 650;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.15);
        }

        .btn-company-profile:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.25);
            color: #ffffff;
        }

        /* Bio Box */
        .bio-box {
            padding: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #d9a65b;
            border-radius: 14px;
            color: #334155;
            font-size: 13.5px;
            line-height: 1.65;
            white-space: pre-line;
        }

        /* Footer */
        .card-footer {
            padding: 22px 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .footer-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 6px 16px;
            border-radius: 12px;
            margin-bottom: 8px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
        }

        .footer-logo-img {
            height: 26px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .footer-desc {
            font-size: 11px;
            color: #64748b;
        }

        /* QR & Share Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
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
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            max-width: 360px;
            width: 100%;
            padding: 26px 22px;
            text-align: center;
            position: relative;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.25);
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
            background: #f1f5f9;
            border: none;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .qr-card-container {
            background: #ffffff;
            border-radius: 16px;
            padding: 14px;
            display: inline-block;
            margin: 14px 0 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
        }

        .qr-card-container img {
            display: block;
            width: 200px;
            height: 200px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 750;
            color: #0f172a;
        }

        .modal-subtitle {
            font-size: 13px;
            color: #64748b;
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
            background: linear-gradient(135deg, #fcebc2, #e5b869);
            color: #1e293b;
            box-shadow: 0 4px 12px rgba(229, 184, 105, 0.3);
        }

        .btn-modal.secondary {
            background: #f1f5f9;
            color: #1e293b;
            border: 1px solid #e2e8f0;
        }

        .btn-modal.secondary:hover {
            background: #e2e8f0;
        }

        /* Toast Feedback */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #0f172a;
            color: #ffffff;
            padding: 12px 22px;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.25);
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
                <img src="{{ asset('images/logo.png') }}" alt="HoaBinh Group" class="brand-logo-img">
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
                <div class="verified-badge" title="Đã xác thực bởi HoaBinh Group">
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

            <!-- Hàng đầu tiên chỗ dưới tên: Zalo, Viber, Facebook, Website (chỉ icon) -->
            <div class="quick-icon-strip">
                @if($employee->zalo)
                    <a href="{{ $employee->zalo_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Zalo">
                        <img src="{{ asset('storage/icon/zalo.webp') }}" alt="Zalo">
                    </a>
                @endif

                @if($employee->viber)
                    <a href="{{ $employee->viber_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Viber">
                        <img src="{{ asset('storage/icon/viber.png') }}" alt="Viber">
                    </a>
                @endif

                <a href="{{ $employee->facebook_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Facebook">
                    <img src="{{ asset('storage/icon/facebook.webp') }}" alt="Facebook">
                </a>

                <a href="{{ $employee->website ?: 'https://hoabinh-group.com' }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="Website">
                    <img src="{{ asset('storage/icon/web.png') }}" alt="Website">
                </a>

                @if($employee->whatsapp)
                    <a href="{{ $employee->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="WhatsApp">
                        <img src="{{ asset('storage/icon/whatsapp.png') }}" alt="WhatsApp">
                    </a>
                @endif

                @if($employee->linkedin)
                    <a href="{{ $employee->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="quick-icon-btn" title="LinkedIn">
                        <img src="{{ asset('storage/icon/linkedIn.webp') }}" alt="LinkedIn">
                    </a>
                @endif
            </div>
        </div>

        <!-- Primary Action Buttons: Save Contact & Share -->
        <div class="hero-actions">
            <a href="{{ route('card.show', $employee->code) }}?download=vcard" id="download-card" class="btn-hero primary">
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

        <!-- Section: Company Profile QR Code -->
        <div class="content-section">
            <div class="section-header">
                <span class="section-title">Hồ sơ năng lực tập đoàn</span>
            </div>

            <div class="company-profile-card">
                <div class="profile-card-badge">HOABINH GROUP PROFILE</div>
                <p class="profile-card-desc">Quét mã QR hoặc nhấn vào nút bên dưới để xem trực tuyến Hồ sơ năng lực của HoaBinh Group.</p>
                <div class="profile-qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=0&data={{ urlencode('https://profile.hoabinh-group.com/') }}" alt="QR Hồ sơ năng lực HoaBinh Group" loading="lazy">
                </div>
                <a href="https://profile.hoabinh-group.com/" target="_blank" rel="noopener noreferrer" class="btn-company-profile">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span>Xem Hồ Sơ Năng Lực (Profile)</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:auto">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Section: Thông tin thêm (Điện thoại & Email) -->
        @if($employee->phone || $employee->email)
        <div class="content-section">
            <div class="section-header">
                <span class="section-title">Thông tin thêm</span>
            </div>

            <div class="channels-list">
                <!-- Phone -->
                @if($employee->phone)
                    <div class="channel-row">
                        <a href="tel:{{ preg_replace('/[^+0-9]/', '', $employee->phone) }}" class="channel-main">
                            <div class="channel-icon-wrap">
                                <img src="{{ asset('storage/icon/phone.png') }}" alt="Điện thoại">
                            </div>
                            <div class="channel-details">
                                <span class="channel-label">Điện thoại</span>
                                <span class="channel-value">{{ $employee->phone }}</span>
                            </div>
                        </a>
                        <button class="channel-copy-btn" title="Sao chép số điện thoại" onclick="copyToClipboard('{{ $employee->phone }}', 'Đã chép số điện thoại!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Email -->
                @if($employee->email)
                    <div class="channel-row">
                        <a href="mailto:{{ $employee->email }}" class="channel-main">
                            <div class="channel-icon-wrap">
                                <img src="{{ asset('storage/icon/email.png') }}" alt="Email">
                            </div>
                            <div class="channel-details">
                                <span class="channel-label">Email công việc</span>
                                <span class="channel-value">{{ $employee->email }}</span>
                            </div>
                        </a>
                        <button class="channel-copy-btn" title="Sao chép email" onclick="copyToClipboard('{{ $employee->email }}', 'Đã chép email!')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                    </div>
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
                <img src="{{ asset('images/logo.png') }}" alt="HoaBinh Group" class="footer-logo-img">
            </div>
            <div class="footer-desc">HoaBinh Group · NFC Smart Digital Business Card</div>
        </div>
    </div>

    <!-- QR Code & Share Modal for Employee Card -->
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
                title: '{{ $employee->name }} | HoaBinh Group',
                text: 'Danh thiếp điện tử của {{ $employee->name }} ({{ $employee->job_title ?: 'HoaBinh Group' }})',
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
