@extends('layouts.admin')

@section('title', 'Mata Kuliah')

@section('content')
<div class="animate-fade-up">

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <div>
            <h2 class="text-heading" style="margin-bottom: 2px;">Mata Kuliah</h2>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3);">Kelola program dan mata kuliah laboratorium.</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="ph ph-plus"></i> Tambah Matkul
        </a>
    </div>

    @if(session('error'))
        <div class="animate-fade-up" style="background: var(--c-danger-t); border: 1px solid #FECACA; border-radius: var(--radius-md); margin-bottom: 1.5rem; padding: 12px 16px; display: flex; align-items: center; gap: 10px;">
            <i class="ph ph-warning-circle" style="color: var(--c-danger); font-size: 1.125rem; flex-shrink: 0;"></i>
            <p style="color: #991B1B; font-weight: 500; margin: 0; font-size: 0.8125rem;">{{ session('error') }}</p>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
        @forelse($courses as $course)
            <div class="card" style="display: flex; flex-direction: column;">
                
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 42px; height: 42px; border-radius: var(--radius-md); background: {{ $course->color ?? 'var(--c-navy)' }}; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                            <i class="ph-fill ph-book-open" style="font-size: 1.25rem;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <h3 style="font-size: 0.9375rem; font-weight: 600; color: var(--c-ink); margin-bottom: 2px;">{{ $course->name }}</h3>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="badge badge-outline" style="font-size: 0.625rem;">{{ $course->code }}</span>
                                @if($course->semester)
                                    <span style="font-size: 0.6875rem; color: var(--c-ink-3);">{{ $course->semester }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--c-border); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 6px; color: var(--c-ink-3);">
                        <i class="ph ph-calendar-dots" style="font-size: 0.875rem;"></i>
                        <span style="font-size: 0.75rem;">{{ $course->schedules_count }} Jadwal</span>
                    </div>

                    <div style="display: flex; gap: 4px;">
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn-icon" title="Edit">
                            <i class="ph ph-pencil-simple"></i>
                        </a>
                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Hapus mata kuliah {{ $course->name }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon" style="color: var(--c-danger); border-color: var(--c-danger-t); background: var(--c-danger-t);" title="Hapus">
                                <i class="ph ph-trash-simple"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div style="grid-column: 1 / -1; background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius-lg); text-align: center; padding: 3rem 2rem;">
                <i class="ph ph-books" style="font-size: 2rem; color: var(--c-ink-3); margin-bottom: 8px;"></i>
                <p style="color: var(--c-ink-3); font-size: 0.8125rem;">Belum ada mata kuliah.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
