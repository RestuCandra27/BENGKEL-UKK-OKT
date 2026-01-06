<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $profil->nama_bengkel ?? 'Bengkel Candra' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #050816;
            --bg-section: #0b1020;
            --accent: #22d3ee;
            /* cyan hidup */
            --accent-soft: rgba(34, 211, 238, 0.18);
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --border-soft: #1f2937;
            --danger: #fb7185;
        }

        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #1f2937 0, #020617 40%, #020617 100%);
            color: var(--text-main);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* NAVBAR */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 6vw;
            background: linear-gradient(to bottom,
                    rgba(3, 7, 18, 0.95),
                    rgba(3, 7, 18, 0.85),
                    transparent);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        }

        .logo-text {
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 0.95rem;
            text-transform: uppercase;
        }

        .logo-pill {
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: radial-gradient(circle at top left, rgba(34, 211, 238, 0.16), transparent 60%);
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
        }

        .nav-links a {
            color: var(--text-muted);
            position: relative;
        }

        .nav-links a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -0.25rem;
            width: 0;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(to right, var(--accent), #a855f7);
            transition: width .25s ease;
        }

        .nav-links a:hover {
            color: var(--text-main);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn {
            border-radius: 999px;
            padding: 0.55rem 1.3rem;
            font-size: 0.85rem;
            border: 1px solid rgba(148, 163, 184, 0.5);
            background: transparent;
            color: var(--text-main);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: all .18s ease-out;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #a855f7);
            border-color: transparent;
            box-shadow: 0 0 35px rgba(34, 211, 238, 0.35);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.3);
        }

        .btn-ghost {
            background: rgba(15, 23, 42, 0.9);
        }

        /* HERO SECTION */

        .hero {
            min-height: 100vh;
            padding: 5rem 6vw 4rem;
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
            gap: 3rem;
            align-items: center;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .18em;
            color: var(--text-muted);
            background: radial-gradient(circle at left, rgba(34, 211, 238, 0.24), transparent 55%);
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 12px rgba(34, 211, 238, 0.9);
        }

        .hero-title {
            font-size: clamp(2.4rem, 4vw, 3rem);
            line-height: 1.1;
            margin: 1rem 0 .4rem;
        }

        .hero-title span.gradient {
            background: linear-gradient(135deg, var(--accent), #a855f7, #f97316);
            -webkit-background-clip: text;
            color: transparent;
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            max-width: 30rem;
            line-height: 1.6;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            margin-top: 1.7rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .hero-meta span {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .hero-cta {
            margin-top: 2.2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            align-items: center;
        }

        .hero-note {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .hero-media {
            position: relative;
            min-height: 260px;
        }

        .hero-card {
            position: relative;
            border-radius: 1.3rem;
            background: radial-gradient(circle at top left, rgba(34, 211, 238, 0.12), rgba(15, 23, 42, 0.96) 40%, #020617 100%);
            padding: 1.2rem;
            border: 1px solid rgba(148, 163, 184, 0.35);
            overflow: hidden;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.95);
        }

        .hero-img-wrapper {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: #020617;
        }

        .hero-img-wrapper img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            filter: saturate(1.2) contrast(1.05);
            transform-origin: center;
            transition: transform 0.8s ease-out;
        }

        .hero-card:hover img {
            transform: scale(1.03);
        }

        .hero-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.5);
        }

        .hero-floating {
            position: absolute;
            bottom: 1rem;
            /* masuk ke dalam card */
            left: 50%;
            transform: translateX(-50%);

            display: flex;
            align-items: center;
            justify-content: center;
            gap: .75rem;

            z-index: 2;
        }


        .hero-chip {
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            font-size: 0.7rem;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.4);
            color: var(--text-muted);
        }

        .hero-chip strong {
            color: var(--accent);
        }

        /* SECTION WRAPPER */
        .section {
            padding: 4rem 6vw 3rem;
        }

        .section-header {
            margin-bottom: 2.3rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
        }

        .section-title {
            font-size: 1.4rem;
            margin: 0;
        }

        .section-kicker {
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .16em;
            color: var(--accent);
        }

        .section-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            max-width: 30rem;
        }

        /* SERVICES */

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
        }

        .service-card {
            border-radius: 1rem;
            padding: 1.2rem;
            background: radial-gradient(circle at top left, var(--accent-soft), #020617);
            border: 1px solid rgba(148, 163, 184, 0.35);
            position: relative;
            overflow: hidden;
            min-height: 140px;
            transition: transform .18s ease-out, box-shadow .18s ease-out, border-color .18s ease-out;
        }

        .service-card:hover {
            transform: translateY(-4px) translateZ(0);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.7);
            border-color: var(--accent);
        }

        .service-tag {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .16em;
            color: var(--accent);
        }

        .service-name {
            font-size: 1rem;
            margin: .5rem 0 .25rem;
        }

        .service-body {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .service-price {
            margin-top: 0.8rem;
            font-size: 0.9rem;
            color: var(--accent);
        }

        /* STATS STRIP */

        .stats-strip {
            margin-top: 2.6rem;
            padding: 1rem 1.2rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: radial-gradient(circle at left, rgba(34, 211, 238, 0.16), rgba(15, 23, 42, 0.96));
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: space-between;
            font-size: 0.85rem;
        }

        .stats-item strong {
            font-size: 1.1rem;
        }

        /* MAP + CONTACT */

        .contact-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            gap: 2rem;
        }

        .card {
            border-radius: 1.2rem;
            padding: 1.4rem;
            background: rgba(15, 23, 42, 0.98);
            border: 1px solid var(--border-soft);
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 0.4rem;
        }

        .card p {
            margin-top: 0;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0.8rem 0 0;
            font-size: 0.88rem;
        }

        .info-list li+li {
            margin-top: 0.35rem;
        }

        .info-label {
            color: var(--text-muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .map-frame {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.4);
            height: 280px;
            /* ❗ tingginya fix, bukan min-height */
        }

        .map-frame iframe {
            width: 100% !important;
            /* paksa override width="600" dari embed */
            height: 100% !important;
            /* paksa isi penuh tinggi container      */
            border: 0;
            display: block;
        }


        /* FOOTER */

        .footer {
            border-top: 1px solid rgba(31, 41, 55, 1);
            padding: 1.5rem 6vw 2rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .7rem;
        }

        /* SCROLL ANIMATIONS */

        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .5s ease-out, transform .5s ease-out;
        }

        .fade-in-view {
            opacity: 1;
            transform: translateY(0);
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: minmax(0, 1fr);
                padding-top: 4.5rem;
            }

            .hero-media {
                order: -1;
            }

            .section {
                padding-inline: 5vw;
            }

            .navbar {
                padding-inline: 5vw;
            }
        }

        @media (max-width: 640px) {
            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <header class="navbar">
        <div class="logo-pill">
            <span class="dot"></span>
            <span class="logo-text">
                {{ $profil->nama_bengkel ?? 'Bengkel Candra' }}
            </span>
        </div>

        <nav class="nav-links">
            <a href="#layanan">Layanan</a>
            <a href="#tentang">Tentang</a>
            <a href="#kontak">Lokasi & Kontak</a>
        </nav>

        <div style="display: flex; gap: .6rem;">
            <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Daftar Pelanggan</a>
        </div>
    </header>

    {{-- HERO --}}
    <section class="hero">
        <div class="fade-up">
            <div class="hero-kicker">
                <span class="dot"></span>
                <span>SERVIS MOTOR TERJADWAL</span>
            </div>

            <h1 class="hero-title">
                {{ $profil->nama_bengkel ?? 'Bengkel Candra' }}<br>
                <span class="gradient">servis cepat, rapi, dan transparan.</span>
            </h1>

            <p class="hero-subtitle">
                {{ $profil->deskripsi ?? 'Rawat motor tanpa drama antrean. Booking online, pantau status servis, dan simpan riwayat perawatan dalam satu aplikasi.' }}
            </p>

            <div class="hero-meta">
                <span>🕒 Jam operasional:
                    <strong>{{ $profil->jam_operasional ?? 'Setiap hari 08.00 – 20.00' }}</strong>
                </span>
                @if (!empty($profil->alamat))
                <span>📍 {{ $profil->alamat }}</span>
                @endif
                @if (!empty($profil->no_telepon))
                <span>☎ {{ $profil->no_telepon }}</span>
                @endif
            </div>

            <div class="hero-cta">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Booking Servis Sekarang
                </a>
                <a href="#layanan" class="btn btn-ghost">
                    Lihat Paket Servis
                </a>
                <div class="hero-note">
                    Tidak perlu antre lama, cukup datang sesuai jadwal booking.
                </div>
            </div>
        </div>

        <div class="hero-media fade-up">
            <div class="hero-card">
                @php
                $heroPath = $profil && !empty($profil->hero_image)
                ? trim($profil->hero_image, '/')
                : 'images/hero-bengkel.jpg';

                $heroImage = asset($heroPath);
                @endphp

                <div class="hero-img-wrapper">
                    <img src="{{ $heroImage }}" alt="Foto bengkel">
                </div>


                <div class="hero-badge">
                    Servis Resmi & Bergaransi
                </div>

                <div class="hero-floating">
                    <div class="hero-chip">
                        SLA Servis rata-rata <strong>60 menit</strong>
                    </div>
                    <div class="hero-chip">
                        <strong>{{ $layanans->count() }}</strong>+ jenis layanan
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LAYANAN --}}
    <section id="layanan" class="section fade-up">
        <div class="section-header">
            <div>
                <div class="section-kicker">Layanan Bengkel</div>
                <h2 class="section-title">Servis favorit pelanggan</h2>
            </div>
            <p class="section-desc">
                Pilih paket servis yang sesuai kebutuhan. Semua harga transparan dan
                bisa dikombinasikan dengan sparepart resmi di bengkel.
            </p>
        </div>

        <div class="services-grid">
            @forelse ($layanans as $layanan)
            <article class="service-card">
                <div class="service-tag">Layanan</div>
                <h3 class="service-name">{{ $layanan->nama_layanan }}</h3>
                <p class="service-body">
                    {{ $layanan->deskripsi ?? 'Perawatan berkala untuk menjaga performa dan keamanan motor Anda.' }}
                </p>
                <div class="service-price">
                    @if (!is_null($layanan->biaya_standar))
                    Mulai dari <strong>Rp {{ number_format($layanan->biaya_standar, 0, ',', '.') }}</strong>
                    @else
                    <span style="color: var(--text-muted);">Harga menyesuaikan jenis motor</span>
                    @endif
                </div>
            </article>
            @empty
            <p class="service-body">
                Data layanan belum diisi. Tambahkan layanan di panel admin agar tampil di halaman ini.
            </p>
            @endforelse
        </div>

        <div class="stats-strip">
            <div class="stats-item">
                <strong>Booking online</strong><br>
                Tanpa antre, datang sesuai jadwal.
            </div>
            <div class="stats-item">
                <strong>Riwayat servis tersimpan</strong><br>
                Semua dikerjakan di sistem, mudah dicek kembali.
            </div>
            <div class="stats-item">
                <strong>Estimasi biaya jelas</strong><br>
                Invoicing otomatis dan transparan.
            </div>
        </div>
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="section fade-up" style="padding-top: 2rem;">
        <div class="section-header">
            <div>
                <div class="section-kicker">Tentang Bengkel</div>
                <h2 class="section-title">Teknisi berpengalaman, sistem modern</h2>
            </div>
            <p class="section-desc">
                Kombinasi montir berpengalaman dengan aplikasi bengkel yang rapi
                bikin proses servis jadi lebih cepat dan minim salah komunikasi.
            </p>
        </div>

        <div class="card">
            <p>
                Kami berfokus pada pengalaman pelanggan: mulai dari reservasi,
                proses servis, hingga pembayaran. Dengan sistem yang terintegrasi,
                montir bisa mencatat keluhan, sparepart, dan riwayat servis
                sehingga kondisi motor Anda selalu terpantau.
            </p>
            <p>
                Untuk armada usaha / fleet, kami juga mendukung pengelolaan banyak
                kendaraan dalam satu akun pelanggan sehingga jadwal servis bisa
                diatur lebih efisien.
            </p>
        </div>
    </section>

    {{-- KONTAK + MAP --}}
    <section id="kontak" class="section fade-up">
        <div class="section-header">
            <div>
                <div class="section-kicker">Lokasi & Kontak</div>
                <h2 class="section-title">Datang langsung atau hubungi kami</h2>
            </div>
            <p class="section-desc">
                Silakan hubungi sebelum datang untuk memastikan slot servis tersedia,
                terutama di akhir pekan.
            </p>
        </div>

        <div class="contact-grid">
            <div class="card">
                <h3>Informasi Bengkel</h3>
                <p>Detail kontak yang terhubung dengan aplikasi bengkel.</p>

                <ul class="info-list">
                    @if (!empty($profil->alamat))
                    <li>
                        <div class="info-label">Alamat</div>
                        {{ $profil->alamat }}
                    </li>
                    @endif

                    @if (!empty($profil->no_telepon))
                    <li>
                        <div class="info-label">Telepon / WhatsApp</div>
                        {{ $profil->no_telepon }}
                    </li>
                    @endif

                    @if (!empty($profil->email_bengkel))
                    <li>
                        <div class="info-label">Email</div>
                        {{ $profil->email_bengkel }}
                    </li>
                    @endif

                    @if (!empty($profil->jam_operasional))
                    <li>
                        <div class="info-label">Jam Operasional</div>
                        {{ $profil->jam_operasional }}
                    </li>
                    @endif

                    @if (!empty($profil->website))
                    <li>
                        <div class="info-label">Website</div>
                        {{ $profil->website }}
                    </li>
                    @endif
                </ul>
            </div>

            <div class="card">
                <h3>Peta Lokasi</h3>
                <p>
                    @if ($profil && $profil->maps_iframe)
                    Gunakan peta di bawah ini untuk menavigasi ke bengkel kami.
                    @else
                    Tambahkan <code>maps_iframe</code> di tabel <code>profil_bengkel</code>
                    untuk menampilkan Google Maps di sini.
                    @endif
                </p>

                <div class="map-frame">
                    @if ($profil && $profil->maps_iframe)
                    {!! $profil->maps_iframe !!}
                    @else
                    <iframe
                        src="https://www.google.com/maps/embed?pb="
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer">
        <span>
            &copy; {{ date('Y') }} {{ $profil->nama_bengkel ?? 'Bengkel Candra' }}.
            Semua hak dilindungi.
        </span>
        <span>
            Dibangun dengan Laravel & sistem manajemen bengkel internal.
        </span>
    </footer>

    <script>
        // Animasi fade-up on scroll
        const observed = document.querySelectorAll('.fade-up');

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-view');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.18
            }
        );

        observed.forEach(el => observer.observe(el));
    </script>
</body>

</html>