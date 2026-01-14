<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masukkan Kode OTP - Candra Garage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #020617;
            --accent: #22d3ee;
            --accent-2: #a855f7;
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --border-soft: rgba(148,163,184,0.35);
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
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
        .card {
            background: radial-gradient(circle at top, rgba(148,163,184,0.16), #020617 55%);
            border-radius: 1.2rem;
            border: 1px solid var(--border-soft);
            padding: 2rem;
            box-shadow: 0 18px 45px rgba(0,0,0,0.8);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }
        h2 {
            margin: 0 0 0.5rem;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }
        input[type="text"] {
            width: 100%;
            border-radius: .7rem;
            border: 1px solid rgba(55,65,81,0.8);
            background: rgba(15,23,42,0.9);
            color: var(--text-main);
            padding: .75rem;
            font-size: 1.2rem;
            letter-spacing: 0.5rem;
            text-align: center;
            outline: none;
            transition: all 0.2s;
        }
        input[type="text"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 1px rgba(34,211,238,0.45);
        }
        .btn-primary {
            width: 100%;
            border: none;
            border-radius: .9rem;
            padding: .75rem 1rem;
            font-size: .95rem;
            font-weight: 500;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #020617;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(34,211,238,0.4);
            transition: all .2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(34,211,238,0.55);
        }
        .error-msg {
            color: #fb7185;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
         .logout-link {
            display: inline-block;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
            background: none;
            border: none;
            cursor: pointer;
        }
        .logout-link:hover {
            color: var(--accent);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Verifikasi OTP</h2>
        <p>Masukkan 6 digit kode yang dikirim ke email Anda.</p>

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <div class="form-group">
                <input type="text" name="otp_code" maxlength="6" placeholder="000000" autofocus required>
                @error('otp_code')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-link">
                Keluar / Batal
            </button>
        </form>
    </div>
</body>
</html>
