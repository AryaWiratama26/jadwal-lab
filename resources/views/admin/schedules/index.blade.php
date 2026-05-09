@extends('layouts.admin')

@section('title', 'Jadwal Praktikum')

@section('content')
<div class="animate-fade-up">

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="text-heading" style="margin-bottom: 2px;">Jadwal Praktikum</h2>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3);">Kelola entri jadwal kelas.</p>
        </div>
        
        <div style="display: flex; gap: 8px; align-items: center;">
            <form action="" method="GET">
                <select name="course_id" class="form-input" style="min-width: 180px; padding: 9px 36px 9px 14px;" onchange="this.form.submit()">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                <i class="ph ph-plus"></i> Tambah
            </a>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="data-table" style="min-width: 860px;">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Program / Kelas</th>
                        <th>Waktu</th>
                        <th>Ruangan</th>
                        <th>Dosen</th>
                        <th>Asisten</th>
                        <th style="text-align: right; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="dot-indicator" style="background: {{ $schedule->course->color ?? 'var(--c-navy)' }};"></div>
                                    <span style="font-weight: 600; color: var(--c-ink);">{{ $schedule->course->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: var(--c-ink);">{{ $schedule->kelas }}</span>
                                <span style="display: block; font-size: 0.75rem; color: var(--c-ink-3); margin-top: 1px;">{{ $schedule->program }} · {{ $schedule->angkatan }}</span>
                            </td>
                            <td>
                                <span class="badge badge-navy" style="margin-bottom: 3px;">{{ $schedule->hari }}</span>
                                <span style="display: block; font-size: 0.75rem; color: var(--c-ink-3);">{{ $schedule->waktu }}</span>
                            </td>
                            <td style="color: var(--c-ink-2);">{{ $schedule->ruangan ?? '—' }}</td>
                            <td style="color: var(--c-ink-2);">{{ $schedule->dosen }}</td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 3px;">
                                    @foreach($schedule->assistants->take(2) as $assistant)
                                        <span class="badge badge-outline">{{ $assistant->name }}</span>
                                    @endforeach
                                    @if($schedule->assistants->count() > 2)
                                        <span class="badge badge-outline">+{{ $schedule->assistants->count() - 2 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; justify-content: flex-end; gap: 4px;">
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn-icon" title="Edit">
                                        <i class="ph ph-pencil-simple"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon" style="color: var(--c-danger); border-color: var(--c-danger-t); background: var(--c-danger-t);" title="Hapus">
                                            <i class="ph ph-trash-simple"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 3rem 2rem; text-align: center;">
                                <i class="ph ph-calendar-x" style="font-size: 1.5rem; color: var(--c-ink-3); margin-bottom: 6px; display: block;"></i>
                                <p style="color: var(--c-ink-3); font-size: 0.8125rem;">Belum ada jadwal ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($schedules->hasPages())
        <div style="margin-top: 1.5rem;">
            {{ $schedules->links() }}
        </div>
    @endif

</div>
@endsection
