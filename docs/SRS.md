# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)
## MORTYX — Official Apparel E-Commerce Platform

| Dokumen Spesifikasi Rekayasa Perangkat Lunak |
| :--- |
| **Sistem**: MORTYX Apparel E-Commerce Engine |
| **Standar Format**: Mengadaptasi IEEE Std 830-1998 |
| **Versi**: 1.0.0 |
| **Penyusun**: Engineering & Architecture Team |
| **Target Runtime**: XAMPP (Apache 2.4+, PHP 8.1+, MySQL 8.0 / MariaDB 10.4+) |

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen
Dokumen Spesifikasi Kebutuhan Perangkat Lunak (SRS) ini mendefinisikan seluruh kebutuhan fungsional, non-fungsional, batasan arsitektur, dan antarmuka teknis untuk pengembangan platform e-commerce brand pakaian **MORTYX**. Dokumen ini menjadi kontrak teknis tunggal sebelum implementasi modul kode dilakukan.

### 1.2 Ruang Lingkup Sistem
Platform ini mengelola alur transaksi pakaian secara digital dari hulu ke hilir:
* Penyajian katalog berbasis batch rilis dengan dukungan multi-gambar dan kurasi visual.
* Manajemen stok presisi per varian produk (kombinasi ukuran dan warna).
* Alur keranjang belanja persisten sisi klien (*client-side state*) yang tersinkronisasi.
* Pemrosesan transaksi checkout (Guest Checkout maupun Registered User).
* Integrasi pembayaran elektronik (QRIS & Virtual Account) dengan pembaruan status real-time.
* Panel administrasi untuk pengelolaan master produk, pengawasan kuota inventaris, dan input nomor resi pengiriman.

### 1.3 Definisi, Akronim, dan Singkatan
* **SKU (Stock Keeping Unit)**: Kode identifikasi unik untuk setiap varian produk (misal: `MTX-TS01-BLK-L`).
* **PDP (Product Detail Page)**: Halaman rincian spesifikasi lengkap produk.
* **ACID (Atomicity, Consistency, Isolation, Durability)**: Properti transaksi basis data untuk menjamin validitas pemesanan dan pemotongan stok.
* **CSRF (Cross-Site Request Forgery)**: Serangan eksploitasi web yang dicegah dengan token sesi acak.
* **PDO (PHP Data Objects)**: Abstraksi akses basis data dengan *prepared statements* untuk mencegah *SQL Injection*.
* **VA (Virtual Account)**: Nomor rekening bank virtual sementara untuk pembayaran pesanan.

---

## 2. Deskripsi Umum Sistem

### 2.1 Arsitektur Tingkat Tinggi (High-Level Architecture)
Sistem menggunakan pendekatan **Modular Multi-Tier Web Architecture**:
1. **Presentation Layer (Frontend)**: HTML5 semantik, Tailwind CSS (styling monokromatik & dark mode), Vanilla JavaScript ES6+ untuk manipulasi DOM dan state keranjang lokal.
2. **Application / Business Logic Layer (Backend)**: PHP 8.1+ modular (menangani routing, validasi masukan, autentikasi sesi, perhitungan ongkos kirim, dan kalkulasi total transaksi).
3. **Data Layer (Persistence)**: MySQL / MariaDB dengan *InnoDB Engine* untuk mendukung foreign key constraints dan transaksi tingkat ACID.

### 2.2 Karakteristik Pengguna
1. **Pengguna Tamu (Guest Customer)**: Dapat menjelajah katalog, menambahkan item ke keranjang, dan menyelesaikan checkout tanpa perlu membuat akun.
2. **Pelanggan Terdaftar (Registered Customer)**: Memiliki hak yang sama dengan tamu, ditambah akses ke halaman profil, riwayat transaksi terdahulu, dan pelacakan status pesanan.
3. **Administrator Toko (Store Admin)**: Memiliki hak istimewa penuh untuk menambah/mengedit produk, memperbarui jumlah stok fisik, memverifikasi pembayaran, serta mengubah status order menjadi *Shipped* dengan melampirkan resi kurir.

