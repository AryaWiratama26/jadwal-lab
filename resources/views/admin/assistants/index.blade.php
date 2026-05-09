@extends('layouts.admin')

@section('title', 'Asisten Lab')

@section('content')
<div class="animate-fade-up">

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <div>
            <h2 class="text-heading" style="margin-bottom: 2px;">Asisten Lab</h2>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3);">Kelola daftar asisten laboratorium.</p>
        </div>
        <a href="{{ route('admin.assistants.create') }}" class="btn btn-primary">
            <i class="ph ph-plus"></i> Tambah Asisten
        </a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Asisten</th>
                    <th>NIM</th>
                    <th>No. Telepon</th>
                    <th>Jadwal Aktif</th>
                    <th style="text-align: right; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assistants as $assistant)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="avatar" style="background: var(--c-accent-t); color: var(--c-navy);">
                                    {{ substr($assistant->name, 0, 1) }}
                                </div>
                                <span style="font-weight: 600; color: var(--c-ink);">{{ $assistant->name }}</span>
                            </div>
                        </td>
                        <td style="color: var(--c-ink-2); font-family: monospace; font-size: 0.8125rem;">{{ $assistant->nim ?? '—' }}</td>
                        <td style="color: var(--c-ink-2);">{{ $assistant->phone ?? '—' }}</td>
                        <td>
                            <span class="badge badge-navy">
                                {{ $assistant->schedules_count }} Kelas
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: flex-end; gap: 4px;">
                                <a href="{{ route('admin.assistants.edit', $assistant) }}" class="btn-icon" title="Edit">
                                    <i class="ph ph-pencil-simple"></i>
                                </a>
                                <form action="{{ route('admin.assistants.destroy', $assistant) }}" method="POST" onsubmit="return confirm('Hapus asisten {{ $assistant->name }}?');">
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
                        <td colspan="5" style="padding: 3rem 2rem; text-align: center;">
                            <i class="ph ph-users-three" style="font-size: 1.5rem; color: var(--c-ink-3); margin-bottom: 6px; display: block;"></i>
                            <p style="color: var(--c-ink-3); font-size: 0.8125rem;">Belum ada data asisten.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($assistants->hasPages())
        <div style="margin-top: 1.5rem;">
            {{ $assistants->links() }}
        </div>
    @endif

</div>
@endsection
