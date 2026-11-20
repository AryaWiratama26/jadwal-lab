@extends('layouts.admin')

@section('title', 'API Keys')

@section('content')
<div class="animate-fade-up">

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="text-heading" style="margin-bottom: 2px;">
                <i class="ph ph-key" style="color: var(--c-navy); margin-right: 4px;"></i> API Keys
            </h2>
            <p style="font-size: 0.8125rem; color: var(--c-ink-3);">Kelola kunci akses untuk REST API <code style="font-size: 0.75rem;">/api/v1</code>. Semua endpoint wajib pakai key.</p>
        </div>
    </div>

    {{-- Plaintext key (sekali tampil) --}}
    @if(session('plaintext_key'))
        <div class="card" style="border-color: #BBF7D0; background: var(--c-success-t); margin-bottom: 1.5rem; padding: 1.25rem 1.5rem;">
            <p style="font-size: 0.8125rem; font-weight: 700; color: #166534; margin-bottom: 6px;">
                <i class="ph ph-check-circle"></i> Salin key ini sekarang — tidak akan ditampilkan lagi!
            </p>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <code id="newApiKey" style="background: white; border: 1px solid #BBF7D0; border-radius: var(--radius-sm); padding: 8px 12px; font-size: 0.8125rem; word-break: break-all; flex: 1;">{{ session('plaintext_key') }}</code>
                <button type="button" class="btn btn-primary" style="padding: 8px 14px;" onclick="copyApiKey(this)">
                    <i class="ph ph-copy"></i> Salin
                </button>
            </div>
        </div>
    @endif

    {{-- Form buat key baru --}}
    <div class="card" style="padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('admin.api-keys.store') }}" method="POST" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
            @csrf
            <div style="flex: 1; min-width: 220px;">
                <label class="form-label" for="name">Nama Aplikasi / Key Baru</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" placeholder="cth: Aplikasi Mobile, SIMBA FT" required maxlength="100">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="ph ph-plus"></i> Generate Key
            </button>
        </form>
    </div>

    {{-- Daftar key --}}
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 1.5rem;">
        <div style="overflow-x: auto;">
            <table class="data-table" style="min-width: 720px;">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Prefix</th>
                        <th>Status</th>
                        <th>Terakhir Dipakai</th>
                        <th>Dibuat</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keys as $key)
                        <tr>
                            <td style="font-weight: 600; color: var(--c-ink);">{{ $key->name }}</td>
                            <td><code style="font-size: 0.75rem;">{{ $key->prefix }}…</code></td>
                            <td>
                                @if($key->is_active)
                                    <span class="badge badge-success"><i class="ph ph-check" style="margin-right: 2px;"></i> Aktif</span>
                                @else
                                    <span class="badge badge-outline">Revoked</span>
                                @endif
                            </td>
                            <td style="font-size: 0.75rem; color: var(--c-ink-3);">
                                {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : '—' }}
                            </td>
                            <td style="font-size: 0.75rem; color: var(--c-ink-3);">{{ $key->created_at->format('d M Y H:i') }}</td>
                            <td style="text-align: right;">
                                @if($key->is_active)
                                    <form action="{{ route('admin.api-keys.destroy', $key) }}" method="POST" style="display: inline;" class="form-confirm" data-confirm-title="Revoke key {{ $key->name }}?" data-confirm-text="Key yang sudah di-revoke tidak bisa dipakai lagi." data-confirm-yes="Ya, revoke">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost" style="color: var(--c-danger); font-size: 0.75rem;">
                                            <i class="ph ph-prohibit"></i> Revoke
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 0.75rem; color: var(--c-ink-3);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 3rem 2rem; text-align: center; color: var(--c-ink-3);">
                                <i class="ph ph-key" style="font-size: 1.25rem; display: block; margin-bottom: 4px;"></i>
                                Belum ada API key. Buat yang pertama di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Cara pakai --}}
    <div class="card" style="padding: 1.25rem 1.5rem;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--c-ink); margin-bottom: 10px;">
            <i class="ph ph-terminal" style="margin-right: 4px;"></i> Cara Pakai
        </h3>
        <p style="font-size: 0.8125rem; color: var(--c-ink-2); margin-bottom: 8px;">
            Kirim key lewat header <code style="font-size: 0.75rem;">X-API-KEY</code> (atau <code style="font-size: 0.75rem;">Authorization: Bearer</code>) di setiap request:
        </p>
        <pre style="background: var(--c-bg); border: 1px solid var(--c-border); border-radius: var(--radius-sm); padding: 12px 14px; font-size: 0.75rem; overflow-x: auto; margin-bottom: 12px;">curl -H "X-API-KEY: sk_..." {{ url('/api/v1/schedules') }}</pre>
        <div style="font-size: 0.8125rem; color: var(--c-ink-2);">
            <p style="margin-bottom: 4px;"><strong style="color: var(--c-ink);">Endpoint tersedia:</strong></p>
            <ul style="list-style: disc; padding-left: 1.5rem; display: flex; flex-direction: column; gap: 2px;">
                <li><code style="font-size: 0.75rem;">GET/POST /api/v1/schedules</code> — filter: <code style="font-size: 0.75rem;">course_id, hari, program, angkatan, search, is_active, include=course,assistants</code></li>
                <li><code style="font-size: 0.75rem;">GET/PUT/DELETE /api/v1/schedules/{id}</code> — body create/update sama kayak form admin + <code style="font-size: 0.75rem;">assistant_ids: [1,2]</code></li>
                <li><code style="font-size: 0.75rem;">GET/POST /api/v1/courses</code> &nbsp;·&nbsp; <code style="font-size: 0.75rem;">GET/PUT/DELETE /api/v1/courses/{id}</code></li>
                <li><code style="font-size: 0.75rem;">GET/POST /api/v1/assistants</code> &nbsp;·&nbsp; <code style="font-size: 0.75rem;">GET/PUT/DELETE /api/v1/assistants/{id}</code></li>
                <li><code style="font-size: 0.75rem;">GET /api/v1/health</code> — cek key valid</li>
            </ul>
        </div>
    </div>

</div>

<script>
function copyApiKey(btn) {
    const text = document.getElementById('newApiKey').innerText;
    const done = () => {
        btn.innerHTML = '<i class="ph ph-check"></i> Tersalin!';
        toastSuccess('API key tersalin ke clipboard!');
    };
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(done).catch(() => fallbackCopy(text, done));
    } else {
        fallbackCopy(text, done);
    }
}
function fallbackCopy(text, done) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    try { document.execCommand('copy'); done(); } catch (e) { /* abaikan */ }
    document.body.removeChild(ta);
}
</script>
@endsection
