<x-guest-layout>
    @section('title')
        {{ 'Log in' }}
    @endsection

    <style>
        :root {
            --primary: #0F6CBD;
            --primary-hover: #0D5BA6;
            --primary-light: #EBF4FF;
            --success: #27AE60;
            --danger: #EB5757;
            --bg-body: #F7F9FC;
            --bg-card: #FFFFFF;
            --text-primary: #1A1D26;
            --text-secondary: #5A6070;
            --text-muted: #8B90A0;
            --border-color: #E5E7EB;
            --shadow-sm: 0 1px 3px rgba(26,29,38,0.06), 0 1px 2px rgba(26,29,38,0.04);
            --shadow-md: 0 4px 6px rgba(26,29,38,0.05), 0 2px 4px rgba(26,29,38,0.04);
            --shadow-lg: 0 10px 15px rgba(26,29,38,0.06), 0 4px 6px rgba(26,29,38,0.04);
            --shadow-xl: 0 20px 25px rgba(26,29,38,0.08), 0 8px 10px rgba(26,29,38,0.04);
            --radius-md: 8px;
            --radius-lg: 12px;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: var(--bg-body);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-sans);
            margin: 0;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .login-header {
            padding: 32px 32px 8px;
            text-align: center;
        }

        .login-header .brand {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-header .brand i {
            color: var(--primary);
            font-size: 26px;
        }

        .login-body {
            padding: 16px 32px 32px;
        }

        .login-subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0 0 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .input-group {
            display: flex;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .input-group:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,108,189,0.1);
        }

        .input-group-text {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            background: var(--bg-body);
            color: var(--text-muted);
            border: none;
            font-size: 16px;
        }

        .form-control {
            flex: 1;
            padding: 10px 12px;
            border: none;
            background: var(--bg-card);
            color: var(--text-primary);
            font-size: 14px;
            font-family: var(--font-sans);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .toggle-password {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            background: var(--bg-card);
            color: var(--text-muted);
            border: none;
            border-left: 1px solid var(--border-color);
            cursor: pointer;
            font-size: 16px;
            transition: color 0.15s ease;
        }

        .toggle-password:hover {
            color: var(--text-primary);
        }

        .form-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .form-check-label {
            font-size: 13px;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            font-family: var(--font-sans);
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.05s ease;
            text-decoration: none;
            line-height: 1.4;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-block {
            width: 100%;
        }

        .login-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .login-footer a {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .login-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: var(--text-muted);
            font-size: 12px;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .alert {
            padding: 10px 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-danger {
            background: #FDEDED;
            border: 1px solid #F5C6C6;
            color: var(--danger);
        }

        .alert-success {
            background: #E8F8F0;
            border: 1px solid #C3E6CB;
            color: var(--success);
        }
    </style>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <a href="/" class="brand">
                    <i class="fas fa-shipping-fast"></i>
                    <span>{{ config('app.name') }}</span>
                </a>
            </div>
            <div class="login-body">
                <p class="login-subtitle">Masuk untuk memulai sesi Anda</p>

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input id="email" class="form-control" type="email" name="email"
                                value="{{ old('email') }}" required autofocus autocomplete="username"
                                placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="password" class="form-control" type="password" name="password"
                                required autocomplete="current-password" placeholder="••••••••">
                            <span class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-footer">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label for="remember" class="form-check-label">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </div>
                </form>

                <div class="login-divider">atau</div>

                <p style="text-align: center; margin: 0; font-size: 13px; color: var(--text-muted);">
                    Belum punya akun? <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 500;">Hubungi admin</a>
                </p>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</x-guest-layout>