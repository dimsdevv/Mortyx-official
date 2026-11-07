# MORTYX APPAREL — PHASE 3 TECHNICAL SPECIFICATION
## Feature: Authentication & Customer Portal (`feature/auth-system`)

**Status**: 🟡 IN PROGRESS (Branch Active)  
**Branch**: `feature/auth-system`  
**Base Branch**: `main` (`commit 0d4c67b`)  
**Standard Compliance**: SRS (FR-AUT-01 s/d FR-AUT-05, NFR-SEC-01 s/d NFR-SEC-04), PRD, `codebase-design`, `anti-slop`, `impeccable`  
**Date Created**: 2026-11-07  

---

## 1. Executive Summary & Objective

Phase 3 mengimplementasikan sistem keanggotaan (membership) dan portal pelanggan untuk brand *MORTYX*. Sistem ini bertujuan untuk:
1. Menyediakan jalur autentikasi aman bagi pelanggan (Registrasi, Login, Logout).
2. Melindungi endpoint privat (Customer Dashboard, Order History) menggunakan session guard.
3. Menjamin standar keamanan enterprise (Bcrypt cost 12+, CSRF token per session, Session Fixation protection, Prepared Statements PDO).
4. Menghadirkan antarmuka pengguna bergaya *raw luxury streetwear* tanpa visual slop (Zero AI Slop) dengan micro-interactions yang mulus.

---

## 2. Arsitektur Modul (`codebase-design` Deep Module)

Sesuai panduan arsitektur *Deep Modules* (`mattpocock/skills`), seluruh kompleksitas enkripsi, session, dan validasi database disembunyikan di balik antarmuka sempit (*narrow interface*) class `Auth`:

### 2.1 File: `includes/Auth.php`
```php
class Auth {
    // Session & Initialization
    public static function init(): void;
    
    // User Authentication
    public static function register(string $name, string $email, string $password, string $phone = ''): array;
    public static function login(string $email, string $password): array;
    public static function logout(): void;
    
    // State & Identity Check
    public static function check(): bool;
    public static function user(): ?array;
    public static function id(): ?int;
    public static function role(): ?string;
    
    // Middleware & Guards
    public static function requireAuth(string $redirectTo = 'auth/login.php'): void;
    public static function requireGuest(string $redirectTo = 'customer/account.php'): void;
    public static function requireRole(string $role, string $redirectTo = 'index.php'): void;
    
    // CSRF Protection
    public static function csrfToken(): string;
    public static function validateCsrf(?string $token): bool;
}
```

### 2.2 Security Hardening Spec
1. **Password Hashing**: `password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])`.
2. **Session Regeneration**: Panggilan `session_regenerate_id(true)` saat proses login berhasil untuk mitigasi serangan *Session Fixation*.
3. **Anti-SQLi**: 100% database query menggunakan PDO prepared statements (`$stmt->prepare()` & `$stmt->execute()`).
4. **Anti-CSRF**: Token di-generate secara *cryptographically secure* (`bin2hex(random_bytes(32))`) dan divalidasi menggunakan fungsi *constant-time comparison* `hash_equals()`.
5. **Anti-XSS**: Seluruh output data pengguna (nama, email) disanitasi menggunakan `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.

---

## 3. Komponen Antarmuka & Frontend

Mengikuti panduan `anti-slop`, `impeccable`, dan `minimalist-ui`:
- **Warna**: Background Carbon Black `#080809`, Surface Zinc-900 `#18181b`, Border Zinc-800, Text Off-White `#f4f4f5`.
- **Tipografi**: Heading *Syne* (uppercase, tracking-tighter), Body *Plus Jakarta Sans*.
- **Anti-Slop Rules**: Tanpa gradien ungu/indigo murahan, tanpa rounded pill berlebih pada form input, tanpa copy marketing basi.

### 3.1 Halaman yang Dibuat / Dimodifikasi:
1. `auth/register.php`: Form registrasi (Full Name, Email, Password min 8 chars, Phone number).
2. `auth/login.php`: Form login aman (Email, Password, CSRF token, auto-redirect setelah sukses).
3. `auth/logout.php`: Penghancuran session aman dan redirect ke landing page.
4. `customer/account.php`: Portal dashboard akun member (Informasi profil, status keanggotaan, riwayat pesanan).
5. `includes/header.php`: Navigasi dinamis menampilkan nama member / tombol akun saat login, atau link "ACCOUNT" saat guest.

---

## 4. Rencana Pengujian Otomatis (`tests/test_auth.php`)

Automated test runner menggunakan SQLite in-memory untuk memverifikasi fungsionalitas secara headless:
1. **CSRF Generation**: Memastikan token CSRF terbuat dengan panjang 64 karakter hex.
2. **CSRF Validation**: Token valid lolos, token salah/kosong ditolak.
3. **User Registration**:
   - Registrasi user baru berhasil dan mengembalikan user ID.
   - Password tersimpan dalam format hash bcrypt valid ($2y$12$).
   - Password plaintext tidak pernah tersimpan di database.
4. **Unique Email Constraint**: Registrasi dengan email yang sama harus gagal dan menghasilkan error terstruktur.
5. **Login Authentication**:
   - Login dengan kredensial benar berhasil mengembalikan user data.
   - Login dengan password salah ditolak.
   - Login dengan email tidak terdaftar ditolak.
6. **Session & Guards**:
   - `Auth::check()` menghasilkan `false` saat belum login, dan `true` setelah login.
   - `Auth::logout()` membersihkan seluruh session data.

---

## 5. Kriteria Selesai (Definition of Done)

- [ ] Seluruh file PHP lolos syntax check (`php -l`).
- [ ] Modul `includes/Auth.php` selesai dan diuji.
- [ ] Form `auth/login.php` dan `auth/register.php` berfungsi dengan validasi error yang jelas.
- [ ] Halaman `customer/account.php` terproteksi auth guard.
- [ ] Test suite `tests/test_auth.php` 100% PASS (🟢 GREEN).
- [ ] Seluruh perubahan dicommit secara atomic dengan konvensi conventional commits.
- [ ] Branch `feature/auth-system` di-push ke remote GitHub.
- [ ] PR dibuat, direview, dan dimerge ke branch `main`.
