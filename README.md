# Jadwal Lab — Sistem Informasi Jadwal Praktikum

Aplikasi web untuk mengelola dan mempublikasikan jadwal praktikum Laboratorium Informatika.
Halaman publik menampilkan jadwal yang dikelompokkan per hari beserta dosen, ruangan, dan
asisten pendamping. Panel admin menyediakan CRUD untuk jadwal, mata kuliah, asisten, dan
pengelolaan API key untuk akses REST API.

Dikembangkan untuk lingkungan Universitas Pelita Bangsa.

## Fitur

**Halaman publik**

- Daftar jadwal dikelompokkan per hari (Senin–Minggu), terurut sesuai urutan hari
- Filter berdasarkan mata kuliah dan pencarian langsung (nama kelas, dosen, ruangan, asisten)
- Ringkasan otomatis: jumlah kelas, asisten, dan ruangan
- Tampilan responsif untuk desktop dan perangkat seluler

**Panel admin** (`/admin`)

- Dashboard berisi statistik dan lima jadwal terbaru
- CRUD jadwal (kelas, angkatan, program Reguler/Ekstensi, hari, jam, dosen, ruangan, status aktif, catatan, penugasan asisten)
- CRUD mata kuliah (dengan proteksi hapus jika masih memiliki jadwal) dan asisten
- Pengelolaan API key: buat banyak key per aplikasi, revoke, dan pantau pemakaian terakhir
- Dialog konfirmasi hapus dan notifikasi menggunakan SweetAlert2

**REST API** (`/api/v1`, dokumentasi lengkap di [`API.md`](API.md))

- CRUD penuh untuk `schedules`, `courses`, dan `assistants`
- Autentikasi API key lewat header `X-API-KEY` atau `Authorization: Bearer`
- Filter, pencarian, pagination, dan pemuatan relasi (`course`, `assistants`)
- Rate limit 60 request per menit, respons error selalu dalam format JSON

## Teknologi

| Lapisan   | Teknologi                              |
| --------- | -------------------------------------- |
| Backend   | Laravel 12, PHP ^8.2, Eloquent ORM     |
| Frontend  | Blade, Vite, Tailwind CSS v4, Phosphor Icons, SweetAlert2 (CDN) |
| Database  | SQLite (pengembangan), MySQL (produksi) |
| Testing   | PHPUnit                                |

## Kebutuhan Sistem

- PHP ^8.2 dengan ekstensi umum Laravel (mbstring, sqlite/mysql, dsb.)
- Composer 2
- Node.js 18+ dan npm (untuk membangun aset frontend)

## Instalasi Lokal

```bash
# 1. Install dependensi
composer install
npm install

# 2. Siapkan environment
cp .env.example .env
php artisan key:generate

# 3. Siapkan database (default SQLite)
touch database/database.sqlite
php artisan migrate --seed

# 4. Jalankan server pengembangan (server + queue + log + vite)
composer dev
```

Buka `http://localhost:8000` untuk halaman publik dan
`http://localhost:8000/admin` untuk panel admin.

Untuk produksi dengan MySQL, sesuaikan variabel `DB_*` di `.env`
(seperti `DB_CONNECTION=mysql`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`),
lalu jalankan `php artisan migrate --force`.

## Akun Bawaan (Seeder)

Seeder mengisi satu akun admin, dua mata kuliah contoh, enam asisten,
dan sepuluh jadwal contoh:

- Email: `pelitabangsalab@gmail.com`
- Password: sesuai yang didefinisikan di `database/seeders/DatabaseSeeder.php`

Ganti kredensial tersebut segera setelah instalasi di lingkungan produksi,
misalnya lewat `php artisan tinker`:

```php
use App\Models\User;
User::where('email', 'pelitabangsalab@gmail.com')->first()
    ->update(['password' => 'password-baru-yang-kuat']);
```

## REST API

Base URL: `http(s)://<host>/api/v1`. Semua endpoint membutuhkan API key
yang dibuat melalui **Admin → API Keys**.

```bash
curl -H "X-API-KEY: sk_xxxx" https://jadwal.contoh.id/api/v1/schedules?hari=Senin
```

Lihat [`API.md`](API.md) untuk referensi endpoint, parameter, format respons,
dan contoh kode lengkap.

## Testing

```bash
php artisan test
```

Tes mencakup autentikasi API key (401 tanpa/salah key, revoke),
siklus CRUD jadwal, filter, dan proteksi hapus mata kuliah.

## Deploy ke cPanel (tanpa Git)

1. Arsipkan file-file yang berubah (lihat riwayat commit) menjadi satu zip
   dengan struktur folder utuh, tanpa menyertakan `vendor/`, `.env`,
   `node_modules/`, dan file database lokal
2. Upload zip melalui File Manager, lalu Extract di folder produksi
3. Lewat cPanel Terminal, jalankan:
   ```bash
   php artisan migrate --force
   php artisan view:clear && php artisan config:clear
   ```
4. Jika terjadi galat 500, periksa `storage/logs/laravel.log` dan pastikan
   versi PHP CLI yang dipakai adalah ^8.2

## Struktur Direktori (ringkas)

```
app/Http/Controllers/Api/V1/   # Controller REST API (schedules, courses, assistants)
app/Http/Controllers/Admin/    # Controller panel admin (termasuk ApiKeyController)
app/Http/Middleware/EnsureApiKey.php
app/Http/Resources/            # Resource JSON untuk API
app/Models/                    # Course, Schedule, Assistant, ApiKey, User
database/migrations/           # Skema courses, assistants, schedules, api_keys
resources/views/               # Blade: halaman publik, layout admin, halaman admin
routes/web.php                 # Route publik + admin
routes/api.php                 # Route REST API v1
API.md                         # Dokumentasi API
```

## Lisensi

Kode aplikasi mengikuti lisensi MIT seperti kerangka kerja Laravel.
Logo dan identitas institusi di dalamnya adalah milik masing-masing pemilik.
