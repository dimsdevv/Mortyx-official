# MORTYX APPAREL — SDLC PHASE TRACKER & ROADMAP

Dokumen ini melacak seluruh siklus pengembangan (SDLC) brand e-commerce streetwear **MORTYX**, memetakan branch, pull request, status pengujian otomatis, dan dokumentasi spesifikasi teknis untuk setiap fase.

---

## 🗺️ Master Phase Matrix

| Phase | Nama Fitur | Branch | Status | PR & Commit | Automated Tests | Spesifikasi Teknis |
|---|---|---|---|---|---|---|
| **Phase 1** | Architecture Foundation & Catalog Showcase | `feature/catalog-showcase` | 🟢 MERGED | PR #1 (`344dc32`) | 15 Tests PASS | [`PHASE-1-CATALOG-SHOWCASE.md`](./phases/PHASE-1-CATALOG-SHOWCASE.md) |
| **Phase 2** | Relational Database Schema & Seeder | `feature/database-setup` | 🟢 MERGED | PR #2 (`0d4c67b`) | 26 Tests PASS | [`PHASE-2-DATABASE-SETUP.md`](./phases/PHASE-2-DATABASE-SETUP.md) |
| **Phase 3** | Authentication & Customer Portal | `feature/auth-system` | 🟢 MERGED | PR #3 (`7c08446`) | 25 Tests PASS | [`PHASE-3-AUTH-SYSTEM.md`](./phases/PHASE-3-AUTH-SYSTEM.md) |
| **Design Upgrade** | CALLE Minimalist UI Overhaul | `feature/minimalist-ui-redesign` | 🟢 MERGED | PR #4 (`b1bdd23`) | 66 Tests PASS | [`PHASE-REDESIGN-CALLE-MINIMALIST.md`](./phases/PHASE-REDESIGN-CALLE-MINIMALIST.md) |
| **Streamline UX** | Landing Splash & Separate Catalog | `feature/landing-streamline` | 🟢 MERGED | PR #5 (`cf0dc2f`) | 66 Tests PASS | [`PHASE-LANDING-STREAMLINE.md`](./phases/PHASE-LANDING-STREAMLINE.md) |
| **Footer Luxury** | Black Footer & Professional Icons | `feature/footer-luxury-redesign` | 🟢 MERGED | PR #6 (`b125ec5`) | 66 Tests PASS | [`PHASE-FOOTER-LUXURY.md`](./phases/PHASE-FOOTER-LUXURY.md) |
| **Phase 4** | Checkout & Stock Reservation Flow | `feature/checkout-flow` | ⚪ PLANNED | - | `test_checkout.php` | - |
| **Phase 5** | Payment Gateway Webhook Integration | `feature/payment-webhook` | ⚪ PLANNED | - | `test_payment.php` | - |
| **Phase 6** | Admin Inventory & Order Management | `feature/admin-dashboard` | ⚪ PLANNED | - | `test_admin.php` | - |

---

## 📜 Standar Rekayasa & Arsitektur
1. **Aturan Utama Branching**: Haram hukumnya melakukan commit langsung ke branch `main`. Setiap fitur wajib dikembangkan pada branch terisolasi (`feature/<name>`).
2. **Atomic Commits**: Setiap unit pekerjaan kecil yang selesai dan lolos linting harus segera dicommit dengan pesan deskriptif.
3. **Automated Testing Mandatory**: Sebelum merge ke `main`, seluruh automated test suite harus berstatus 🟢 **PASS**.
4. **Zero AI Slop**: Penerapan pedoman `anti-slop`, `impeccable`, dan `minimalist-ui` pada seluruh tampilan dan kode.
5. **Deep Module Pattern**: Penerapan prinsip `codebase-design` untuk modul backend (isolasi antarmuka sempit dengan implementasi yang tersembunyi).

