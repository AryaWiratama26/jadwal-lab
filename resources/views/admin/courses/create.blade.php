@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')

@section('content')
<div class="animate-fade-up" style="max-width: 560px;">
    
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.courses.index') }}" style="font-size: 0.8125rem; color: var(--c-ink-3); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
        <h2 class="text-heading">Tambah Mata Kuliah</h2>
    </div>

    <div class="card" style="padding: 1.5rem;">
        <form action="{{ route('admin.courses.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf

            <div>
                <label class="form-label" for="name">Nama Mata Kuliah</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" placeholder="Data Mining" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label class="form-label" for="code">Kode</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" class="form-input" placeholder="DM" required>
                </div>
                <div>
                    <label class="form-label" for="color">Warna Badge</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="color" name="color" id="color" value="{{ old('color', '#1B2E5C') }}" style="width: 40px; height: 40px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); cursor: pointer; padding: 2px; background: var(--c-surface);" required>
                        <input type="text" id="color_text" value="{{ old('color', '#1B2E5C') }}" class="form-input" style="flex: 1; font-family: monospace; font-size: 0.8125rem;" oninput="document.getElementById('color').value = this.value">
                    </div>
                </div>
            </div>

            <div>
                <label class="form-label" for="semester">Semester <span style="font-weight: 400; color: var(--c-ink-3);">(opsional)</span></label>
                <input type="text" name="semester" id="semester" value="{{ old('semester') }}" class="form-input" placeholder="Genap 2024/2025">
            </div>

            <div style="display: flex; gap: 8px; margin-top: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i> Simpan
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('color').addEventListener('input', function() {
        document.getElementById('color_text').value = this.value;
    });
</script>
@endsection
