# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## MORTYX — Official Apparel E-Commerce Platform

| Metadata | Keterangan |
| :--- | :--- |
| **Nama Proyek** | MORTYX E-Commerce Platform |
| **Kategori Produk** | Fashion / Streetwear & Contemporary Apparel |
| **Versi Dokumen** | v1.0.0 (Baseline Architecture) |
| **Status Dokumen** | Proposed / Review |
| **Tech Stack Acuan** | PHP 8.1+, MySQL 8.0, HTML5, Vanilla JavaScript, Tailwind CSS |

---

## 1. Executive Summary & Brand Identity

### 1.1 Visi Produk
**MORTYX** adalah brand pakaian kontemporer yang menggabungkan estetika *dark-streetwear*, material tekstil berdensitas tinggi (heavyweight cotton 280–400 GSM), dan budaya *drop-culture* eksklusif. 

Platform e-commerce MORTYX dibangun untuk menghadirkan pengalaman belanja online yang setara dengan brand fashion internasional (seperti Represent, Essentials, Undercover): **cepat, monokromatik, minimalis, dan tanpa gesekan (*frictionless*)**, baik di perangkat desktop maupun ponsel pintar (*mobile-first*).

### 1.2 Tujuan Bisnis (Business Objectives)
1. **Direct-to-Consumer (D2C)**: Membangun kanal penjualan mandiri tanpa ketergantungan 100% pada pihak ketiga (marketplace), sehingga profit margin maksimal dan kontrol brand image terjaga utuh.
2. **Drop Management**: Mampu menangani lonjakan pengunjung saat perilisan koleksi musiman (*batch drops*) tanpa kendala *server down* atau *race condition* stok.
3. **Conversion Rate Tinggi**: Mempersingkat waktu dari membuka katalog hingga pembayaran sukses (*Cart-to-Payment flow*) di bawah 90 detik.

### 1.3 Key Performance Indicators (KPIs)
* **Waktu Muat Halaman (Page Speed)**: LCP < 1.8 detik pada jaringan 4G.
* **Cart Abandonment Rate**: Di bawah 45% berkat alur *one-page checkout* dan transparansi ongkir.
* **Akurasi Stok (Stock Integrity)**: 0% *overselling* (tidak pernah menjual barang melebihi stok fisik riil).

---

## 2. Target Audience & User Personas

### 2.1 Persona 1: The Drop Hunter (Rian, 22 Tahun)
* **Profil**: Mahasiswa / Content Creator, pecinta streetwear, selalu menunggu rilis batch terbaru di Instagram.
* **Karakteristik Belanja**: Membuka website lewat smartphone, mencari produk *best-seller* atau *limited release*, ingin proses checkout yang instan sebelum ukuran yang diinginkan habis.
* **Pain Points**: Frustrasi jika website lemot, varian ukuran tidak jelas, atau tiba-tiba pesanan dibatalkan karena stok minus (*oversell*).

### 2.2 Persona 2: The Conscious Apparel Buyer (Dimas, 28 Tahun)
* **Profil**: Profesional muda, menyukai kaos basic polos berbobot berat (*boxy cut*), mementingkan detail gramasi kain dan petunjuk ukuran.
* **Karakteristik Belanja**: Membaca spesifikasi kain (GSM, rib collar), memeriksa *Size Chart* secara teliti, memilih opsi ekspedisi tepercaya.
* **Pain Points**: Ragu membeli jika tidak ada panduan ukuran (*size chart modal*) dan detail foto tekstil asli.

### 2.3 Persona 3: Store Operations / Admin (Siti, Store Manager)
* **Profil**: Penanggung jawab pemenuhan pesanan (*fulfillment*), inventaris gudang, dan pencatatan resi.
* **Kebutuhan**: Dashboard yang jelas untuk melihat pesanan masuk berstatus `PAID`, mencetak label alamat, menginput nomor resi (*tracking number*), dan memantau stok kritis (*low stock alert*).
* **Pain Points**: Bingung jika status pembayaran tidak otomatis terhubung dengan stok gudang.

---

## 3. Analisis Masalah & Solusi Produk

