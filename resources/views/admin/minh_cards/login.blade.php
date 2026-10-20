<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="theme-color" content="#0b0f19">
    <title>Đăng nhập quản trị cá nhân | /admin/minh</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-body: #0b0f19;
            --bg-card: rgba(17, 24, 39, 0.85);
            --border-card: rgba(255, 255, 255, 0.12);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient glowing backdrop */
        .ambient-glow {
            position: fixed;
            pointer-events: none;
            z-index: 0;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.45;
        }
        .glow-1 {
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.5) 0%, transparent 70%);
            top: 10%;
            left: 20%;
        }
        .glow-2 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.4) 0%, transparent 70%);
            bottom: 10%;
            right: 20%;
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-card);
            border-radius: 24px;
            padding: 36px 30px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6),
                        0 0 0 1px rgba(255, 255, 255, 0.05);
            animation: cardEntrance 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.35);
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        .brand-badge {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #ffffff;
            font-weight: 800;
            font-size: 11px;
            letter-spacing: 1px;
            padding: 2px 7px;
            border-radius: 5px;
        }

        .brand-text {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #93c5fd;
        }

        .login-title {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .login-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 6px;
            line-height: 1.5;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
            color: #e2e8f0;
        }

        .input {
            width: 100%;
            padding: 13px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: #ffffff;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        .input::placeholder {
            color: #64748b;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border: 1px solid rgba(59, 130, 246, 0.5);
            border-radius: 12px;
            color: #ffffff;
            font-family: inherit;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.5);
            margin-top: 6px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -4px rgba(37, 99, 235, 0.65);
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .error-msg {
            color: #f87171;
            font-size: 13px;
            margin-top: 6px;
        }

        .login-footer {
            margin-top: 24px;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 18px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="login-card">
        <div class="brand-header">
            <div class="brand-pill">
                <span class="brand-badge">MINH</span>
                <span class="brand-text">PRIVATE NFC ADMIN</span>
            </div>
            <h1 class="login-title">Đăng nhập quản trị</h1>
            <p class="login-subtitle">Hệ thống quản lý danh thiếp cá nhân độc lập</p>
        </div>

        <form method="post" action="{{ route('admin.minh.login.submit') }}">
            @csrf

            <div class="field">
                <label for="email">Tài khoản Email</label>
                <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" placeholder="name@domain.com" required autofocus>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Mật khẩu</label>
                <input class="input" id="password" type="password" name="password" autocomplete="current-password" placeholder="••••••••" required>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Đăng nhập hệ thống</button>
        </form>

        <div class="login-footer">
            Bảo mật phiên đăng nhập · URL: <strong>/admin/minh</strong>
        </div>
    </div>
</body>
</html>
