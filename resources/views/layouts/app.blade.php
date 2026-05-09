<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jadwal Praktikum — Lab Informatika')</title>
    <meta name="description" content="Jadwal praktikum Laboratorium Informatika — {{ config('app.university') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column;">

    {{-- HEADER --}}
    <header style="background: var(--c-surface); border-bottom: 1px solid var(--c-border); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1120px; margin: 0 auto; padding: 12px 1.5rem; display: flex; align-items: center; justify-content: space-between;">
            
            <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <img src="{{ asset('images/logo-upb.png') }}" alt="Logo UPB" style="height: 38px; width: auto;">
                <div style="display: flex; flex-direction: column;">
                    <span style="font-weight: 700; font-size: 0.9375rem; line-height: 1.2; letter-spacing: -0.01em; color: var(--c-ink);">Jadwal Praktikum</span>
                    <span style="font-size: 0.6875rem; color: var(--c-ink-3); font-weight: 500;">{{ config('app.university') }}</span>
                </div>
            </a>

            <div style="display: flex; align-items: center; gap: 12px;">
                <span class="hide-mobile" style="font-size: 0.8125rem; color: var(--c-ink-3); font-weight: 500;">
                    <i class="ph ph-flask" style="margin-right: 2px;"></i> Laboratorium Informatika
                </span>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main style="flex: 1; padding: 1.5rem 0;">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer style="border-top: 1px solid var(--c-border); padding: 1.5rem; text-align: center;">
        <p style="font-size: 0.75rem; color: var(--c-ink-3);">
            &copy; {{ date('Y') }} Laboratorium Informatika — {{ config('app.university') }}
        </p>
    </footer>

</body>
</html>
