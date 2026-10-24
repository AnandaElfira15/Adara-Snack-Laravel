<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · Adara Snack</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="login-logo">A</div>
        <h1>Selamat datang!</h1>
        <p class="muted">Masuk ke dashboard Adara Snack</p>

        @if($errors->any())
            <div class="alert danger">{{ $errors->first() }}</div>
        @endif

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="nama@email.com"
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                required
                placeholder="Masukkan password"
            >

            <label class="remember">
                <input type="checkbox" name="remember">
                Ingat saya
            </label>

            <button class="btn primary full">Masuk ke aplikasi</button>
        </form>
    </div>
</body>
</html>
