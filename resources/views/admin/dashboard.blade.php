@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="animate-fade-up">

    {{-- Page Header --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="text-heading" style="margin-bottom: 2px;">
                <i class="ph ph-squares-four" style="color: var(--c-navy); margin-right: 4px;"></i> Dashboard
            </h2>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3);">Selamat datang kembali, <strong style="color: var(--c-ink-2);">{{ auth()->user()->name }}</strong></p>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <i class="ph ph-plus"></i> Tambah Jadwal
        </a>
    </div>

    {{-- Stats --}}
    <div class="stat-grid-4" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2.5rem;">
        
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--c-accent-t); color: var(--c-navy);">
                <i class="ph-fill ph-calendar-dots"></i>
            </div>
            <div>
                <p class="stat-value">{{ $totalSchedules }}</p>
                <p class="stat-label">Total Jadwal</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--c-success-t); color: var(--c-success);">
                <i class="ph-fill ph-check-circle"></i>
            </div>
            <div>
                <p class="stat-value">{{ $activeSchedules }}</p>
                <p class="stat-label">Jadwal Aktif</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--c-red-t); color: var(--c-red);">
                <i class="ph-fill ph-books"></i>
            </div>
            <div>
                <p class="stat-value">{{ $totalCourses }}</p>
                <p class="stat-label">Mata Kuliah</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--c-warning-t); color: var(--c-warning);">
                <i class="ph-fill ph-users-three"></i>
            </div>
            <div>
                <p class="stat-value">{{ $totalAssistants }}</p>
                <p class="stat-label">Asisten Lab</p>
            </div>
        </div>

    </div>

    <div class="dashboard-2col" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        {{-- Recent Schedules --}}
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h3 style="font-size: 0.9375rem; font-weight: 600; color: var(--c-ink);">
                    <i class="ph ph-clock-counter-clockwise" style="margin-right: 4px; color: var(--c-ink-3);"></i> Jadwal Terbaru
                </h3>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-ghost" style="font-size: 0.75rem;">
                    Lihat Semua <i class="ph ph-arrow-right"></i>
                </a>
            </div>

            <div class="card" style="padding: 0; overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Kelas</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSchedules as $schedule)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="dot-indicator" style="background: {{ $schedule->course->color ?? 'var(--c-navy)' }};"></div>
                                        <span style="font-weight: 600; color: var(--c-ink);">{{ $schedule->course->name }}</span>
                                    </div>
                                </td>
                                <td style="color: var(--c-ink);">{{ $schedule->kelas }}</td>
                                <td>
                                    <span class="badge badge-navy">{{ $schedule->hari }}</span>
                                    <span style="color: var(--c-ink-3); font-size: 0.75rem; margin-left: 4px;">{{ $schedule->waktu }}</span>
                                </td>
                                <td>
                                    @if($schedule->is_active)
                                        <span class="badge badge-success">
                                            <i class="ph ph-check" style="margin-right: 2px;"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-outline">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 3rem 2rem; text-align: center; color: var(--c-ink-3);">
                                    <i class="ph ph-calendar-blank" style="font-size: 1.25rem; display: block; margin-bottom: 4px;"></i>
                                    Belum ada jadwal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <h3 style="font-size: 0.9375rem; font-weight: 600; color: var(--c-ink); margin-bottom: 1rem;">
                <i class="ph ph-lightning" style="margin-right: 4px; color: var(--c-ink-3);"></i> Aksi Cepat
            </h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                
                <a href="{{ route('admin.schedules.create') }}" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 14px; padding: 1rem 1.25rem;">
                    <div style="width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--c-accent-t); color: var(--c-navy); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="ph ph-calendar-plus" style="font-size: 1.125rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-weight: 600; font-size: 0.8125rem; color: var(--c-ink);">Tambah Jadwal</p>
                        <p style="font-size: 0.6875rem; color: var(--c-ink-3);">Buat entri jadwal baru</p>
                    </div>
                    <i class="ph ph-caret-right" style="color: var(--c-ink-3);"></i>
                </a>

                <a href="{{ route('admin.courses.create') }}" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 14px; padding: 1rem 1.25rem;">
                    <div style="width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--c-red-t); color: var(--c-red); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="ph ph-book-open" style="font-size: 1.125rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-weight: 600; font-size: 0.8125rem; color: var(--c-ink);">Tambah Mata Kuliah</p>
                        <p style="font-size: 0.6875rem; color: var(--c-ink-3);">Daftarkan matkul baru</p>
                    </div>
                    <i class="ph ph-caret-right" style="color: var(--c-ink-3);"></i>
                </a>

                <a href="{{ route('admin.assistants.create') }}" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 14px; padding: 1rem 1.25rem;">
                    <div style="width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--c-warning-t); color: var(--c-warning); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="ph ph-user-plus" style="font-size: 1.125rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-weight: 600; font-size: 0.8125rem; color: var(--c-ink);">Tambah Asisten</p>
                        <p style="font-size: 0.6875rem; color: var(--c-ink-3);">Daftarkan asisten baru</p>
                    </div>
                    <i class="ph ph-caret-right" style="color: var(--c-ink-3);"></i>
                </a>

                <a href="/" target="_blank" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 14px; padding: 1rem 1.25rem;">
                    <div style="width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--c-bg); color: var(--c-ink-3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--c-border);">
                        <i class="ph ph-arrow-square-out" style="font-size: 1.125rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-weight: 600; font-size: 0.8125rem; color: var(--c-ink);">Lihat Website</p>
                        <p style="font-size: 0.6875rem; color: var(--c-ink-3);">Buka halaman publik</p>
                    </div>
                    <i class="ph ph-caret-right" style="color: var(--c-ink-3);"></i>
                </a>

            </div>
        </div>
    </div>

</div>
@endsection
