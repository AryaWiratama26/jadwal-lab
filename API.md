# Dokumentasi REST API — Jadwal Lab

Base URL: `http(s)://<host>/api/v1`

Semua endpoint (termasuk `GET`) **wajib** memakai API key. Format respons JSON.

## 1. Autentikasi

Buat key di panel admin: **Dashboard → API Keys → Generate Key**.
Key hanya ditampilkan **sekali** saat dibuat — simpan baik-baik. Satu key yang bocor bisa di-**revoke** tanpa mengganggu key lain.

Kirim key di setiap request lewat salah satu cara ini:

```http
X-API-KEY: sk_xxxxxxxxxxxxxxxx
```

atau

```http
Authorization: Bearer sk_xxxxxxxxxxxxxxxx
```

Contoh curl:

```bash
curl -H "X-API-KEY: sk_xxxx" https://jadwal.contoh.id/api/v1/schedules
```

Contoh JavaScript (fetch):

```js
const res = await fetch('https://jadwal.contoh.id/api/v1/schedules?hari=Senin', {
  headers: { 'X-API-KEY': 'sk_xxxx', 'Accept': 'application/json' },
});
const json = await res.json();
```

## 2. Format Respons

**List (paginated)** — struktur paginator standar Laravel:

```json
{
  "data": [ { "id": 1, "kelas": "TI.23.A.1", "...": "..." } ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": { "current_page": 1, "per_page": 15, "total": 10 }
}
```

**Single / create / update:**

```json
{
  "data": { "id": 14, "kelas": "TI.25.A.9", "...": "..." },
  "message": "Jadwal berhasil ditambahkan."
}
```

**Delete:**

```json
{ "message": "Jadwal berhasil dihapus." }
```

**Error:**

| Status | Arti | Contoh body |
| ------ | ---- | ----------- |
| 401 | Key kosong / salah / di-revoke | `{ "message": "Unauthenticated. Invalid or revoked API key." }` |
| 404 | Data tidak ditemukan | `{ "message": "No query results for model ..." }` |
| 422 | Validasi gagal | `{ "message": "...", "errors": { "hari": ["..."] } }` |
| 429 | Kena rate limit (60 req/menit) | `{ "message": "Too Many Attempts." }` |

## 3. Health Check

Cek key valid + versi API.

```http
GET /health
```

```json
{ "message": "OK", "data": { "version": "v1", "time": "2026-11-20T10:22:44+00:00" } }
```

## 4. Schedules (Jadwal)

### 4.1 List — `GET /schedules`

Selalu diurutkan **hari Senin→Minggu**, lalu jam mulai.

| Query param | Keterangan |
| ----------- | ---------- |
| `course_id` | Filter mata kuliah (id) |
| `hari` | `Senin` `Selasa` `Rabu` `Kamis` `Jumat` `Sabtu` `Minggu` |
| `program` | `Reguler` / `Ekstensi` (bebas, string) |
| `angkatan` | cth: `2023` |
| `search` | Cari di kelas, dosen, ruangan, program |
| `is_active` | `1` atau `0` |
| `include` | Relasi yang dimuat. **Default: `course` + `assistants` ikut semua.** Isi `?include=` untuk membatasi, cth: `?include=course` |
| `per_page` | Default `15`, maks `100` |

```bash
curl -H "X-API-KEY: sk_xxxx" \
  "https://jadwal.contoh.id/api/v1/schedules?hari=Senin&program=Reguler&per_page=10"
```

Contoh satu item:

```json
{
  "id": 13,
  "course_id": 1,
  "angkatan": "2023",
  "program": "Reguler",
  "kelas": "TI.23.A.1",
  "hari": "Senin",
  "waktu_mulai": "08.00",
  "waktu_selesai": "10.00",
  "waktu": "08.00 - 10.00",
  "dosen": "Suherman, S.Kom., M.Kom.",
  "ruangan": "Lab 1",
  "is_active": true,
  "catatan": null,
  "course": { "id": 1, "name": "Data Mining", "code": "DM", "color": "#6366F1", "semester": "Genap 2024/2025" },
  "assistants": [{ "id": 8, "name": "Syifa Aurellia", "nim": "3123102843", "phone": null }],
  "assistant_ids": [8]
}
```

### 4.2 Detail — `GET /schedules/{id}`

```bash
curl -H "X-API-KEY: sk_xxxx" https://jadwal.contoh.id/api/v1/schedules/13
```

