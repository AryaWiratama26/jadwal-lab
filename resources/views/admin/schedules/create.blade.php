@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="animate-fade-up" style="max-width: 720px;">
    
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.schedules.index') }}" style="font-size: 0.8125rem; color: var(--c-ink-3); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 6px;">
            <i class="ph ph-arrow-left"></i> Kembali ke jadwal
        </a>
        <h2 class="text-heading">Tambah Jadwal Baru</h2>
    </div>

    <div class="card" style="padding: 1.5rem;">
        <form action="{{ route('admin.schedules.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label class="form-label" for="course_id">Mata Kuliah</label>
                    <select name="course_id" id="course_id" class="form-input" required>
                        <option value="">Pilih matkul</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" for="angkatan">Angkatan</label>
                    <input type="text" name="angkatan" id="angkatan" value="{{ old('angkatan') }}" class="form-input" placeholder="2023" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label class="form-label" for="program">Program</label>
                    <select name="program" id="program" class="form-input" required>
                        <option value="Reguler" {{ old('program') === 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="Ekstensi" {{ old('program') === 'Ekstensi' ? 'selected' : '' }}>Ekstensi</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="kelas">Kelas</label>
                    <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}" class="form-input" placeholder="TI.23.A.1" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label class="form-label" for="hari">Hari</label>
                    <select name="hari" id="hari" class="form-input" required>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                            <option value="{{ $day }}" {{ old('hari') === $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" for="waktu_mulai">Waktu Mulai</label>
                    <input type="text" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai') }}" class="form-input" placeholder="08.00" required>
                </div>
                <div>
                    <label class="form-label" for="waktu_selesai">Waktu Selesai</label>
                    <input type="text" name="waktu_selesai" id="waktu_selesai" value="{{ old('waktu_selesai') }}" class="form-input" placeholder="10.00" required>
                </div>
            </div>

            <div>
                <label class="form-label" for="dosen">Dosen</label>
                <input type="text" name="dosen" id="dosen" value="{{ old('dosen') }}" class="form-input" placeholder="Nama dosen pengampu" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label class="form-label" for="ruangan">Ruangan</label>
                    <input type="text" name="ruangan" id="ruangan" value="{{ old('ruangan') }}" class="form-input" placeholder="Lab 1">
                </div>
                <div style="display: flex; align-items: flex-end; padding-bottom: 6px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span style="font-size: 0.8125rem; font-weight: 600; color: var(--c-ink);">Jadwal Aktif</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="form-label">Asisten Lab</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 4px;">
                    @foreach($assistants as $assistant)
                        <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; cursor: pointer; border: 1px solid var(--c-border); border-radius: var(--radius-md); background: var(--c-surface); transition: border-color 0.15s;">
                            <input type="checkbox" name="assistants[]" value="{{ $assistant->id }}" {{ in_array($assistant->id, old('assistants', [])) ? 'checked' : '' }}>
                            <div>
                                <span style="font-size: 0.8125rem; font-weight: 600; color: var(--c-ink); display: block;">{{ $assistant->name }}</span>
                                @if($assistant->nim)
                                    <span style="font-size: 0.6875rem; color: var(--c-ink-3);">{{ $assistant->nim }}</span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="form-label" for="catatan">Catatan <span style="font-weight: 400; color: var(--c-ink-3);">(opsional)</span></label>
                <textarea name="catatan" id="catatan" rows="2" class="form-input" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
            </div>

            <div style="display: flex; gap: 8px; margin-top: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i> Simpan Jadwal
                </button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
