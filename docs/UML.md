# UNIFIED MODELING LANGUAGE (UML) DIAGRAMS
## MORTYX — Official Apparel E-Commerce Architecture

Dokumen ini memuat diagram visual arsitektur sistem perangkat lunak MORTYX dengan standar pemodelan UML menggunakan sintaks Mermaid.

---

## 1. Use Case Diagram

Diagram ini memetakan interaksi fungsional antara aktor utama (**Guest Customer**, **Registered Customer**, **Store Admin**, dan **Payment Gateway**) dengan batasan sistem MORTYX.

```mermaid
flowchart LR
    Customer((Guest Customer))
    RegCustomer((Registered Customer))
    Admin((Store Admin))
    PGSystem((Payment Gateway API))

    Customer -.->|Spesialisasi Akun| RegCustomer

    subgraph MORTYX E-Commerce Platform
        UC_View[1. Jelajah Katalog & Filter Varian]
        UC_QuickView[2. Quick View & Lihat Size Chart]
        UC_Cart[3. Kelola Keranjang Belanja Slide-Over]
        UC_Checkout[4. Checkout Pesanan]
        UC_Pay[5. Melakukan Pembayaran QRIS / VA]
        UC_Track[6. Lacak Status Pengiriman Resi]
        
        UC_History[7. Lihat Riwayat Pesanan & Profil]
        UC_Wishlist[8. Simpan Produk ke Wishlist]

        UC_ManageProd[9. CRUD Produk & Manajemen Stok Varian]
        UC_ManageOrder[10. Verifikasi Order & Input Resi Kurir]
        UC_Analytics[11. Pantau Laporan Penjualan & Low Stock Alert]
        
        UC_VerifyPay[12. Validasi & Notifikasi Webhook Pembayaran]
    end

    Customer --> UC_View
    Customer --> UC_QuickView
    Customer --> UC_Cart
    Customer --> UC_Checkout
    Customer --> UC_Pay
    Customer --> UC_Track

    RegCustomer --> UC_History
    RegCustomer --> UC_Wishlist

    Admin --> UC_ManageProd
    Admin --> UC_ManageOrder
    Admin --> UC_Analytics

    PGSystem --> UC_VerifyPay
    UC_VerifyPay -.->|Update Status Otomatis| UC_ManageOrder
```

---

## 2. Activity Diagrams

### 2.1 Alur Checkout, Validasi Stok & Pembayaran (Customer Journey)

```mermaid
flowchart TD
    Start([Mulai: Pembeli Buka Keranjang]) --> ClickCheckout[Klik Tombol 'Proceed to Checkout']
    ClickCheckout --> FillAddress[Isi Formulir Alamat Lengkap & Kontak]
    FillAddress --> ChooseCourier[Pilih Layanan Kurir: JNE / SiCepat / J&T]
    ChooseCourier --> ChoosePayment[Pilih Metode Bayar: QRIS / Virtual Account]
    ChoosePayment --> SubmitOrder[Klik 'Place Order']
    
    SubmitOrder --> BeginTx{Mulai Database Transaction: Cek Ketersediaan Stok Varian}
    
    BeginTx -- "Stok Habis / Rebutan" --> RollbackTx[Rollback Transaksi]
    RollbackTx --> ShowOutOfStock[Tampilkan Pesan Error: Stok Baru Saja Habis]
    ShowOutOfStock --> EndFail([Selesai: Kembali ke Keranjang])

    BeginTx -- "Stok Tersedia" --> DeductStock[Kurangi Stok Fisik Varian]
    DeductStock --> CreateOrderRecord[Buat Data Order Status: PENDING_PAYMENT]
    CreateOrderRecord --> GenPayToken[Request Token Pembayaran ke Payment Gateway]
    GenPayToken --> CommitTx[Commit Transaksi Database]
    CommitTx --> RenderPayScreen[Tampilkan Layar Bayar: QR Code / Nomor VA]

    RenderPayScreen --> WaitPayment{Menunggu Pembayaran Pembeli Maks. 60 Menit}
    
    WaitPayment -- "Bayar Tepat Waktu" --> WebhookReceived[Terima Webhook Sukses dari Payment Gateway]
    WebhookReceived --> UpdatePaid[Ubah Status Order Menjadi: PAID]
    UpdatePaid --> ShowInvoice[Tampilkan Halaman Invoice & Konfirmasi Pesanan]
    ShowInvoice --> EndSuccess([Selesai: Pesanan Siap Diproses Gudang])

    WaitPayment -- "Melebihi 60 Menit" --> TimeoutExpired[Trigger Scheduler / Cron Expiry]
    TimeoutExpired --> RestoreStock[Kembalikan Kuantitas Stok ke Tabel Varian]
    RestoreStock --> UpdateExpired[Ubah Status Order Menjadi: EXPIRED]
    UpdateExpired --> EndExpired([Selesai: Pesanan Dibatalkan Otomatis])
```

