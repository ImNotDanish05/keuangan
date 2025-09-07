=== PERANMU ===
Kamu adalah senior Laravel engineer. Tugas: tambahkan modul **Manajemen User** ke aplikasi Laravel 11 + Blade + Breeze + MySQL yang sudah ada.

=== KONTEKS DB (SUDAH ADA) ===
Tabel users punya kolom:
- id (bigint), name (string), email (unique), email_verified_at, password, remember_token, timestamps
- username (unique, string)
- role (enum: owner, admin, user) default 'user'
- is_approved (boolean) default false

Login berbasis username. Aplikasi sudah memakai middleware `auth` + `adminonly` (hanya owner/admin + approved yang bisa akses).

=== ATURAN AKSES (SANGAT PENTING) ===
- Hanya **owner** dan **admin** yang bisa melihat modul Manajemen User.
- **Admin** TIDAK boleh:
  - mengubah role user menjadi **owner**
  - mengedit atau menghapus user **owner**
- **Owner** boleh melakukan semua hal (kecuali tidak boleh menghapus dirinya sendiri).
- Siapapun TIDAK boleh menghapus dirinya sendiri.
- Hanya user dengan `is_approved=true` dan role ∈ {owner, admin} yang bisa login (sudah ada). User baru register: role='user', is_approved=false.

=== FITUR YANG HARUS ADA ===
1) **Index User**  
   - Tabel daftar user (name, username, email, role, is_approved, created_at).  
   - Pencarian (global: name/username/email), filter role (owner/admin/user), filter status approval (approved/pending), pagination 10–20.  
   - Aksi per baris: Edit, Approve/Unapprove (toggle), Delete (konfirmasi).
2) **Create User (Admin Panel)**  
   - Form: name, username, email (opsional/nullable boleh tapi valid email jika diisi), password, role (user|admin|owner*), is_approved.  
   - **Jika yang membuat adalah admin**: batasi role pilihan hanya user|admin (owner tersembunyi).  
   - Password wajib minimal 8, di-hash.  
3) **Edit User**  
   - Boleh ubah: name, username, email (unik), role*, is_approved.  
   - **Admin tidak boleh**:
     - mengedit user owner
     - mempromosikan siapapun menjadi owner
   - **Owner** boleh edit siapa saja (kecuali tidak boleh turunkan dirinya sendiri ke role yang membuatnya kehilangan akses saat submit — validasi tahan).
4) **Approve/Unapprove**  
   - Tombol toggle cepat di index + aksi khusus di Edit.
5) **Delete User**  
   - Soft delete tidak perlu; langsung delete.  
   - Larang: menghapus diri sendiri; admin dilarang hapus owner.
6) **Keamanan & UX**  
   - CSRF, Form Request Validation, Policy/Authorization.  
   - Flash message sukses/gagal.  
   - Gunakan asset template dari `public/assets` (JANGAN @vite).  
   - Layout utama: `resources/views/layouts/app.blade.php` (sudah ada). Tambah menu **Users** di navbar untuk role owner/admin.

=== ARSITEKTUR KODE YANG DIMINTA ===
- Controller: `app/Http/Controllers/UserManagementController.php`
  - index(), create(), store(), edit($user), update($user), destroy($user), toggleApproval($user)
- Requests:
  - `app/Http/Requests/UserStoreRequest.php`
  - `app/Http/Requests/UserUpdateRequest.php`
- Policy: `app/Policies/UserPolicy.php` (pakai Gate/Policy Laravel)
  - rules:
    - viewAny: owner|admin
    - view: owner|admin
    - create: owner|admin (admin tidak boleh create owner)
    - update: owner boleh update siapa saja; admin tidak boleh update owner
    - delete: owner boleh delete siapa saja kecuali dirinya sendiri; admin tidak boleh delete owner dan tidak boleh delete dirinya sendiri
    - promoteToOwner: hanya owner
- Service kecil (opsional): `app/Services/UserService.php` untuk logika set role/approval aman.
- Routes (`routes/web.php`):
  - Dalam grup `middleware(['auth','adminonly'])`:
    - Route::get('/users', ...)->name('users.index');
    - Route::get('/users/create', ...)->name('users.create');
    - Route::post('/users', ...)->name('users.store');
    - Route::get('/users/{user}/edit', ...)->name('users.edit');
    - Route::put('/users/{user}', ...)->name('users.update');
    - Route::delete('/users/{user}', ...)->name('users.destroy');
    - Route::patch('/users/{user}/toggle-approval', ...)->name('users.toggleApproval');
- Views (Blade) di `resources/views/users/`:
  - index.blade.php — tabel + search/filter + pagination + tombol Create.
  - create.blade.php — form create.
  - edit.blade.php — form edit.
- **Semua view** extend `layouts/app.blade.php` dan **pakai asset statis**:
  - `<link rel="stylesheet" href="{{ asset('assets/style.css') }}">`
  - `<script src="{{ asset('assets/app.js') }}" defer></script>`

