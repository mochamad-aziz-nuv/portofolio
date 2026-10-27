# 📋 Panduan Deployment Live (Railway & Render)
> **NagroSmart • Smart Aquaculture Management Platform**  
> Proyek ini siap di-deploy langsung secara gratis ke cloud agar siapapun (klien, recruiter, atau pengguna) bisa langsung mencoba tanpa instalasi lokal.

---

## 🎯 Ringkasan Kredensial Uji Coba Live

Saat web berhasil live, pengguna dapat langsung login ke **`/admin`** maupun menembak API dengan kredensial berikut:

| Peran (Role) | Email | Password | Kegunaan |
| :--- | :--- | :--- | :--- |
| **Admin Pusat** | `admin@aquafarm.test` | `password` | Akses penuh dashboard Filament & manajemen user |
| **Supervisor Kolam** | `supervisor@aquafarm.test` | `password` | Pengawasan blok tambak & siklus budidaya |
| **Teknisi Utama** | `teknisi@aquafarm.test` | `password` | Analisis kualitas air & evaluasi anco pakan |
| **Asisten Teknisi** | `asisten@aquafarm.test` | `password` | Input pengukuran sensor DO/pH lapangan |
| **Operator Pakan** | `operator@aquafarm.test` | `password` | Pencatatan pakan harian 4x sehari |

---

## 🚀 OPSI 1: Deploy ke Railway (Direkomendasikan — Paling Cepat)

[Railway](https://railway.app) memiliki performa build Docker yang sangat cepat dan otomatis memberikan domain publik SSL HTTPS.

### Langkah-langkah:
1. **Daftar / Login ke Railway**:
   - Buka [railway.com](https://railway.com) lalu login dengan akun GitHub Anda (`mochamad-aziz-nuv`).
2. **Buat Project Baru**:
   - Klik tombol **+ New Project** -> Pilih **Deploy from GitHub repo**.
   - Pilih repository Anda: `mochamad-aziz-nuv/portofolio`.
   - *Catatan jika repo di dalam subfolder:*  
     Di menu **Settings** -> bagian **Root Directory**, masukkan:  
     `aquaculture-laravel-api`  
     *(Atau jika Anda membuat repo standalone tersendiri, biarkan root kosong).*
3. **Konfigurasi Otomatis Docker**:
   - Railway akan otomatis mendeteksi file `Dockerfile` yang sudah kami sediakan.
4. **Generate Domain Publik**:
   - Buka tab **Settings** pada service Anda -> scroll ke bagian **Networking** -> klik **Generate Domain**.
   - Anda akan mendapatkan URL publik, contohnya: `https://aquaculture-production.up.railway.app`.
5. **Selesai!**  
   Buka URL tersebut di browser. Halaman landing page dan dashboard `/admin` langsung aktif beserta 5 kolam dan data dummy 30 hari!

---

## 🌐 OPSI 2: Deploy ke Render (Gratis Selamanya)

[Render](https://render.com) menyediakan hosting gratis dengan pilihan server di **Singapura** (sangat cepat untuk diakses dari Indonesia).

### Langkah-langkah:
1. **Daftar / Login ke Render**:
   - Buka [render.com](https://render.com) dan login via GitHub.
2. **Buat Web Service Baru**:
   - Klik **New +** -> Pilih **Web Service**.
   - Hubungkan akun GitHub Anda dan pilih repository `mochamad-aziz-nuv/portofolio`.
3. **Pengaturan Service**:
   - **Name**: `nagrosmart-aquaculture`
   - **Region**: `Singapore (Southeast Asia)`
   - **Root Directory**: `aquaculture-laravel-api` *(jika berada dalam repo portofolio)*
   - **Language / Runtime**: Pilih **Docker**
   - **Instance Type**: Pilih **Free**
4. **Environment Variables**:
   Tambahkan variabel berikut di tab Environment:
   - `APP_ENV` = `production`
   - `APP_DEBUG` = `false`
   - `APP_TIMEZONE` = `Asia/Jakarta`
5. **Klik "Deploy Web Service"**:
   - Render akan menjalankan build Docker dan migrasi database otomatis.
   - Dalam 3-5 menit, Anda akan mendapatkan URL live gratis, misalnya:  
     `https://nagrosmart-aquaculture.onrender.com`

---

## 🧪 Menguji URL Live Anda

Setelah berhasil deploy ke Railway atau Render:

1. **Buka Halaman Utama**:
   `https://<domain-anda>/`
   *(Akan tampil Landing Page interaktif bernuansa kelautan dengan penjelasan fitur, kredensial, dan arsitektur).*

2. **Buka Dashboard Filament**:
   `https://<domain-anda>/admin`
   - Masukkan email: `admin@aquafarm.test`
   - Password: `password`
   - Anda akan langsung melihat grafik parameter kualitas air (DO, pH, suhu) dan ringkasan 5 kolam aktif.

3. **Uji API Healthcheck**:
   `https://<domain-anda>/api/health`
   *(Respon JSON: status `ok` dan service `NagroSmart Aquaculture API`).*

4. **Uji API Login Mobile via Postman / Curl**:
   ```bash
   curl -X POST https://<domain-anda>/api/v1/auth/login \
     -H "Content-Type: application/json" \
     -d '{"email":"teknisi@aquafarm.test", "password":"password"}'
   ```
   *(Respon akan mengembalikan token Sanctum dan data user lengkap dengan rolenya).*

---

## 💡 Tips Tambahan untuk Portofolio

Cantumkan tautan live ini di:
- **README GitHub**
- **LinkedIn post**
- **Website Portofolio utama** (`Portofolio/index.html`)

Dengan adanya live demo yang bisa langsung diklik, kredibilitas Anda di mata calon klien atau Tech Lead akan melonjak drastis karena membuktikan bahwa Anda tidak hanya bisa menulis kode, tapi juga memahami *deployment pipeline*, *containerization*, dan *cloud operations*.
