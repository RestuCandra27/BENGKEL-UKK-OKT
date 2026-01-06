<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - Candra Garage</title>
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

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont,"Segoe UI",sans-serif;
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
            grid-template-columns: minmax(0, .95fr) minmax(0, 1.05fr);
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
            max-width: 25rem;
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
            padding: 1.6rem 1.7rem 1.4rem;
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
            margin-bottom: .9rem;
        }

        input[type="text"],
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

        input[type="text"]:focus,
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

        .two-cols {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: .75rem;
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
            margin-top: .4rem;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 0 38px rgba(34,211,238,0.55);
        }

        .auth-footer {
            margin-top: .8rem;
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
                max-width: 540px;
            }
            .two-cols {
                grid-template-columns: minmax(0,1fr);
            }
        }

        @media (max-width: 640px) {
            body { padding: 1rem; }
            .card { padding-inline: 1.2rem; }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    {{-- Kiri: teks --}}
    <div>
        <div class="brand">
            <span class="brand-dot"></span>
            <span>Candra Garage</span>
        </div>
        <h1 class="title">
            Buat akun <span>pelanggan bengkel</span>
        </h1>
        <p class="subtitle">
            Dengan akun ini kamu bisa melakukan booking servis online, memantau status servis,
            dan menyimpan riwayat perawatan motor di Candra Garage.
        </p>

        <div class="highlight-strip">
            Setelah registrasi, kamu akan otomatis terdaftar sebagai
            <strong>role pelanggan</strong>. Hak akses admin/kasir/montir hanya bisa diubah oleh admin.
        </div>
    </div>

    {{-- Kanan: form register --}}
    <div>
        <div class="card">
            <div class="card-header">
                <h2>Registrasi Pelanggan</h2>
                <p>Isi data berikut untuk membuat akun baru.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required autofocus>
                    @error('nama')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="two-cols">
                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>
                </div>

                {{-- Kalau kamu punya field tambahan, misal no_telepon / alamat pelanggan, bisa ditambah di sini --}}

                <button type="submit" class="btn-primary">
                    Daftar
                </button>

                <div class="auth-footer">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk sekarang</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
