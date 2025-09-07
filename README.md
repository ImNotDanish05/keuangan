## Keuangan — Personal Finance Tracker (Laravel 11)

Keuangan adalah aplikasi pelacak pemasukan dan pengeluaran dengan antarmuka dashboard, ekspor CSV, dan manajemen pengguna berbasis role (owner/admin/user) + approval. Aplikasi ini menggunakan Blade + Tailwind (Argon Dashboard), Chart.js, dan MySQL.

### Fitur Utama

- Dashboard ringkasan bulan: chart pemasukan/pengeluaran, tren saldo, detail harian.
- CRUD Pemasukan (Incomes) dan Pengeluaran (Expenses), dengan kategori.
- Manajemen Kategori (income/expense).
- Ekspor laporan CSV per bulan/tahun.
- Manajemen User: create/edit/delete, approve/unapprove, pembatasan ketat untuk role admin vs owner.
- Login memakai username; hanya user `approved` dan berole `owner|admin` yang dapat mengakses aplikasi.

### Teknologi

- Laravel 11, PHP 8.2+
- MySQL/MariaDB
- Blade + Argon Dashboard Tailwind (tanpa Vite; asset statis di `public/assets`)
- Chart.js 4 untuk visualisasi

---

## Instalasi

1) Persyaratan

- PHP 8.2+
- Composer
- MySQL/MariaDB

2) Setup Proyek

```bash
cp .env.example .env
# sesuaikan DB_* pada .env

composer install
php artisan key:generate
php artisan migrate --seed
```

Seeder akan membuat akun owner default:

- Username: `root`
- Password: `Admin#1234`

Silakan login dan ganti password segera setelah masuk.

3) Menjalankan Aplikasi

```bash
php artisan serve
# buka http://127.0.0.1:8000
```

Jika menggunakan Laragon/Valet, arahkan virtual host ke folder `public`.

---

## Cara Pakai Singkat

1) Login sebagai owner/admin (harus `is_approved = true`).
2) Dashboard menampilkan ringkasan bulan aktif (pilih bulan/tahun di form atas).
3) Kelola data:
   - Pengeluaran: menu “Pengeluaran”.
   - Pemasukan: menu “Pemasukan”.
   - Kategori: menu “Kategori”.
4) Ekspor CSV:
   - Tombol “Export CSV” di sidebar/dashboard, atau akses route `reports.export?year=YYYY&month=M`.
5) Manajemen User (hanya owner/admin): menu “Users”.
   - Admin dapat membuat user dengan role `user`/`admin` saja.
   - Admin tidak bisa mengedit/hapus user `owner`, dan tidak bisa mempromosikan menjadi `owner`.
   - Owner boleh mengatur semua hal, tetapi tidak bisa menghapus dirinya sendiri dan tidak boleh menurunkan rolenya sendiri hingga kehilangan akses.
   - Toggle Approve/Unapprove tersedia di daftar user dan halaman edit.

Catatan: Pengguna yang registrasi dari halaman Register akan dibuat sebagai `role=user` dan `is_approved=false`. Mereka tidak dapat login sampai disetujui dan/atau dipromosikan oleh owner/admin sesuai kebijakan akses.

---

## Struktur Penting

- Layout utama: `resources/views/layouts/app.blade.php` (memuat `public/argon` dan `public/assets`).
- Modul Users:
  - Controller: `app/Http/Controllers/UserManagementController.php`
  - Requests: `app/Http/Requests/UserStoreRequest.php`, `app/Http/Requests/UserUpdateRequest.php`
  - Policy: `app/Policies/UserPolicy.php` (didaftrakan di `AppServiceProvider`)
  - Routes: `routes/web.php` (dalam grup `middleware(['auth','adminonly'])`)
  - Views: `resources/views/users/` (index, create, edit)

---

## FAQ

- Tidak bisa login setelah register?
  - Akun baru `role=user` dan `is_approved=false`. Minta owner/admin menyetujui dan/atau mengubah role Anda.

- Saya admin dan tidak bisa mengubah user owner.
  - Sesuai kebijakan, hanya owner yang bisa mengubah owner atau mempromosikan ke owner.

- Aset tidak tampil saat serve?
  - Pastikan base URL benar dan server mengarah ke folder `public`.

---

## Lisensi

Kode dirilis di bawah lisensi MIT.
