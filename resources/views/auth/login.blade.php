<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pakis Medika Utama</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary-blue: #0F62FE;
            --dark-blue: #0B3E9C;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --glass-bg: rgba(224, 236, 252, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: radial-gradient(circle at 10% 20%, #2563EB 0%, #1D4ED8 45%, #1E3A8A 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient glowing background shapes */
        .ambient-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, rgba(59, 130, 246, 0) 70%);
            top: -10%;
            left: -10%;
            z-index: 1;
            pointer-events: none;
        }

        .ambient-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.15) 0%, rgba(147, 51, 234, 0) 70%);
            bottom: -5%;
            right: -5%;
            z-index: 1;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            min-height: 85vh;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            padding: 2.5rem;
            position: relative;
            z-index: 5;
            align-items: center;
            gap: 4rem;
        }

        /* Left Side Info Panel */
        .info-panel {
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-height: 500px;
            padding: 1rem 0;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 3rem;
        }

        .mobile-logo {
            display: none !important;
        }

        .logo-icon {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo-text h2 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .logo-text span {
            font-size: 9px;
            font-weight: 700;
            color: #93C5FD;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #93C5FD;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            align-self: flex-start;
            margin-bottom: 2rem;
        }

        .badge-pill .pill-dot {
            width: 6px;
            height: 6px;
            background: #10B981;
            border-radius: 50%;
            box-shadow: 0 0 8px #10B981;
        }

        .headline h1 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 4rem;
        }

        .kpi-row {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-top: auto;
        }

        .kpi-item {
            position: relative;
        }

        .kpi-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: -1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
        }

        .kpi-value {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .kpi-label {
            font-size: 9px;
            font-weight: 700;
            color: #93C5FD;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* Top Right Language & Support Actions */
        .top-actions {
            position: absolute;
            top: 2.5rem;
            right: 2.5rem;
            display: flex;
            align-items: center;
            gap: 16px;
            z-index: 10;
        }

        .lang-switch {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        .lang-divider {
            width: 1px;
            height: 14px;
            background: rgba(255, 255, 255, 0.3);
        }

        .help-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        .help-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Right Side Glassmorphic Login Card */
        .card-container {
            display: flex;
            justify-content: flex-end;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            width: 100%;
            max-width: 410px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.3);
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .card-header h3 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 1.25rem;
        }

        .form-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .form-label-link {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary-blue);
            text-decoration: none;
        }
        .form-label-link:hover {
            color: var(--dark-blue);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 16px;
            color: #94A3B8;
            pointer-events: none;
        }

        .input-icon-right {
            position: absolute;
            right: 16px;
            color: #94A3B8;
            cursor: pointer;
            transition: color 0.2s;
        }
        .input-icon-right:hover {
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            background: #FFFFFF;
            border: 1px solid rgba(148, 163, 184, 0.3);
            border-radius: 12px;
            padding: 14px 16px 14px 44px;
            font-size: 13px;
            color: var(--text-dark);
            font-weight: 500;
            outline: none;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(15, 98, 254, 0.15);
        }
        .form-control::placeholder {
            color: #94A3B8;
        }

        .btn-submit {
            background: var(--dark-blue);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 6px -1px rgba(11, 62, 156, 0.2);
            margin-top: 1rem;
        }
        .btn-submit:hover {
            background: var(--primary-blue);
            box-shadow: 0 10px 15px -3px rgba(15, 98, 254, 0.3);
            transform: translateY(-1px);
        }

        .error-alert {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #B91C1C;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
            padding-top: 1.5rem;
            margin-top: 1rem;
        }

        .footer-info {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            body {
                padding: 1.5rem 1rem;
                display: block;
                overflow-x: hidden;
            }
            .ambient-glow-1, .ambient-glow-2 {
                display: none !important;
            }
            .login-wrapper {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 2rem auto !important;
                padding: 0 !important;
            }
            .info-panel {
                display: none !important;
            }
            .mobile-logo {
                display: flex !important;
                justify-content: center;
                align-items: center;
                gap: 12px;
                margin-bottom: 1.5rem;
                width: 100%;
            }
            .card-container {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
            }
            .login-card {
                width: 100% !important;
                max-width: 410px !important;
                margin: 0 auto !important;
                padding: 2.5rem 1.75rem;
                border-radius: 24px;
                box-sizing: border-box !important;
            }
            .top-actions {
                position: absolute;
                top: 1rem;
                right: 1rem;
            }
        }

        @media (max-width: 500px) {
            body {
                padding: 0.75rem;
            }
            .login-wrapper {
                margin: 3.5rem auto 1.5rem auto !important;
            }
            .login-card {
                padding: 1.75rem 1.25rem !important;
                border-radius: 20px !important;
                gap: 1.5rem !important;
            }
            .card-header h3 {
                font-size: 20px;
            }
            .form-control {
                font-size: 13px;
                padding: 12px 16px 12px 40px;
            }
            .top-actions {
                position: absolute;
                top: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Dynamic background blobs -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Top Right Switchers -->
    <div class="top-actions">
        <a href="#" class="lang-switch">
            <i data-lucide="globe" size="14"></i>
            <span>ID</span>
        </a>
        <div class="lang-divider"></div>
        <div class="help-btn">
            <i data-lucide="help-circle" size="16"></i>
        </div>
    </div>

    <div class="login-wrapper">
        <!-- Left Column -->
        <div class="info-panel">
            <div class="logo-container">
                <div class="logo-icon">
                    <i data-lucide="activity" size="20" style="color: #93C5FD;"></i>
                </div>
                <div class="logo-text">
                    <h2>Pakis Medika Utama</h2>
                    <span>Clinical Pharmacy System</span>
                </div>
            </div>

            <div class="content-middle">
                <div class="badge-pill">
                    <span class="pill-dot"></span>
                    <span>Sistem Administrasi Farmasi</span>
                </div>

                <div class="headline">
                    <h1>Presisi Klinis.<br>Pelayanan.</h1>
                </div>
            </div>

            <div class="kpi-row">
                <div class="kpi-item">
                    <div class="kpi-value">99.9%</div>
                    <div class="kpi-label">Uptime</div>
                </div>
                <div class="kpi-item">
                    <div class="kpi-value">24/7</div>
                    <div class="kpi-label">Dukungan</div>
                </div>
                <div class="kpi-item">
                    <div class="kpi-value">v2.4</div>
                    <div class="kpi-label" style="opacity: 0.7;">Versi</div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="card-container">
            <div class="login-card">
                <div class="logo-container mobile-logo">
                    <div class="logo-icon" style="background: rgba(15, 98, 254, 0.1); border: 1px solid rgba(15, 98, 254, 0.2);">
                        <i data-lucide="activity" size="20" style="color: var(--primary-blue);"></i>
                    </div>
                    <div class="logo-text">
                        <h2 style="color: var(--text-dark);">Pakis Medika Utama</h2>
                        <span style="color: var(--text-muted); font-size: 8px;">Clinical Pharmacy System</span>
                    </div>
                </div>

                <div class="card-header">
                    <h3>Selamat Datang Kembali</h3>
                    <p>Silakan masuk untuk mengakses sistem.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @csrf

                    @if($errors->any())
                        <div class="error-alert">
                            <i data-lucide="alert-circle" size="16" style="flex-shrink: 0;"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Input Email/Username -->
                    <div class="form-group">
                        <div class="form-label-row">
                            <span class="form-label">Pengguna Terverifikasi</span>
                        </div>
                        <div class="input-wrapper">
                            <i data-lucide="user" size="16" class="input-icon-left"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Nama Pengguna atau Email" class="form-control">
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div class="form-group">
                        <div class="form-label-row">
                            <span class="form-label">Kata Sandi</span>
                            <a href="#" class="form-label-link">LUPA?</a>
                        </div>
                        <div class="input-wrapper">
                            <i data-lucide="lock" size="16" class="input-icon-left"></i>
                            <input type="password" name="password" id="passwordInput" required placeholder="••••••••" class="form-control" style="padding-right: 44px;">
                            <span id="passwordToggle" class="input-icon-right" style="display: flex; align-items: center; cursor: pointer;">
                                <i data-lucide="eye" id="eyeIcon" size="16"></i>
                                <i data-lucide="eye-off" id="eyeOffIcon" size="16" style="display: none;"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <span>Masuk Ke Sistem</span>
                        <i data-lucide="log-in" size="16"></i>
                    </button>
                </form>

                <div class="card-footer">
                    <div class="footer-info">
                        <i data-lucide="shield-check" size="12" style="color: #10B981;"></i>
                        <span>Enkripsi AES-256-bit</span>
                    </div>
                    <div class="footer-info" style="cursor: pointer;">
                        <i data-lucide="life-buoy" size="12"></i>
                        <span>Bantuan IT</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Password visibility toggle logic
        const passwordInput = document.getElementById('passwordInput');
        const passwordToggle = document.getElementById('passwordToggle');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        if (passwordToggle && passwordInput) {
            passwordToggle.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                if (isPassword) {
                    passwordInput.setAttribute('type', 'text');
                    if (eyeIcon) eyeIcon.style.display = 'none';
                    if (eyeOffIcon) eyeOffIcon.style.display = 'block';
                } else {
                    passwordInput.setAttribute('type', 'password');
                    if (eyeIcon) eyeIcon.style.display = 'block';
                    if (eyeOffIcon) eyeOffIcon.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