### 4.3 Tambah — `POST /schedules`

| Field | Wajib | Aturan |
| ----- | ----- | ------ |
| `course_id` | Ya | id course yang ada |
| `angkatan` | Ya | string, maks 10 |
| `program` | Ya | string, maks 30 |
| `kelas` | Ya | string, maks 30, cth `TI.23.A.1` |
| `hari` | Ya | Salah satu dari 7 hari (lihat tabel di 4.1) |
| `waktu_mulai` / `waktu_selesai` | Ya | string, maks 10, cth `08.00` |
| `dosen` | Ya | string, maks 255 |
| `ruangan` | Tidak | string, maks 30 |
| `is_active` | Tidak | boolean, default `true` |
| `catatan` | Tidak | string |
| `assistant_ids` | Tidak | array id asisten, cth `[1,2]` (alias `assistants` juga diterima) |

```bash
curl -X POST -H "X-API-KEY: sk_xxxx" -H "Content-Type: application/json" \
  -d '{"course_id":1,"angkatan":"2025","program":"Reguler","kelas":"TI.25.A.9",
       "hari":"Sabtu","waktu_mulai":"08.00","waktu_selesai":"10.00",
       "dosen":"Dosen API","ruangan":"Lab 9","assistant_ids":[1,2]}' \
  https://jadwal.contoh.id/api/v1/schedules
```

Respons: `201 Created` + `{ "data": {...}, "message": "Jadwal berhasil ditambahkan." }`.

### 4.4 Ubah — `PUT /schedules/{id}`

Semua field **opsional** (hanya yang dikirim yang diubah). Kirim `assistant_ids: [...]` (boleh kosong `[]`) untuk ganti daftar asisten; kalau tidak dikirim, asisten tidak berubah.

```bash
curl -X PUT -H "X-API-KEY: sk_xxxx" -H "Content-Type: application/json" \
  -d '{"ruangan":"Lab 10"}' \
  https://jadwal.contoh.id/api/v1/schedules/14
```

### 4.5 Hapus — `DELETE /schedules/{id}`

```bash
curl -X DELETE -H "X-API-KEY: sk_xxxx" \
  https://jadwal.contoh.id/api/v1/schedules/14
```

## 5. Courses (Mata Kuliah)

| Method | Endpoint | Body |
| ------ | -------- | ---- |
| `GET` | `/courses?search=...&per_page=...` | — (termasuk `schedules_count`) |
| `GET` | `/courses/{id}` | — |
| `POST` | `/courses` | `name*` (maks 255), `code*` (maks 20), `color*` (hex, maks 7, cth `#6366F1`), `semester` (opsional, maks 50) |
| `PUT` | `/courses/{id}` | Semua opsional |
| `DELETE` | `/courses/{id}` | — |

> Matkul yang **masih punya jadwal akan ditolak** (`422`) — hapus jadwalnya dulu.

```bash
curl -X POST -H "X-API-KEY: sk_xxxx" -H "Content-Type: application/json" \
  -d '{"name":"Kecerdasan Buatan","code":"AI","color":"#10B981","semester":"Ganjil 2025/2026"}' \
  https://jadwal.contoh.id/api/v1/courses
```

## 6. Assistants (Asisten)

| Method | Endpoint | Body |
| ------ | -------- | ---- |
| `GET` | `/assistants?search=...&per_page=...` | — (cari by nama/nim, termasuk `schedules_count`) |
| `GET` | `/assistants/{id}` | — |
| `POST` | `/assistants` | `name*` (maks 255), `nim` (opsional, maks 20), `phone` (opsional, maks 20) |
| `PUT` | `/assistants/{id}` | Semua opsional |
| `DELETE` | `/assistants/{id}` | — |

```bash
curl -X POST -H "X-API-KEY: sk_xxxx" -H "Content-Type: application/json" \
  -d '{"name":"Budi Santoso","nim":"23.01.010"}' \
  https://jadwal.contoh.id/api/v1/assistants
```

## 7. Catatan

- **Rate limit:** 60 request/menit per IP.
- **Versioning:** prefix `/v1`. Perubahan yang merusak akan naik ke `/v2`, `/v1` tetap jalan.
- **Waktu** disimpan sebagai string format `HH.MM` (titik, bukan titik dua) mengikuti konvensi yang dipakai aplikasi.
- Tanda `*` = field wajib.