### 2.3 Batasan & Asumsi Desain
* Tidak bergantung pada framework PHP yang berat (*lightweight native architecture*) agar performa maksimal di lingkungan lokal XAMPP maupun server produksi.
* Sisi frontend dirancang dengan filosofi *Mobile-First*, mengingat lebih dari 80% pembeli streetwear mengakses lewat perangkat seluler.

---

## 3. Kebutuhan Fungsional (Functional Requirements)

### 3.1 Modul Katalog & Penelusuran (FR-CAT)
* **FR-CAT-01**: Sistem HARUS menampilkan daftar produk aktif dalam tata letak kisi (*responsive grid*).
* **FR-CAT-02**: Sistem HARUS menyediakan mekanisme penyaringan (*filter*) berdasarkan kategori produk (*Tees, Hoodies, Outerwear, Pants, Accessories*).
* **FR-CAT-03**: Sistem HARUS menyediakan fasilitas pencarian instan (*instant search*) berdasarkan nama produk dan deskripsi singkat.
* **FR-CAT-04**: Sistem HARUS menyediakan fitur pengurutan (*sorting*) berdasarkan:
  - Rekomendasi/Featured
  - Harga Terendah ke Tertinggi
  - Harga Tertinggi ke Terendah
  - Ulasan/Rating Tertinggi
* **FR-CAT-05**: Setiap kartu produk HARUS memiliki efek transisi gambar kedua (*hover swap*) yang memperlihatkan pose model / detail tekstil kain.

### 3.2 Modul Detail Produk & Varian (FR-PDP)
* **FR-PDP-01**: Sistem HARUS menampilkan informasi detail: Nama Produk, Harga Normal, Harga Coret (jika diskon), Kategori, Komposisi Bahan, Petunjuk Perawatan, dan Galeri Foto.
* **FR-PDP-02**: Sistem HARUS menampilkan pilihan ukuran (*size selection pills*) yang hanya dapat dipilih jika stok varian tersebut $> 0$.
* **FR-PDP-03**: Jika suatu ukuran memiliki stok $= 0$, sistem HARUS menandai ukuran tersebut sebagai *Disabled* / *Out of Stock*.
* **FR-PDP-04**: Sistem HARUS menyediakan modal *Size Chart Guide* dengan dimensi panjang $\times$ lebar (cm) dan rekomendasi berat/tinggi badan.

### 3.3 Modul Keranjang Belanja (FR-CRT)
* **FR-CRT-01**: Sistem HARUS menyediakan keranjang belanja model *Slide-Over Drawer* tanpa memicu *reload* halaman.
* **FR-CRT-02**: Sistem HARUS menyimpan data keranjang belanja di `localStorage` peramban pembeli agar tidak hilang saat halaman di-refresh atau browser ditutup.
* **FR-CRT-03**: Pengguna HARUS dapat menambah kuantitas (+), mengurangi kuantitas (-), dan menghapus item dari keranjang secara langsung.
* **FR-CRT-04**: Sistem HARUS menghitung subtotal belanja secara instan saat kuantitas berubah.
* **FR-CRT-05**: Sistem HARUS menampilkan *Free Shipping Progress Bar* yang secara dinamis menunjukkan sisa nominal belanja untuk mendapatkan bebas ongkir (ambang batas: Rp 500.000).

### 3.4 Modul Transaksi & Checkout (FR-CHK)
* **FR-CHK-01**: Sistem HARUS mendukung *Guest Checkout* (pembeli cukup mengisi Nama, Email, Nomor WhatsApp/Telepon, dan Alamat Lengkap).
* **FR-CHK-02**: Form alamat pengiriman HARUS memuat: Alamat Jalan, Provinsi, Kota/Kabupaten, Kecamatan, dan Kode Pos.
* **FR-CHK-03**: Sistem HARUS mengalkulasi biaya pengiriman berdasarkan kurir yang dipilih (JNE, SiCepat, J&T) dengan estimasi waktu tiba (ETD).
* **FR-CHK-04**: Sistem HARUS menghasilkan Nomor Pesanan unik (*Order Number*) dengan format: `MTX-YYYYMMDD-XXXX` (contoh: `MTX-20261105-8912`).

