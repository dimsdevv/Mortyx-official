# MORTYX APPAREL — UI REDESIGN TECHNICAL SPECIFICATION
## Reference: CALLE DE LARACHE Minimalist Aesthetic (`feature/minimalist-ui-redesign`)

**Status**: 🟢 MERGED (PR #4)  
**Branch**: `feature/minimalist-ui-redesign`  
**Base Branch**: `main` (`commit 9f0bc42`)  
**Design Reference**: [CALLE DE LARACHE](https://www.calledelarache.com/)  
**Standard Compliance**: `minimalist-ui`, `anti-slop`, `impeccable`, SRS (FR-CAT, FR-AUT)  
**Date Created**: 2026-11-08  

---

## 1. Analisis & Sasaran Desain

Berdasarkan referensi visual dan website resmi **CALLE DE LARACHE**, tampilan brand MORTYX dirombak dari tema sebelumnya (*dark brutalist cyberpunk* yang padat komponen floating) menjadi estetika **Contemporary Minimalist Streetwear** yang bersih, berkelas, lapang (*generous whitespace*), dan tidak bising (*zero noise / zero AI slop*).

### Karakteristik Visual CALLE DE LARACHE:
1. **Clean White / Light Canvas**: Menggunakan latar belakang putih bersih (`#ffffff` / `#fafafa`) dengan pemisah garis ultra-tipis (`#e5e7eb` / `#eaeaea`).
2. **Bold Monochromatic Header**:
   - Logo brand sebelah kiri: **MORTYX** dengan bobot *black/heavy bold*, kapital, tegas.
   - Navigasi utama berada di tengah: `CATALOG`, `COMMUNITY`, `ARCHIVES`, `ABOUT` dengan tipografi bersih, sans-serif, huruf kapital elegan.
   - Kontrol utilitas kanan: Indikator mata uang (`🇮🇩 IDR`), trigger `CART`, dan ikon profil/akun minimalist outline.
3. **Editorial Hero Section**:
   - Menghilangkan kotak-kotak spek floating yang terlalu ramai, teks bertumpuk, dan badge neon berkedip.
   - Menggunakan foto editorial sinematik beresolusi tinggi yang bersih dan bercerita.
   - Satu tombol Call-To-Action (CTA) minimalis yang elegan: `SHOP HERE` (translucent dark rounded container dengan teks putih bold).
4. **Minimalist Product Catalog**:
   - Filter kategori bergaya tab minimalis tanpa warna-warni kontras tinggi.
   - Kartu produk berfokus pada visual pakaian (rasio 3:4 atau 4:5), efek hover transisi foto model/detail yang mulus.
   - Informasi produk di bawah gambar disajikan bersih: Nama produk (kapital, rapi) dan Harga (`IDR xxx.xxx` / `Rp xxx.xxx`), tanpa bintang rating atau label diskon yang mengotori estetika.
5. **Drawer & Modal Polish**:
   - Slide-over Cart Drawer bergaya putih minimalis dengan aksen hitam kontras tinggi.
   - Quick Spec modal yang ringkas dan fokus pada pemilihan ukuran (Size Selector) dan Add to Cart.

---

## 2. Token Desain & Palet Warna

| Elemen | Token Nilai | Keterangan |
|---|---|---|
| **Canvas / Background** | `#ffffff` / `#fafafa` | Latar putih bersih / ultra-light off-white |
| **Card Surface** | `#ffffff` | Kontainer produk bersih |
| **Primary Text** | `#111827` (Zinc-900) | Teks utama tajam dan terbaca |
| **Secondary Text** | `#6b7280` (Zinc-500) | Meta info, kategori, placeholder |
| **Borders & Dividers** | `#e5e7eb` (Zinc-200) | Garis 1px ultra-ringan |
| **CTA Primary Button** | `#000000` (Hover: `#27272a`) | Tombol solid hitam teks putih |
| **Heading Font** | `Syne, sans-serif` | Brand mark & display hero |
| **Body / Nav Font** | `Plus Jakarta Sans, sans-serif` | Navigasi, katalog, form input |

---

## 3. Komponen yang Disesuaikan

1. `includes/header.php`:
   - Header putih sticky dengan border-b tipis.
   - Brand logo bold MORTYX.
   - Navigasi tengah `CATALOG`, `COLLECTIONS`, `LOOKBOOK`, `ABOUT`.
   - Right utility: `🇮🇩 IDR`, `CART`, Account avatar/portal trigger.
2. `index.php`:
   - Hero banner editorial sinematik dengan tombol `SHOP HERE`.
   - Grid katalog produk minimalis (3-4 kolom pada desktop).
   - Lookbook editorial monokromatik.
3. `includes/cart_drawer.php`:
   - Latar putih dengan aksen border zinc-200 dan tombol hitam pekat.
4. `includes/footer.php`:
   - Footer minimalis elegan bertema monokrom cerah/netral.
5. `auth/login.php` & `auth/register.php` & `customer/account.php`:
   - Desain form otentikasi dan akun pelanggan yang selaras dengan palet minimalis.

---

## 4. Verifikasi & Pengujian

- Semua 66 automated tests (`tests/test_runner.php`, `tests/test_database.php`, `tests/test_auth.php`) tetap berstatus 🟢 **PASS**.
- Sintaks PHP divalidasi dengan `php -l` di setiap file.
