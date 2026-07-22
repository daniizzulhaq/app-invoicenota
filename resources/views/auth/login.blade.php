<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'InvoiceKu') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', 'Inter', system-ui, sans-serif;
            background: #f4f5f7;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 20px 40px -12px rgba(30,41,59,0.12);
            padding: 40px 36px;
            border: 1px solid #eef0f3;
        }

        .login-logo {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 20px;
            box-shadow: 0 8px 16px -4px rgba(79,70,229,0.4);
        }

        .login-title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.12);
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            accent-color: #6366f1;
            border-radius: 4px;
        }

        .link-muted {
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
        }

        .link-muted:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: transform 0.1s ease, box-shadow 0.15s ease;
            box-shadow: 0 8px 16px -6px rgba(79,70,229,0.5);
        }

        .btn-login:hover {
            box-shadow: 0 10px 20px -6px rgba(79,70,229,0.6);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            font-size: 12px;
            color: #cbd5e1;
            margin-top: 28px;
        }

        .error-text {
            color: #ef4444;
            font-size: 12.5px;
            margin-top: 5px;
        }

        .status-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-logo">🧾</div>
            <h1 class="login-title">{{ config('app.name', 'InvoiceKu') }}</h1>
            <p class="login-subtitle">Masuk untuk mengelola nota &amp; invoice Anda</p>

            @if (session('status'))
                <div class="status-box">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                        value="{{ old('email') }}" placeholder="nama@email.com"
                        required autofocus autocomplete="username">
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control"
                        placeholder="••••••••" required autocomplete="current-password">
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row-between">
                    <label for="remember_me" class="checkbox-label">
                        <input id="remember_me" type="checkbox" name="remember">
                        Ingat saya
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link-muted">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <p class="login-footer">&copy; {{ date('Y') }} {{ config('app.name', 'InvoiceKu') }}. All rights reserved.</p>
        </div>
    </div>

</body>
</html>