### 3.5 Modul Manajemen Stok & Integritas Transaksi (FR-STK)
* **FR-STK-01**: Saat pembeli menekan tombol *"Place Order"*, sistem backend HARUS membuka transaksi basis data (`START TRANSACTION`) dan mengunci baris stok (`SELECT ... FOR UPDATE`).
* **FR-STK-02**: Jika stok varian mencukupi, sistem HARUS mengurangi stok fisik sejumlah kuantitas pesanan dan menetapkan status order menjadi `PENDING_PAYMENT`.
* **FR-STK-03**: Jika stok tidak mencukupi, transaksi HARUS di-*rollback* dan sistem mengembalikan pesan kegagalan ramah pengguna (*"Maaf, stok baru saja habis dibeli pelanggan lain"*).
* **FR-STK-04**: Sistem HARUS memiliki batas waktu kedaluwarsa pesanan (*payment expiry window*, default: 60 menit). Jika tidak dibayar dalam batas waktu tersebut, stok otomatis dikembalikan ke inventaris (`RESTORE STOCK`) dan status menjadi `EXPIRED`.

### 3.6 Modul Pembayaran (FR-PAY)
* **FR-PAY-01**: Sistem HARUS menyediakan pilihan metode pembayaran:
  - QRIS Dinamis (dapat discan langsung oleh BCA Mobile, GoPay, OVO, ShopeePay, Dana, dll).
  - Virtual Account Bank (BCA, Mandiri, BNI, BRI).
* **FR-PAY-02**: Sistem HARUS menerima notifikasi / webhook pembayaran untuk secara otomatis memperbarui status order dari `PENDING_PAYMENT` menjadi `PAID`.
* **FR-PAY-03**: Setelah pembayaran sukses, sistem HARUS menampilkan halaman konfirmasi (*Invoice / Receipt View*) yang memuat nomor pesanan, detail item, kurir terpilih, dan alamat kirim.

### 3.7 Modul Pelacakan Pesanan (FR-TRK)
* **FR-TRK-01**: Sistem HARUS menyediakan halaman publik *Track Order*.
* **FR-TRK-02**: Pengguna dapat memasukkan Nomor Pesanan dan Email/No HP untuk melihat status terkini pesanan:
  `PENDING_PAYMENT` $\to$ `PAID` $\to$ `PROCESSING (Packing)` $\to$ `SHIPPED` $\to$ `COMPLETED`.
* **FR-TRK-03**: Saat status pesanan `SHIPPED`, sistem HARUS menampilkan nama kurir dan nomor resi (*Airway Bill / AWB*).

### 3.8 Modul Panel Administrator (FR-ADM)
* **FR-ADM-01**: Akses panel admin HARUS diproteksi dengan otentikasi kata sandi terenkripsi.
* **FR-ADM-02**: Admin HARUS dapat menambah produk baru, mengunggah foto, mengatur kategori, dan mengelola varian ukuran/warna serta jumlah stoknya.
* **FR-ADM-03**: Admin HARUS dapat melihat daftar pesanan masuk yang sudah berstatus `PAID`.
* **FR-ADM-04**: Admin HARUS dapat memperbarui status pesanan menjadi `SHIPPED` dan menginput nomor resi pengiriman.
* **FR-ADM-05**: Admin HARUS dapat melihat ringkasan omset penjualan harian/bulanan serta peringatan stok menipis (*Low Stock Alert* $< 5$ pcs).

---

## 4. Kebutuhan Non-Fungsional (Non-Functional Requirements)

