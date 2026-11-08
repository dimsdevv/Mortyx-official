# MORTYX APPAREL — LANDING STREAMLINE SPECIFICATION
## Minimalist Landing Splash & Separate Catalog Route (`feature/landing-streamline`)

**Status**: 🟢 MERGED (PR #5)  
**Branch**: `feature/landing-streamline`  
**Base Branch**: `main` (`commit b25b108`)  
**Design Reference**: [CALLE DE LARACHE](https://www.calledelarache.com/) Homepage Splash Pattern  
**Standard Compliance**: `minimalist-ui`, `anti-slop`, `impeccable`  
**Date Created**: 2026-11-08  

---

## 1. Objective & Scope

Sesuai arahan pengguna untuk menyederhanakan halaman depan agar benar-benar persis seperti CALLE DE LARACHE (*simple, minimalist, tidak ramai*):

1. **Menghapus Top Announcement Bar**:
   - Menghapus bar `"FREE SHIPPING JABODETABEK MIN. IDR 500K | DROP 04 IS LIVE"` dari atas navigation bar agar header menjadi bersih, murni warna putih, langsung menyatu dengan logo dan menu.
2. **Menyederhanakan Halaman Utama (`index.php`)**:
   - Menghapus grid catalog dari halaman beranda (*splash screen*).
   - Menghapus section `COMMUNITY // ARCHIVES SEASON 2026 VISUAL ARCHIVES` dari halaman beranda.
   - Halaman utama menjadi *editorial splash campaign* beresolusi penuh dengan logo, menu navigasi, dan tombol tunggal **`SHOP HERE`**.
3. **Memindahkan & Mempertahankan Fitur Katalog ke `catalog.php`**:
   - Seluruh komponen katalog, filter kategori, pengurutan (sort), pencarian (search), modal quick view, dan keranjang belanja (cart) dipindahkan ke halaman dedicated `catalog.php`.
   - Tombol **`SHOP HERE`** pada halaman utama dan link **`CATALOG`** pada navbar mengarahkan pengunjung langsung ke `catalog.php`.
   - Kode, fitur, dan komponen yang ada tidak dirusak atau dihapus, melainkan ditata secara profesional sesuai pola arsitektur e-commerce kontemporer.

---

## 2. File Changes

- `includes/header.php`: Hapus elemen announcement bar, update tautan menu `CATALOG` ke `catalog.php`.
- `index.php`: Bersihkan section catalog dan community archives; arahkan tombol `SHOP HERE` ke `catalog.php`.
- `catalog.php`: Halaman katalog lengkap dengan interaktivitas filter, search, sort, dan quick view.
- `docs/ROADMAP-PHASES.md`: Tambahkan tracker milestone ini.
