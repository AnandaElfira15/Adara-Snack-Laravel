<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Adara Snack</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f6f9;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(11, 31, 58, 0.12);
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand h1 {
            color: #0b1f3a;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .brand p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            color: #1f2937;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        input:focus {
            border-color: #0b1f3a;
            box-shadow: 0 0 0 3px rgba(11, 31, 58, 0.08);
        }

        .login-button {
            width: 100%;
            padding: 13px;
            background: #0b1f3a;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 5px;
        }

        .login-button:hover {
            background: #17365f;
        }

        .error-box {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            margin-top: 24px;
            color: #9ca3af;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="brand">
            <h1>Adara Snack</h1>
            <p>Silakan masuk ke akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="login-button">
                Masuk
            </button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} Adara Snack
        </div>
    </div>
</body>
</html>