### 4.1 Kebutuhan Kinerja (Performance - NFR-PERF)
* **NFR-PERF-01**: Waktu respon halaman pertama (*Time to First Byte / TTFB*) tidak boleh melebihi 250 ms di server lokal maupun staging.
* **NFR-PERF-02**: Skor Google Lighthouse untuk kategori Performa pada perangkat mobile minimal 85.
* **NFR-PERF-03**: Query basis data untuk halaman katalog produk harus selesai dalam waktu kurang dari 50 ms dengan pemanfaatan indeks basis data yang tepat.

### 4.2 Kebutuhan Keamanan (Security - NFR-SEC)
* **NFR-SEC-01 (Anti-SQL Injection)**: Seluruh interaksi ke basis data MySQL HARUS menggunakan **PDO Prepared Statements** dengan parameter binding. Dilarang keras melakukan konkatenasi string SQL mentah.
* **NFR-SEC-02 (Anti-XSS)**: Seluruh output dinamis yang dicetak ke layar HTML HARUS melalui fungsi sanitasi `htmlspecialchars($data, ENT_QUOTES, 'UTF-8')`.
* **NFR-SEC-03 (Anti-CSRF)**: Setiap form mutasi data (POST / PUT / DELETE) HARUS menyertakan token CSRF acak yang divalidasi dengan token sesi server.
* **NFR-SEC-04 (Hashing Password)**: Kata sandi pengguna/admin HARUS dienkripsi menggunakan algoritma `password_hash($password, PASSWORD_BCRYPT)` dengan cost factor minimal 12.

### 4.3 Kebutuhan Keandalan & Integritas Data (Reliability - NFR-REL)
* **NFR-REL-01**: Seluruh tabel transaksi keuangan dan pemotongan stok HARUS menggunakan engine **InnoDB** dengan relasi *Foreign Key* bertipe `ON DELETE RESTRICT` pada data master.
* **NFR-REL-02**: Sistem HARUS memiliki pencatatan log error aplikasi (*error logging*) di direktori aman yang tidak dapat diakses langsung oleh publik.

### 4.4 Kebutuhan Desain & Usabilitas (Usability - NFR-USA)
* **NFR-USA-01**: Desain antarmuka wajib mengusung tema **Dark Minimalist / Streetwear Aesthetics** dengan palet dominan Carbon Black (`#080809`), Off-White, dan aksen tipografi kontras tinggi.
* **NFR-USA-02**: Seluruh elemen yang dapat diklik (*touch targets*) pada versi mobile wajib memiliki ukuran minimal $44 \times 44$ piksel sesuai standar panduan aksesibilitas WCAG 2.1.

---

## 5. Matriks Ketertelusuran Kebutuhan (Traceability Matrix)

| ID Kebutuhan | Komponen Implementasi | Tabel Basis Data Terkait | Rencana Verifikasi / Pengujian |
| :--- | :--- | :--- | :--- |
| **FR-CAT-01..05** | `index.php`, `catalog.js` | `products`, `categories` | Unit Test dataset & filter assertions |
| **FR-PDP-01..04** | `quickview-modal`, `pdp.php` | `product_variants` | Verifikasi render swatches & modal render |
| **FR-CRT-01..05** | `cart_drawer.php`, `catalog.js`| `localStorage` | Simulasi penambahan/pengurangan item |
| **FR-CHK-01..04** | `checkout.php` | `orders`, `order_items` | Pengujian validasi form & kalkulasi ongkir |
| **FR-STK-01..04** | `api/order_create.php` | `product_variants` | Concurrency testing & atomic rollback test |
| **FR-PAY-01..03** | `api/payment_webhook.php` | `payments`, `orders` | Mocking HTTP POST webhook & status assert |
| **FR-ADM-01..05** | `admin/dashboard.php` | `users`, `products`, `orders`| Autentikasi sesi admin & CRUD verification |

