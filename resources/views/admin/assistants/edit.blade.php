@extends('layouts.admin')

@section('title', 'Edit Asisten')

@section('content')
<div class="animate-fade-up" style="max-width: 560px;">
    
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.assistants.index') }}" style="font-size: 0.8125rem; color: var(--c-ink-3); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
        <h2 class="text-heading">Edit Asisten</h2>
    </div>

    <div class="card" style="padding: 1.5rem;">
        <form action="{{ route('admin.assistants.update', $assistant) }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label" for="nim">NIM <span style="font-weight: 400; color: var(--c-ink-3);">(opsional)</span></label>
                <input type="text" name="nim" id="nim" value="{{ old('nim', $assistant->nim) }}" class="form-input">
            </div>

            <div>
                <label class="form-label" for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $assistant->name) }}" class="form-input" required>
            </div>

            <div>
                <label class="form-label" for="phone">No. Telepon <span style="font-weight: 400; color: var(--c-ink-3);">(opsional)</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $assistant->phone) }}" class="form-input">
            </div>

            <div style="display: flex; gap: 8px; margin-top: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.assistants.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
