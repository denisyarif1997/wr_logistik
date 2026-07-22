<x-guest-layout>
    @section('title')
        {{ 'Log in' }}
    @endsection

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #eef1f5 0%, #dde3ea 45%, #c9d3de 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .card {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 40px rgba(30, 41, 59, 0.12);
            overflow: hidden;
        }

        .card-header {
            background: #fff;
            border-bottom: none;
            padding: 2rem 2rem 0.5rem;
            text-align: center;
        }

        .card-header a {
            color: #1e3a5f;
            font-size: 1.4rem;
            text-decoration: none;
            letter-spacing: 0.3px;
        }

        .card-body {
            padding: 1.5rem 2rem 2rem;
        }

        .login-box-msg {
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.75rem;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.35rem;
        }

        .input-group {
            margin-bottom: 1.1rem;
        }

        .input-group-text {
            background-color: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            border-right: none;
        }

        .form-control {
            background-color: #f9fafb;
            color: #111827;
            border: 1px solid #e5e7eb;
            border-left: none;
            padding: 0.65rem 0.85rem;
        }

        .form-control:focus {
            background-color: #fff;
            color: #111827;
            border-color: #3b5f8a;
            box-shadow: 0 0 0 3px rgba(59, 95, 138, 0.12);
        }

        .toggle-password {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: none;
            color: #6b7280;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: #374151;
        }

        .form-check-input:checked {
            background-color: #3b5f8a;
            border-color: #3b5f8a;
        }

        .form-check-label {
            font-size: 0.85rem;
            color: #4b5563;
        }

        .btn-primary {
            background-color: #3b5f8a;
            border-color: #3b5f8a;
            font-weight: 600;
            letter-spacing: 0.3px;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: background-color 0.15s ease, transform 0.05s ease;
        }

        .btn-primary:hover {
            background-color: #2f4d70;
            border-color: #2f4d70;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .invalid-feedback-custom {
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: -0.75rem;
            margin-bottom: 0.9rem;
        }
    </style>

    <div class="login-box">
        <div class="card">
            <div class="card-header">
                <a href="/"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masuk untuk memulai sesi Anda</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}"
                            required autofocus autocomplete="username" placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback-custom">{{ $message }}</div>
                    @enderror

                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input id="password" class="form-control" type="password" name="password"
                            required autocomplete="current-password" placeholder="••••••••">
                        <span class="input-group-text toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye-fill" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback-custom">{{ $message }}</div>
                    @enderror

                    <div class="row align-items-center mt-2">
                        <div class="col-7">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                <label for="remember" class="form-check-label">
                                    Ingat saya
                                </label>
                            </div>
                        </div>
                        <div class="col-5 text-end">
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        }
    </script>
</x-guest-layout>