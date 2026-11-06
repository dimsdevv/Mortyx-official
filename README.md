# MORTYX — Official Apparel E-Commerce

> Sistem platform e-commerce resmi untuk brand pakaian **MORTYX**.

---

## 🛡️ Development & Collaboration Workflow (SOP)

Seluruh pengembangan di repositori ini mematuhi standar **Engineering Governance & Safety Workflow**:

1. **Plan & Specification First**
   - Setiap fitur wajib memiliki dokumen rencana / spesifikasi fitur yang disepakati sebelum kode ditulis.
   - Pengerjaan wajib menggunakan branch terpisah (`git checkout -b feature/<nama-fitur>`).
   - **Dilarang keras melakukan commit atau modifikasi langsung pada branch `main`**.

2. **Atomic Commits**
   - Setiap bagian kecil yang telah selesai dan berfungsi wajib di-commit secara terpisah dan bertahap.
   - Memastikan histori perubahan tercatat rapi dan mudah dilakukan *revert* / *rollback* jika ditemukan anomali.

3. **Push to Feature Branch**
   - Kode hanya di-push ke branch fitur (`git push origin feature/<nama-fitur>`), menjaga branch `main` tetap terlindungi.

4. **Pull Request (PR) Gatekeeper**
   - Setiap penggabungan kode ke `main` harus melalui Pull Request (PR) untuk direview secara manusia maupun automated reviewer.

5. **Automated CI Pipeline**
   - Pipeline CI otomatis menjalankan:
     - **Linting**: Konsistensi gaya penulisan kode.
     - **Automated Tests**: Unit test & integration test.
     - **Build Verification**: Memastikan kode dapat dikompilasi tanpa error.
   - **Aturan status CI**:
     - 🔴 **Merah (Fail)**: Dilarang keras dimerge.
     - 🟢 **Hijau (Pass)**: Lolos verifikasi, siap untuk review dan merge.

6. **Merge to Main & Continuous Deployment (CD)**
   - PR yang disetujui dan berstatus hijau digabungkan ke `main`, lalu sistem CD otomatis mendeploy versi terbaru ke server produksi.

---

## 📁 Repository Structure
*(Akan diperbarui seiring inisialisasi modul aplikasi)*

