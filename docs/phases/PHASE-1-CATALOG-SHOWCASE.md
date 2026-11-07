# MORTYX APPAREL — PHASE 1 TECHNICAL SPECIFICATION & REPORT
## Feature: Architecture Foundation & Catalog Showcase (`feature/catalog-showcase`)

**Status**: 🟢 COMPLETED & MERGED TO MAIN  
**Branch**: `feature/catalog-showcase` (Merged via PR #1: `commit 344dc32`)  
**Base Branch**: `main`  
**Test Suite**: `tests/test_runner.php` (15/15 PASS)  

---

## 1. Overview & Scope

Phase 1 mendirikan fondasi arsitektur sistem e-commerce MORTYX, menyusun dokumen rekayasa perangkat lunak (PRD, SRS, UML), serta membangun etalase katalog produk interaktif bergaya streetwear gelap monokromatik.

---

## 2. Deliverables & Implementasi

1. **Dokumentasi Rekayasa Perangkat Lunak**:
   - `docs/PRD.md`: Analisis pasar streetwear, personas, MoSCoW feature matrix, metrik keberhasilan.
   - `docs/SRS.md`: Kebutuhan fungsional (FR-CAT, FR-CHK, FR-AUT, FR-ADM) & non-fungsional (NFR).
   - `docs/UML.md`: Diagram Use Case, Activity, Sequence, ERD, dan State Machine dalam sintaks Mermaid.

2. **Katalog & Frontend**:
   - `data/products.php`: Dataset 6 produk streetwear (Tees, Hoodies, Outerwear, Bottoms, Accessories) dengan helper formatting mata uang rupiah (`format_rupiah`).
   - `includes/header.php`: Navigation bar responsif dengan pengumuman banner, live search dropdown, dan tombol cart.
   - `includes/footer.php`: Manifesto brand MORTYX, form newsletter, dan tautan kebijakan.
   - `includes/cart_drawer.php`: Slide-over cart drawer dengan progress bar gratis ongkir.
   - `index.php`: Landing page hero, grid katalog dengan hover-swap gambar, filter kategori, dropdown sorting, modal quick-view, dan lookbook.
   - `assets/js/catalog.js`: Manajemen state cart sisi klien (localStorage), search filter, quick-view modal, dan size selector.

3. **CI & Automated Testing**:
   - `tests/test_runner.php`: 15 unit test untuk helper rupiah, integritas schema produk, kelengkapan file asset, dan validasi kategori.
   - `.github/workflows/ci.yml`: GitHub Actions pipeline untuk otomatisasi pengujian PHP di lingkungan CI.
