<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Jadwal Lab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column !important;
            }
            .mobile-header {
                display: flex !important;
            }
            .admin-sidebar-el {
                position: fixed !important;
                left: -260px;
                top: 0;
                height: 100vh;
                z-index: 99;
                transition: left 0.3s ease;
                box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            }
            .admin-sidebar-el.show {
                left: 0;
            }
            .admin-main {
                padding: 1.5rem 1rem !important;
            }
            .mobile-close-btn {
                display: block !important;
            }
        }
    </style>
</head>
<body style="min-height: 100vh; background: var(--c-bg);">

    <div class="admin-layout" style="display: flex; min-height: 100vh;">

        {{-- MOBILE HEADER --}}
        <div class="mobile-header" style="display: none; position: sticky; top: 0; z-index: 50; background: var(--c-surface); border-bottom: 1px solid var(--c-border); padding: 12px 1.25rem; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo-upb.png') }}" alt="Logo UPB" style="height: 30px; width: auto;">
                <span style="font-weight: 700; font-size: 0.875rem; color: var(--c-ink);">Jadwal Lab</span>
            </div>
            <button onclick="toggleMobileSidebar()" class="btn-icon" style="border: none; background: none;">
                <i class="ph ph-list" style="font-size: 1.25rem;"></i>
            </button>
        </div>

        {{-- MOBILE OVERLAY --}}
        <div id="mobileOverlay" class="mobile-overlay" onclick="toggleMobileSidebar()" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.3); z-index: 98;"></div>

        {{-- SIDEBAR --}}
        <aside id="mobileSidebar" class="admin-sidebar-el" style="width: 250px; background: var(--c-surface); border-right: 1px solid var(--c-border); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; padding: 1.25rem;">

            {{-- Brand --}}
            <div class="sidebar-brand" style="display: flex; align-items: center; gap: 10px; padding-bottom: 1.25rem; border-bottom: 1px solid var(--c-border); margin-bottom: 1rem;">
                <img src="{{ asset('images/logo-upb.png') }}" alt="Logo UPB" style="height: 32px; width: auto;">
                <a href="/admin" style="text-decoration: none; display: flex; flex-direction: column;">
                    <span style="color: var(--c-ink); font-weight: 700; font-size: 0.875rem; line-height: 1.2;">Jadwal Lab</span>
                    <span class="text-label" style="font-size: 0.5625rem;">Admin Panel</span>
                </a>
                {{-- Mobile close --}}
                <button class="mobile-close-btn" onclick="toggleMobileSidebar()" style="display: none; margin-left: auto; background: none; border: none; cursor: pointer; color: var(--c-ink-3); font-size: 1.25rem;">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            {{-- Nav --}}
            <nav style="flex: 1; display: flex; flex-direction: column; gap: 2px;">
                <p class="text-label" style="padding: 8px 14px 4px; font-size: 0.5625rem;">Menu</p>
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="{{ request()->routeIs('admin.dashboard') ? 'ph-fill' : 'ph' }} ph-squares-four"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="admin-nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <i class="{{ request()->routeIs('admin.schedules.*') ? 'ph-fill' : 'ph' }} ph-calendar-dots"></i>
                    Jadwal
                </a>
                <a href="{{ route('admin.courses.index') }}" class="admin-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                    <i class="{{ request()->routeIs('admin.courses.*') ? 'ph-fill' : 'ph' }} ph-books"></i>
                    Mata Kuliah
                </a>
                <a href="{{ route('admin.assistants.index') }}" class="admin-nav-link {{ request()->routeIs('admin.assistants.*') ? 'active' : '' }}">
                    <i class="{{ request()->routeIs('admin.assistants.*') ? 'ph-fill' : 'ph' }} ph-users-three"></i>
                    Asisten
                </a>

                <div class="divider" style="margin: 8px 0;"></div>
                
                <a href="/" target="_blank" class="admin-nav-link">
                    <i class="ph ph-arrow-square-out"></i>
                    Lihat Website
                </a>
            </nav>

            {{-- User --}}
            <div class="sidebar-user" style="border-top: 1px solid var(--c-border); padding-top: 1rem; margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                    <div class="avatar" style="background: var(--c-accent-t); color: var(--c-navy);">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div style="min-width: 0;">
                        <p style="font-size: 0.8125rem; font-weight: 600; color: var(--c-ink); line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</p>
                        <span class="text-label" style="font-size: 0.5625rem; text-transform: none;">Administrator</span>
                    </div>
                </div>
                
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-icon" style="border: none; color: var(--c-ink-3);" title="Logout">
                        <i class="ph ph-sign-out" style="font-size: 1rem;"></i>
                    </button>
                </form>
            </div>

        </aside>

        {{-- MAIN --}}
        <main class="admin-main" style="flex: 1; overflow-x: hidden; padding: 2rem 2.5rem;">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="animate-fade-up" style="background: var(--c-success-t); border: 1px solid #BBF7D0; border-radius: var(--radius-md); margin-bottom: 1.5rem; padding: 12px 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="ph ph-check-circle" style="color: var(--c-success); font-size: 1.125rem; flex-shrink: 0;"></i>
                    <p style="color: #166534; font-weight: 500; margin: 0; font-size: 0.8125rem;">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="animate-fade-up" style="background: var(--c-danger-t); border: 1px solid #FECACA; border-radius: var(--radius-md); margin-bottom: 1.5rem; padding: 12px 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="ph ph-warning-circle" style="color: var(--c-danger); font-size: 1.125rem; flex-shrink: 0;"></i>
                    <p style="color: #991B1B; font-weight: 500; margin: 0; font-size: 0.8125rem;">{{ session('error') }}</p>
                </div>
            @endif
            @if($errors->any())
                <div class="animate-fade-up" style="background: var(--c-danger-t); border: 1px solid #FECACA; border-radius: var(--radius-md); margin-bottom: 1.5rem; padding: 12px 16px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                        <i class="ph ph-warning-circle" style="color: var(--c-danger); font-size: 1.125rem;"></i>
                        <p style="color: #991B1B; font-weight: 600; margin: 0; font-size: 0.8125rem;">Terdapat kesalahan:</p>
                    </div>
                    <ul style="list-style: disc; padding-left: 2rem; margin: 0; color: #991B1B; font-size: 0.8125rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
            
        </main>
    </div>

<script>
function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileOverlay');
    const isOpen = sidebar.classList.contains('show');
    if (isOpen) {
        sidebar.classList.remove('show');
        overlay.style.display = 'none';
    } else {
        sidebar.classList.add('show');
        overlay.style.display = 'block';
    }
}
</script>

</body>
</html>
