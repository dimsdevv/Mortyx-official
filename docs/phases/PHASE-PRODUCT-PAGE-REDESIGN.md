# MORTYX APPAREL — PRODUCT PAGE REDESIGN SPECIFICATION
## Feature: CALLE-Inspired Product Page with Enhanced UX (`feature/product-page-redesign`)

**Status**: 🟡 IN PROGRESS (Branch Active)  
**Branch**: `feature/product-page-redesign`  
**Base Branch**: `main` (`commit 54bd4b8`)  
**Design Standard**: `minimalist-ui`, `anti-slop`, `impeccable`, `emil-design-eng`  
**Date Created**: 2026-11-08  

---

## 1. Executive Summary & Objective

Merombak halaman `catalog.php` menjadi halaman produk profesional dengan tata letak sidebar + grid yang terinspirasi dari website **CALLE DE LARACHE**, ditambah komponen-komponen UX tambahan untuk kenyamanan pembeli.

### Perubahan Inti:
1. **Layout Sidebar + Product Grid** (referensi CALLE):
   - Sidebar kiri dengan filter: Search, Product Type, Availability, Price Range, Size
   - Grid produk 3 kolom di area konten utama
   - Sort dropdown di header grid (sort: Featured)
   - Heading "All Products" yang bersih

2. **Komponen UX Tambahan untuk Kenyamanan Pembeli**:
   - **Real-time Search**: Input pencarian live di sidebar dengan ikon kaca pembesar
   - **Product Type Filter**: Radio button untuk All Products / Featured Products
   - **Availability Filter**: Radio button All / In Stock only
   - **Price Range Filter**: Radio button range harga dalam Rupiah
   - **Size Selector**: Pill button S/M/L/XL di sidebar
   - **Sort Dropdown**: Featured, Price Low-High, Price High-Low, Top Rated
   - **Breadcrumb Navigation**: Home > Catalog trail
   - **Product Count Indicator**: "Showing X of Y products"
   - **Smooth Hover Effects**: Image swap dan quick view button on hover
   - **Collapsible Filter Sections**: Accordion-style sidebar sections

3. **Proteksi Integritas Kode**:
   - Tidak merusak Quick View modal, Cart Drawer, atau fitur interaktif lain
   - Semua automated tests (66/66) tetap PASS
   - `catalog.js` di-upgrade secara backward-compatible

---

## 2. Arsitektur Komponen

### File yang Dimodifikasi:
| File | Perubahan |
|---|---|
| `catalog.php` | Full redesign: sidebar + grid layout |
| `assets/js/catalog.js` | Tambah logika filter sidebar (price, size, availability, search) |

### File yang Tidak Disentuh:
- `includes/header.php` — Tetap utuh
- `includes/footer.php` — Tetap utuh
- `includes/cart_drawer.php` — Tetap utuh
- `data/products.php` — Tetap utuh
- `index.php` — Tetap utuh

---

## 3. Layout Reference (CALLE DE LARACHE Style)

```
┌─────────────────────────────────────────────────────────┐
│  MORTYX    CATALOG  COMMUNITY  ARCHIVES  ABOUT  IDR CART│
├──────────┬──────────────────────────────────────────────┤
│ Sidebar  │  All Products            sort: Featured ▼   │
│          │                                              │
│ 🔍Search │  ┌──────┐  ┌──────┐  ┌──────┐              │
│          │  │      │  │      │  │      │              │
│ ▼Type    │  │ IMG  │  │ IMG  │  │ IMG  │              │
│ ○ All    │  │      │  │      │  │      │              │
│ ○ Feat   │  └──────┘  └──────┘  └──────┘              │
│          │  NAME       NAME       NAME                 │
│ ▼Avail.  │  Rp XXX     Rp XXX     Rp XXX              │
│ ○ All    │                                              │
│ ○ Stock  │  ┌──────┐  ┌──────┐  ┌──────┐              │
│          │  │      │  │      │  │      │              │
│ ▼Price   │  │ IMG  │  │ IMG  │  │ IMG  │              │
│ ○ <500K  │  │      │  │      │  │      │              │
│ ○ 500-   │  └──────┘  └──────┘  └──────┘              │
│          │  NAME       NAME       NAME                 │
│ ▼Size    │  Rp XXX     Rp XXX     Rp XXX              │
│[S][M][L] │                                              │
└──────────┴──────────────────────────────────────────────┘
```

---

## 4. Automated Testing Requirement

Sebelum merge ke `main`, seluruh suite berikut harus hijau:
- `tests/test_runner.php` → 15 tests
- `tests/test_database.php` → 26 tests
- `tests/test_auth.php` → 25 tests

**Total: 66/66 PASS required.**

