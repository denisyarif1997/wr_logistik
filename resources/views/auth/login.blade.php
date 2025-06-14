<x-guest-layout>
    @section('title')
        {{ 'Log in' }}
    @endsection

    <style>
        body {
            background-image: url('/images/bg-industrial.jpg'); /* kamu bisa ganti dengan gambar tema industrial */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-box {
            margin-top: 5%;
        }

        .card {
            background-color: rgba(34, 34, 34, 0.9);
            color: #fff;
            border: 1px solid #555;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }

        .input-group-text {
            background-color: #444;
            color: #fff;
            border: none;
        }

        .form-control {
            background-color: #2b2b2b;
            color: #fff;
            border: 1px solid #666;
        }

        .form-control:focus {
            background-color: #2b2b2b;
            color: #fff;
            border-color: #999;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-primary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>

    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1 text-white"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}"
                            required autofocus autocomplete="username" placeholder="Email">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="input-group mb-3">
                        <input id="password" class="form-control" type="password" name="password"
                            required autocomplete="current-password" placeholder="Password">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="form-check text-left">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                <label for="remember" class="form-check-label">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Tambahkan ini untuk Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</x-guest-layout>
