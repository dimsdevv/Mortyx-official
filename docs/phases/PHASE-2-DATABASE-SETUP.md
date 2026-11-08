# MORTYX APPAREL — PHASE 2 TECHNICAL SPECIFICATION & REPORT
## Feature: Relational Database Schema, PDO Manager, Seeder & Tests (`feature/database-setup`)

**Status**: 🟢 COMPLETED & MERGED TO MAIN  
**Branch**: `feature/database-setup` (Merged via PR #2: `commit 0d4c67b`)  
**Base Branch**: `main`  
**Test Suite**: `tests/test_database.php` (26/26 PASS)  

---

## 1. Overview & Scope

Phase 2 merancang arsitektur penyimpanan relasional MySQL InnoDB dengan integritas data referensial ketat (foreign keys, cascade deletes, stock control per SKU ukuran), connection manager PDO yang aman, script data seeder awal, dan pengujian otomatis menggunakan SQLite in-memory.

---

## 2. Deliverables & Implementasi

1. **Skema Relasional MySQL (`database/schema.sql`)**:
   - 7 tabel inti: `users`, `categories`, `products`, `product_variants`, `orders`, `order_items`, `payments`.
   - Foreign key constraints untuk menjaga integritas data.
   - Indexing pada kolom query kritis (`slug`, `email`, `order_number`, `status`).

2. **PDO Connection Manager (`config/database.php`)**:
   - Singleton pattern untuk efisiensi koneksi pool.
   - Dukungan koneksi ganda: MySQL (Production/XAMPP) dan SQLite (Testing).
   - Pengaturan keamanan PDO: `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`, `PDO::ATTR_EMULATE_PREPARES => false`.

3. **Database Seeder (`database/seed.php`)**:
   - 5 kategori produk utama.
   - 6 produk unggulan streetwear MORTYX.
   - 21 varian SKU ukuran (S, M, L, XL, All Size) dengan stok masing-masing.
   - 1 akun admin default (`admin@mortyx.com` / `mortyx2026`) dengan password hash bcrypt.
   - Sintaks SQL portabel berbasis ANSI SQL untuk kompatibilitas lintas database.

4. **Automated Database Test Suite (`tests/test_database.php`)**:
   - 26 unit & integration tests.
   - Verifikasi DDL skema, validasi seeder, keunikan SKU, verifikasi hash bcrypt password admin, kueri JOIN relasional, dan pengujian rollback atomik transaksi (ACID).