---

### 2.2 Alur Pemenuhan Pesanan oleh Admin (Fulfillment Workflow)

```mermaid
flowchart TD
    A([Mulai: Admin Login ke Dashboard]) --> B[Buka Tab Menu 'Orders']
    B --> C[Filter Pesanan dengan Status: PAID]
    C --> D[Pilih Order & Cetak Shipping Label / Packing Slip]
    D --> E[Tim Gudang Mempersiapkan Baju ke Ziplock & Sticker Pack]
    E --> F[Serahkan Paket ke Kurir Ekspedisi]
    F --> G[Kurir Menerbitkan Nomor Resi / AWB Fisik]
    G --> H[Admin Menginput Nomor Resi ke Sistem]
    H --> I[Update Status Pesanan Menjadi: SHIPPED]
    I --> J[Kirim Notifikasi / Tampilkan Resi di Halaman Track Order]
    J --> K([Selesai])
```

---

## 3. Sequence Diagrams

### 3.1 Sequence Pembuatan Pesanan & Atomic Stock Reservation

```mermaid
sequenceDiagram
    autonumber
    actor User as Pembeli (Browser)
    participant UI as Checkout Frontend
    participant API as Order API Handler (PHP)
    participant DB as MySQL Database (InnoDB)
    participant PG as Payment Gateway

    User->>UI: Klik tombol "Place Order"
    UI->>API: POST /api/orders/create (Data Penerima, Items, Kurir, Metode)
    API->>DB: START TRANSACTION
    API->>DB: SELECT stock FROM product_variants WHERE id = :variant_id FOR UPDATE
    
    alt Stok < Kuantitas Pesanan
        DB-->>API: Stok Tidak Cukup
        API->>DB: ROLLBACK
        API-->>UI: HTTP 400 (Maaf, kuota ukuran ini baru saja habis)
        UI-->>User: Tampilkan notifikasi error stok
    else Stok >= Kuantitas Pesanan
        DB-->>API: Stok Valid
        API->>DB: UPDATE product_variants SET stock = stock - :qty WHERE id = :variant_id
        API->>DB: INSERT INTO orders (order_number, total_amount, status='PENDING_PAYMENT')
        API->>DB: INSERT INTO order_items (order_id, variant_id, qty, price)
        API->>PG: Request Create Transaction (QRIS / VA)
        PG-->>API: Return payment_url, qr_string, va_number, expiry_time
        API->>DB: INSERT INTO payments (order_id, payment_method, payment_status='PENDING')
        API->>DB: COMMIT
        API-->>UI: HTTP 201 Created (Order Number & Payment Details)
        UI-->>User: Tampilkan QRIS / Nomor VA & Hitung Mundur 60 Menit
    end
```

---

### 3.2 Sequence Webhook Notifikasi Pembayaran & Settlement

```mermaid
sequenceDiagram
    autonumber
    actor Customer as Pembeli
    participant App as Mobile Banking / e-Wallet (BCA/GoPay)
    participant PG as Payment Gateway Server
    participant Webhook as Webhook Listener (/api/payment/webhook.php)
    participant DB as MySQL Database

    Customer->>App: Scan QRIS / Transfer ke Nomor VA
    App->>PG: Notifikasi Pembayaran Berhasil
    PG-->>Customer: Tampilkan Struk Bukti Bayar Sukses
    
    Note over PG, Webhook: Gateway mengirim sinyal HTTP POST ke server MORTYX
    PG->>Webhook: POST /api/payment/webhook.php (order_id, transaction_status, signature_key)
    
    Webhook->>Webhook: Verifikasi Signature Hash Key Keamanan
    alt Signature Valid & transaction_status == 'settlement' / 'capture'
        Webhook->>DB: START TRANSACTION
        Webhook->>DB: UPDATE payments SET payment_status = 'SETTLED', paid_at = NOW() WHERE order_id = :id
        Webhook->>DB: UPDATE orders SET status = 'PAID' WHERE id = :id
        Webhook->>DB: COMMIT
        Webhook-->>PG: HTTP 200 OK (Acknowledge received)
    else Signature Palsu / Anomali
        Webhook-->>PG: HTTP 403 Forbidden (Invalid signature)
    end
```

---

## 4. Entity-Relationship Diagram (ERD & Class Schema)

Diagram struktur basis data relasional (*relational database schema*) yang dirancang untuk mendukung performa tinggi, integritas referensial, dan pencatatan audit order.