=== VALIDASI FORM ===
UserStoreRequest:
- name: required, string, max:100
- username: required, string, max:100, unique:users,alpha_dash
- email: nullable|email|max:255|unique:users
- password: required|string|min:8|confirmed
- role: required|in:owner,admin,user (tapi jika current user adalah admin, override: in:admin,user)
- is_approved: boolean
UserUpdateRequest:
- name: required|string|max:100
- username: required|string|max:100|alpha_dash|unique:users,username,{$user->id}
- email: nullable|email|max:255|unique:users,email,{$user->id}
- password: nullable|string|min:8|confirmed
- role: required|in:owner,admin,user (admin tidak boleh set owner; admin tidak boleh edit user owner)
- is_approved: boolean

Tambahkan rule kustom/after hook jika:
- target user adalah owner → batasi aksi bagi admin;
- mencegah user menurunkan rolenya sendiri sehingga kehilangan akses (khusus owner terhadap dirinya sendiri).

=== CONTOH POTONGAN KODE KUNCI (BUATKAN SECARA LENGKAP) ===
-- routes/web.php --
Route::middleware(['auth','adminonly'])->group(function () {
  Route::resource('users', UserManagementController::class)->except(['show']);
  Route::patch('/users/{user}/toggle-approval', [UserManagementController::class, 'toggleApproval'])->name('users.toggleApproval');
});

-- app/Policies/UserPolicy.php --
public function create(User $actor) { return in_array($actor->role, ['owner','admin']); }
public function update(User $actor, User $target) {
  if ($target->role === 'owner' && $actor->role !== 'owner') return false;
  return in_array($actor->role, ['owner','admin']);
}
public function delete(User $actor, User $target) {
  if ($actor->id === $target->id) return false; // tidak boleh hapus diri sendiri
  if ($target->role === 'owner' && $actor->role !== 'owner') return false;
  return in_array($actor->role, ['owner','admin']);
}
public function promoteToOwner(User $actor) { return $actor->role === 'owner'; }

-- app/Http/Controllers/UserManagementController.php --
- index(): authorize('viewAny', User::class); apply search/filter; eager load; paginate.
- create(): authorize('create', User::class); form.
- store(UserStoreRequest $r): authorize('create', User::class);
  - role: jika actor admin dan $r->role==='owner' → abort 403
  - hash password; create
  - flash sukses
- edit(User $user): authorize('update', $user); form
- update(UserUpdateRequest $r, User $user): authorize('update', $user);
  - jika actor admin & target owner → abort 403
  - jika role diubah ke owner → pastikan actor owner
  - update fields; password optional
  - flash sukses
- toggleApproval(User $user): authorize('update', $user);
  - $user->is_approved = !$user->is_approved; save; flash
- destroy(User $user): authorize('delete', $user);
  - larang hapus diri sendiri; delete; flash

-- resources/views/users/index.blade.php --
- Form search (q), filter role (all/owner/admin/user), filter approval (all/approved/pending).
- Tabel: Name, Username, Email, Role (badge), Status (Approved/Pending), Actions [Edit, Toggle Approval, Delete].
- Semua tombol pakai class dari `public/assets` (jangan @vite).
- Konfirmasi JS sebelum delete.

-- resources/views/users/create.blade.php & edit.blade.php --
- Fields: name, username, email, role (select), is_approved (checkbox), password + password_confirmation (optional saat edit).
- Jika actor admin, sembunyikan opsi role 'owner'.
- Tampilkan error per field.
- Tombol Submit & Cancel.

=== INTEGRASI UI/ASSET ===
- Gunakan CSS/JS dari `public/assets` (template dari .note\design_template\build yang sudah dipindah).
- Pastikan layout utama sudah memuat:
  <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
  <script src="{{ asset('assets/app.js') }}" defer></script>

=== ACCEPTANCE CRITERIA (HARUS LOLOS) ===
- Menu **Users** tampil hanya untuk owner/admin.
- Admin:
  - bisa buat user role user/admin; TIDAK bisa buat atau edit ke role owner; TIDAK bisa edit/hapus owner.
- Owner:
  - bisa buat/ubah role siapa pun termasuk set owner; TIDAK bisa menghapus dirinya sendiri.
- Pencarian, filter, pagination berfungsi.
- Toggle approval bekerja (approved/pending) dan memengaruhi kemampuan login.
- Validasi unik username/email, password hashing, CSRF, flash messages tampil.
- Semua halaman memakai layout & asset `public/assets`.

=== BONUS (JIKA MUDAH) ===
- Bulk Approve (checkbox multi user + tombol “Approve Selected” untuk owner).
- Reset Password cepat: generate password baru dan tampilkan sekali (owner only).

Silakan hasilkan: Controller lengkap, Requests, Policy, route, dan 3 view (index/create/edit) yang siap jalan.
