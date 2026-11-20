<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Jadwal Praktikum</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column;">

    <header style="background: var(--c-surface); border-bottom: 1px solid var(--c-border);">
        <div style="max-width: 1120px; margin: 0 auto; padding: 12px 1.5rem; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('images/logo-upb.png') }}" alt="Logo UPB" style="height: 38px; width: auto;">
            <img src="{{ asset('images/simbaft.png') }}" alt="SIMBA FT" style="height: 28px; width: auto; opacity: 0.5;">
            <div style="width: 1px; height: 28px; background: var(--c-border);"></div>
            <div style="display: flex; flex-direction: column;">
                <span style="font-weight: 700; font-size: 0.9375rem; line-height: 1.2; color: var(--c-ink);">Jadwal Praktikum</span>
                <span style="font-size: 0.6875rem; color: var(--c-ink-3);">{{ config('app.university') }}</span>
            </div>
        </div>
    </header>

    <main style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 1.5rem;">
        <div style="text-align: center; max-width: 420px;">
            <div style="font-size: 5rem; font-weight: 800; color: var(--c-navy); line-height: 1; letter-spacing: -0.03em;">403</div>
            <div style="width: 40px; height: 3px; background: var(--c-navy); border-radius: 99px; margin: 1rem auto;"></div>
            <p style="font-size: 1rem; font-weight: 600; color: var(--c-ink); margin-bottom: 4px;">Akses Ditolak</p>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3);">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <a href="/" class="btn btn-primary" style="margin-top: 1.5rem; text-decoration: none;">
                <i class="ph ph-arrow-left"></i> Kembali ke Beranda
            </a>

        </div>
    </main>

    <footer style="border-top: 1px solid var(--c-border); padding: 1.5rem; text-align: center;">
        <p style="font-size: 0.75rem; color: var(--c-ink-3);">
            &copy; {{ date('Y') }} Laboratorium Informatika — {{ config('app.university') }}
        </p>
    </footer>

</body>
</html>