```mermaid
erDiagram
    USERS ||--o{ ORDERS : places
    USERS {
        int id PK "AUTO_INCREMENT"
        string name "VARCHAR(100)"
        string email "VARCHAR(150) UNIQUE"
        string password "VARCHAR(255)"
        string phone "VARCHAR(20)"
        enum role "ENUM('customer','admin') DEFAULT 'customer'"
        datetime created_at
        datetime updated_at
    }

    CATEGORIES ||--o{ PRODUCTS : categorizes
    CATEGORIES {
        int id PK "AUTO_INCREMENT"
        string name "VARCHAR(50)"
        string slug "VARCHAR(50) UNIQUE"
        text description
    }

    PRODUCTS ||--|{ PRODUCT_VARIANTS : contains
    PRODUCTS {
        int id PK "AUTO_INCREMENT"
        int category_id FK
        string name "VARCHAR(150)"
        string slug "VARCHAR(150) UNIQUE"
        decimal base_price "DECIMAL(12,2)"
        decimal compare_price "DECIMAL(12,2) NULL"
        string tag "VARCHAR(50) NULL"
        text short_description
        text full_description
        string primary_image "VARCHAR(255)"
        string hover_image "VARCHAR(255)"
        boolean is_active "TINYINT(1) DEFAULT 1"
        datetime created_at
    }

    PRODUCT_VARIANTS {
        int id PK "AUTO_INCREMENT"
        int product_id FK
        string size "VARCHAR(10) (S, M, L, XL, XXL)"
        string color "VARCHAR(50)"
        string color_hex "VARCHAR(10)"
        int stock "INT NOT NULL DEFAULT 0"
        string sku "VARCHAR(50) UNIQUE"
        decimal price_override "DECIMAL(12,2) NULL"
    }

    ORDERS ||--|{ ORDER_ITEMS : contains
    ORDERS {
        int id PK "AUTO_INCREMENT"
        string order_number "VARCHAR(32) UNIQUE"
        int user_id FK "NULLABLE (for guest checkout)"
        string customer_name "VARCHAR(100)"
        string customer_email "VARCHAR(150)"
        string customer_phone "VARCHAR(20)"
        text shipping_address
        string city "VARCHAR(100)"
        string province "VARCHAR(100)"
        string postal_code "VARCHAR(10)"
        string courier_name "VARCHAR(50) (JNE, SiCepat, J&T)"
        decimal shipping_cost "DECIMAL(10,2)"
        decimal subtotal "DECIMAL(12,2)"
        decimal total_amount "DECIMAL(12,2)"
        enum status "ENUM('PENDING_PAYMENT','PAID','PROCESSING','SHIPPED','COMPLETED','CANCELLED','EXPIRED')"
        string tracking_number "VARCHAR(100) NULL"
        datetime expires_at
        datetime created_at
        datetime updated_at
    }

    ORDER_ITEMS {
        int id PK "AUTO_INCREMENT"
        int order_id FK
        int variant_id FK
        string product_name "VARCHAR(150)"
        string variant_size "VARCHAR(10)"
        string variant_color "VARCHAR(50)"
        int quantity "INT NOT NULL"
        decimal unit_price "DECIMAL(12,2)"
        decimal subtotal_price "DECIMAL(12,2)"
    }

    ORDERS ||--|| PAYMENTS : settles
    PAYMENTS {
        int id PK "AUTO_INCREMENT"
        int order_id FK "UNIQUE"
        string payment_method "VARCHAR(50) (QRIS, VA_BCA, VA_MANDIRI)"
        decimal amount "DECIMAL(12,2)"
        enum payment_status "ENUM('PENDING','SETTLED','EXPIRED','FAILED')"
        string transaction_id "VARCHAR(100) NULL"
        text gateway_response_payload "TEXT NULL"
        datetime paid_at "DATETIME NULL"
        datetime created_at
    }
```

---

## 5. State Machine Diagram: Siklus Hidup Pesanan (Order Lifecycle)

```mermaid
stateDiagram-v2
    [*] --> PENDING_PAYMENT : Pembeli Klik 'Place Order' (Stok Dikunci)

    PENDING_PAYMENT --> PAID : Webhook Sukses (Bayar dalam 60 Menit)
    PENDING_PAYMENT --> EXPIRED : Batas Waktu 60 Menit Habis (Stok Dikembalikan)
    PENDING_PAYMENT --> CANCELLED : Dibatalkan Manual

    PAID --> PROCESSING : Admin Konfirmasi & Gudang Mempacking Baju
    
    PROCESSING --> SHIPPED : Paket Diserahkan ke Kurir & Resi Diinput
    
    SHIPPED --> COMPLETED : Paket Diterima Pembeli (Selesai)

    EXPIRED --> [*]
    CANCELLED --> [*]
    COMPLETED --> [*]
```

