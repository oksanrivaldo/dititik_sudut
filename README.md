[Uploading README.md…]()
# ☕ DiTitik Sudut - Aplikasi Kasir (POS) dengan Enkripsi Private Key

Aplikasi sistem informasi kasir (Point of Sale) modern yang dilengkapi dengan landing page interaktif, sistem autentikasi admin berbasis **Private Key**, serta penerapan **enkripsi data** pada penyimpanan database dan proses dekripsi otomatis saat masuk ke dashboard admin.

---

## 🚀 Fitur Utama
1. **Landing Page Interaktif:** Halaman depan profil kafe/toko lengkap dengan daftar menu, harga, stok, dan lokasi gerai.
2. **Login Berbasis Private Key:** Autentikasi keamanan admin yang menggunakan kunci privat (*private key*) alih-alih password konvensional.
3. **Enkripsi Database:** Data sensitif seperti nama produk dan harga otomatis dienkripsi menggunakan algoritma `AES-256-CBC` sebelum disimpan ke database MySQL.
4. **Dekripsi Otomatis (Admin Dashboard):** Saat admin berhasil masuk, sistem melakukan dekripsi data secara *on-the-fly* sehingga konten dapat dibaca dengan normal di tabel manajemen kasir.

---

## 🛠️ Stack Teknologi
* **Bahasa Pemrograman:** PHP (Native / Prosedural)
* **Database:** MySQL (dikelola via Laragon / HeidiSQL)
* **Frontend Framework:** Tailwind CSS (via CDN)
* **Keamanan:** PHP OpenSSL (AES-256-CBC) & Password Hashing

---

## ⚙️ Langkah Instalasi & Menjalankan Aplikasi
1. Pastikan aplikasi **Laragon** sudah terpasang dan layanan **Apache & MySQL** dalam status *Running*.
2. Clone atau unduh repository ini ke dalam direktori web server lokal Anda:
   ```bash
   C:\laragon\www\dtitik_sudut