| No | Masalah Utama E-Commerce Fashion | Solusi pada Platform MORTYX |
| :--- | :--- | :--- |
| 1 | **Salah Ukuran (Size Returns)**: Tingginya angka retur karena pembeli salah pilih ukuran. | Fitur **Interactive Size Chart Modal** dengan panduan perbandingan cm dan rekomendasi tinggi/berat badan. |
| 2 | **Overselling saat Rebutan Stok**: Dua pembeli memesan 1 sisa barang di detik yang sama. | Mekanisme **Atomic Stock Reservation**: Stok dikunci (*locked*) secara database transaction saat checkout dibuat dengan batas waktu bayar (misal 30 menit). |
| 3 | **Checkout Rumit**: Formulir panjang dan kewajiban register yang memicu pembatalan. | Mendukung **Guest Checkout** (cukup isi data pengiriman dan kontak) dengan opsi menyimpan akun opsional. |
| 4 | **Transparansi Biaya**: Pembeli kaget dengan ongkos kirim di tahap akhir. | **Free Shipping Progress Tracker** interaktif di Cart Drawer (misal: "Tambah Rp 75.000 lagi untuk Bebas Ongkir"). |

---

## 4. Model Bisnis & Rilis Koleksi

1. **Sistem Rilis Musiman (*Seasonal Drop Batch*)**:
   - Produk dirilis dalam bentuk *Drop* (contoh: *Drop 04: Distorted Reality*).
   - Memiliki badge status produk: `LIMITED DROP`, `BEST SELLER`, `NEW ARRIVAL`, `SOLD OUT`.
2. **Struktur Diskon & Insentif**:
   - Ambang batas Bebas Ongkir (Free Domestic Shipping) untuk pesanan di atas nominal tertentu (default: Rp 500.000).
   - Diskon pendaftaran newsletter/email pertama (10% off voucher code).

---

## 5. Ruang Lingkup Fitur (MoSCoW Matrix)

### 5.1 Must-Have (P0 — Prioritas Mutlak)
* [x] **Katalog Produk & Tampilan Koleksi**: Grid responsif, dual-image hover (foto model vs foto flat lay), filter kategori.
* [x] **Detail Produk & Varian (PDP / Quick View)**: Pilihan ukuran (S, M, L, XL, XXL) dan warna dengan stok dinamis per SKU.
* [x] **Keranjang Belanja (Slide-Over Bag)**: Update kuantitas real-time, hapus item, kalkulasi subtotal otomatis, penyimpanan persisten di browser.
* [ ] **Alur Checkout Terstruktur**: Input data penerima, alamat lengkap (Provinsi, Kota, Kecamatan, Kode Pos), dan opsi pengiriman.
* [ ] **Integrasi Pembayaran Otomatis**: Simulasi/Payment Gateway (QRIS, Virtual Account BCA/Mandiri/BRI) dengan status pembayaran otomatis.
* [ ] **Pengurangan Stok Otomatis (Stock Locking)**: Validasi stok tingkat basis data dengan status transaksi atomik.
* [ ] **Dashboard Admin Operasional**: CRUD produk, kelola kuota stok per varian, update status pesanan dan nomor resi.

### 5.2 Should-Have (P1 — Sangat Penting)
* [ ] **Guest Checkout**: Pembeli dapat bertransaksi cepat tanpa perlu membuat akun / kata sandi.
* [ ] **Kalkulator Ongkos Kirim**: Estimasi biaya kurir (JNE, SiCepat, J&T) berbasis wilayah.
* [ ] **Halaman Pelacakan Pesanan (*Track Order*)**: Cukup input Nomor Pesanan + Email / No HP untuk melihat status pengiriman.
* [ ] **Size Guide Modal**: Pop-up interaktif panduan ukuran panjang $\times$ lebar baju.

### 5.3 Could-Have (P2 — Nilai Tambah)
* [ ] **Drop Countdown Timer**: Penghitung mundur di header/banner untuk produk yang akan datang (*Upcoming Drops*).
* [ ] **Customer Portal (Akun Pembeli)**: Riwayat order, buku alamat tersimpan, dan wishlist koleksi favorit.
* [ ] **Notifikasi WhatsApp / Email**: Pengiriman invoice otomatis ke email atau WhatsApp pembeli.

### 5.4 Won't-Have (P3 — Ditunda untuk Fase Berikutnya)
* Pengiriman Internasional (Cross-border multi-currency).
* Sistem Multi-Vendor / Marketplace.
* AR Virtual Fitting Room.

---

## 6. Roadmap Pengembangan Sistem

```
Fase 1: Fondasi Arsitektur, PRD, SRS, & UML Sign-Off (Current Milestone)
   │
   ├── Fase 2: Skema Basis Data Relasional & Seeding Data Varian
   │
   ├── Fase 3: Modul Katalog, Quick Spec Modal, & State Keranjang
   │
   ├── Fase 4: Modul Checkout, Ekspedisi, & Payment Gateway Handler
   │
   ├── Fase 5: Modul Admin Dashboard (Katalog, Stok, & Manajemen Resi)
   │
   └── Fase 6: Testing End-to-End, Automated CI Pipeline, & Deployment
```
