<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Candra Garage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #020617;
            --bg-card: #0b1020;
            --accent: #22d3ee;
            --accent-2: #a855f7;
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --border-soft: rgba(148,163,184,0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(34,211,238,0.12), transparent 55%),
                radial-gradient(circle at bottom right, rgba(168,85,247,0.18), transparent 60%),
                #020617;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-wrapper {
            max-width: 960px;
            width: 100%;
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            gap: 2.5rem;
            align-items: center;
        }

        .brand {
            font-size: 0.85rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin-bottom: 0.75rem;
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 18px rgba(34,211,238,1);
        }

        .title {
            font-size: clamp(1.9rem, 2.4vw, 2.2rem);
            margin: 0 0 .4rem;
        }

        .title span {
            background: linear-gradient(135deg, var(--accent), var(--accent-2), #f97316);
            -webkit-background-clip: text;
            color: transparent;
        }

        .subtitle {
            margin: 0;
            font-size: .9rem;
            color: var(--text-muted);
            max-width: 26rem;
            line-height: 1.6;
        }

        .highlight-strip {
            margin-top: 1.6rem;
            padding: .8rem 1rem;
            border-radius: .9rem;
            border: 1px solid rgba(148,163,184,0.35);
            background: radial-gradient(circle at left, rgba(34,211,238,0.18), #020617);
            font-size: .8rem;
            color: var(--text-muted);
        }

        .highlight-strip strong {
            color: var(--accent);
        }

        .card {
            background: radial-gradient(circle at top, rgba(148,163,184,0.16), #020617 55%);
            border-radius: 1.2rem;
            border: 1px solid var(--border-soft);
            padding: 1.6rem 1.7rem 1.5rem;
            box-shadow: 0 18px 45px rgba(0,0,0,0.8);
        }

        .card-header {
            margin-bottom: 1.2rem;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.3rem;
        }

        .card-header p {
            margin: .2rem 0 0;
            font-size: .8rem;
            color: var(--text-muted);
        }

        label {
            display: block;
            font-size: .8rem;
            margin-bottom: .25rem;
            color: var(--text-main);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            border-radius: .7rem;
            border: 1px solid rgba(55,65,81,0.8);
            background: rgba(15,23,42,0.9);
            color: var(--text-main);
            padding: .55rem .85rem;
            font-size: .85rem;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 1px rgba(34,211,238,0.45);
            background: #020617;
        }

        .error {
            font-size: .75rem;
            color: #fb7185;
            margin-top: .15rem;
        }

        .inline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.1rem;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .8rem;
            color: var(--text-muted);
        }

        .remember input {
            width: 14px;
            height: 14px;
        }

        .link {
            font-size: .8rem;
            color: var(--accent);
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            border: none;
            border-radius: .9rem;
            padding: .6rem 1rem;
            font-size: .9rem;
            font-weight: 500;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #020617;
            cursor: pointer;
            box-shadow: 0 0 28px rgba(34,211,238,0.4);
            transition: transform .15s ease-out, box-shadow .15s ease-out, filter .15s ease-out;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 0 38px rgba(34,211,238,0.55);
        }

        .auth-footer {
            margin-top: .9rem;
            font-size: .8rem;
            text-align: center;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .auth-wrapper {
                grid-template-columns: minmax(0,1fr);
                max-width: 480px;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            .card {
                padding-inline: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    {{-- Kolom kiri: tagline --}}
    <div>
        <div class="brand">
            <span class="brand-dot"></span>
            <span>Candra Garage</span>
        </div>
        <h1 class="title">
            Masuk ke <span>panel bengkel</span>
        </h1>
        <p class="subtitle">
            Kelola reservasi, status servis, dan invoice pelanggan dalam satu dashboard.
            Akses hanya untuk pengguna terdaftar (admin, kasir, montir, dan pelanggan).
        </p>

        <div class="highlight-strip">
            Login menggunakan akun yang sudah dibuat admin atau daftar sebagai
            <strong>pelanggan</strong> melalui halaman registrasi.
        </div>
    </div>

    {{-- Kolom kanan: form login --}}
    <div>
        <div class="card">
            <div class="card-header">
                <h2>Login</h2>
                <p>Masuk menggunakan email dan kata sandi Anda.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="email">
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password">
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="inline">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="link" href="{{ route('password.request') }}">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-primary">
                    Masuk
                </button>

                <div class="auth-footer">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar sebagai pelanggan</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
