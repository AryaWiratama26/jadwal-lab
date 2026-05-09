<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Jadwal Lab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body style="min-height: 100vh; background: var(--c-bg); display: flex; align-items: center; justify-content: center; padding: 2rem;">

    <div class="animate-fade-up" style="width: 100%; max-width: 380px;">
        
        {{-- Brand --}}
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="{{ asset('images/logo-upb.png') }}" alt="Logo UPB" style="height: 56px; width: auto; margin-bottom: 1rem;">
            <h1 style="font-size: 1.25rem; font-weight: 700; color: var(--c-ink); margin-bottom: 4px;">Admin Panel</h1>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3);">Masuk untuk mengelola jadwal praktikum.</p>
        </div>

        {{-- Card --}}
        <div class="card" style="padding: 1.5rem;">
            
            @if($errors->any())
                <div style="background: var(--c-danger-t); border: 1px solid #FECACA; border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-warning-circle" style="color: var(--c-danger); flex-shrink: 0;"></i>
                    @foreach($errors->all() as $error)
                        <p style="color: #991B1B; font-size: 0.8125rem; font-weight: 500; margin: 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf
                
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="admin@pelitabangsa.ac.id" required autofocus>
                </div>

                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 11px; font-size: 0.875rem; margin-top: 4px;">
                    <i class="ph ph-sign-in"></i> Masuk
                </button>
            </form>

        </div>

        <div style="text-align: center; margin-top: 1.25rem;">
            <a href="/" style="font-size: 0.8125rem; color: var(--c-ink-3); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i class="ph ph-arrow-left"></i> Kembali ke Website
            </a>
        </div>

    </div>

</body>
</html>
