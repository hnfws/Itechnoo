<div align="center">

![Logo](public/images/logo.png)
# Katstruction.

### Platform Pelaporan Infrastruktur Berbasis AI untuk Kolaborasi Warga dan Pemerintah

[![Live Demo](https://img.shields.io/badge/Live_Demo-Visit_Site-success?style=for-the-badge)](https://[URL_DEMO])
[![GitHub](https://img.shields.io/badge/GitHub-Repository-181717?style=for-the-badge&logo=github)](https://[URL_REPO])
[![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](LICENSE)

**Submission for ITECHNO CUP 2026 - Web Development**

**By WNI Suka Bobo**

</div>

---

## Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Unggulan](#fitur-unggulan)
- [Demo dan Screenshot](#demo-dan-screenshot)
- [Teknologi](#teknologi)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Instalasi dan Setup](#instalasi-dan-setup)
- [Penggunaan](#penggunaan)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Tim Developer](#tim-developer)
- [Lisensi](#lisensi)

---

## Tim Developer

Lengkapi data berikut sesuai anggota tim proyek.

| Nama | Peran | GitHub |
|------|-------|--------|
| **Muhana Fawwas Sausan** | Project Lead dan Full Stack Developer | [GitHub](https://github.com/hnfws) |
| **Kevin Chandra Syahrial** | Full Stack Developer & UI/UX Designer | [GitHub](https://github.com/04keishi) |

---

## Tentang Proyek

### Latar Belakang

Informasi mengenai kerusakan jalan dan masalah infrastruktur sering kali tersebar di berbagai kanal, sulit diverifikasi, dan tidak memiliki prioritas penanganan yang jelas. Warga membutuhkan cara yang sederhana untuk mengirim laporan, sedangkan admin atau pemerintah membutuhkan data yang terstruktur untuk memantau, memverifikasi, dan menentukan laporan yang perlu ditangani lebih dahulu.

### Solusi yang Ditawarkan

Katstruction. adalah platform pelaporan warga yang menghubungkan masyarakat dengan pengelola laporan melalui satu portal. Warga dapat membuat laporan lengkap dengan foto, lokasi, deskripsi, dan koordinat. Sistem kemudian membantu menganalisis tingkat keparahan serta urgensi laporan menggunakan AI, menghitung skor prioritas berdasarkan kondisi laporan dan dukungan warga, lalu menampilkan status penanganannya secara transparan.

### Tujuan Proyek

- **Tujuan Utama**: Memudahkan pelaporan masalah infrastruktur dan membantu admin menentukan prioritas penanganan.
- **Target Pengguna**: Warga, admin pengelola laporan, dan instansi yang menangani infrastruktur publik.
- **Value Proposition**: Pelaporan terstruktur, analisis AI, peta lokasi, dukungan warga, dan pemantauan status dalam satu aplikasi.

---

## Fitur Unggulan

### Fitur Utama

| Fitur | Deskripsi | Keunggulan |
|----------|--------------|---------------|
| **Pelaporan Infrastruktur** | Warga mengirim judul, identitas, kontak, deskripsi, foto, lokasi, dan koordinat laporan. | Data laporan lebih lengkap dan mudah ditindaklanjuti. |
| **Analisis AI** | Gemini menganalisis foto dan deskripsi untuk menghasilkan tingkat keparahan, urgensi, risiko, serta ringkasan untuk warga dan admin. | Membantu klasifikasi laporan secara cepat dan konsisten. |
| **Skor Prioritas** | Sistem menghitung prioritas dari severity, urgency, dan jumlah dukungan warga. | Admin dapat melihat laporan yang membutuhkan perhatian lebih dahulu. |
| **Dukungan Warga** | Warga dapat melakukan upvote dan unvote pada laporan tanpa login menggunakan identitas cookie browser. | Dukungan publik dapat dihitung tanpa proses registrasi yang rumit. |
| **Panel Admin** | Admin dapat login, melihat dashboard, mengubah status, mengelola artikel, dan menghapus laporan. | Pengelolaan laporan terpusat dan lebih terkontrol. |

### Fitur Tambahan

- **Peta Interaktif** - Menampilkan lokasi laporan menggunakan Leaflet dan OpenStreetMap.
- **Peringatan Cuaca Ekstrem** - Mengecek kondisi cuaca berdasarkan koordinat atau lokasi laporan.
- **Status Penanganan** - Mendukung status pending, terverifikasi, dalam proses, selesai, dan ditolak.
- **Artikel Publik** - Admin dapat menerbitkan artikel informasi untuk warga.
- **Privasi Pelapor** - Nama pelapor dapat ditampilkan atau disamarkan berdasarkan pilihan saat membuat laporan.
- **Pagination Laporan** - Daftar laporan publik ditampilkan secara bertahap agar mudah dipindai.

---

## Demo dan Screenshot

### Live Demo

**[Kunjungi Website](http://ptrs.site.je/)**

### Screenshot Aplikasi

<div align="center">

<p><strong>Sisi Warga</strong><p>

  <img src="docs/screenshots/homepage.png" alt="Homepage Katstruction" width="800"/>

  <p><em>Homepage - Ringkasan laporan, peta, dan artikel terbaru</em></p>

  <img src="docs/screenshots/page-artikel.png" alt="Menu artikel" width="800"/>

  <p><em>Menu Artikel</em></p>

  <img src="docs/screenshots/menu-report.png" alt="Menu laporan" width="800"/>

  <p><em>Menu Laporan</em></p>

  <img src="docs/screenshots/report-form.png" alt="Form laporan" width="800"/>

  <p><em>Form Laporan - Pengiriman laporan infrastruktur oleh warga</em></p>

  <img src="docs/screenshots/report-view.jpeg" alt="View laporan" width="800"/>

  <p><em>Contoh Laporan infrastruktur oleh warga</em></p>

  <p><strong>Sisi Admin</strong></p>

  <img src="docs/screenshots/admin-login.png" alt="Login admin" width="800"/>

  <p><em>Login Page Admin - Harus memasukkan email dan password</em></p>

  <img src="docs/screenshots/admin-welcome.png" alt="Wpage admin" width="800"/>

  <p><em>Welcome Page Admin</em></p>

  <img src="docs/screenshots/admin-dashboard.jpeg" alt="Dashboard admin" width="800"/>

  <p><em>Dashboard Admin - Monitoring dan pengelolaan laporan</em></p>

  <img src="docs/screenshots/admin-report.png" alt="Laporan admin" width="800"/>

  <p><em>Menu Laporan Admin</em></p>

  <img src="docs/screenshots/admin-report-view.jpeg" alt="View Laporan admin" width="800"/>

  <p><em>View Laporan Admin</em></p>

  <img src="docs/screenshots/admin-artikel.png" alt="Artikel admin" width="800"/>

  <p><em>Menu Artikel Admin</em></p>

  <img src="docs/screenshots/admin-form-artikel.png" alt="Artikel form admin" width="800"/>

  <p><em>Form Artikel Admin</em></p>

</div>

### Video Demo

Tambahkan tautan video demo setelah tersedia: **[Link Video Demo](https://drive.google.com/drive/folders/1g8Au-V1t8ehk1Sifxjh8Dwfv6JukuLEI?usp=sharing)**

---

## Teknologi

### Tech Stack

#### Frontend

```text
Template       : Laravel Blade
CSS            : Tailwind CSS 4
JavaScript     : Vanilla JavaScript dan Fetch API
Build Tool     : Vite
Maps           : Leaflet dan OpenStreetMap
Weather        : Open-Meteo API
```

#### Backend

```text
Runtime        : PHP 8.2+
Framework      : Laravel 12
Database       : SQLite (default) atau MySQL
ORM            : Laravel Eloquent
Authentication  : Laravel session dan custom admin guard
AI Integration  : Google Gemini API
Queue          : Laravel database queue
```

#### DevOps dan Tools

```text
Dependency     : Composer dan npm
Asset Build    : Vite
Testing        : PHPUnit melalui Laravel Test Runner
Code Analysis  : Larastan dan PHPStan
Formatting     : Laravel Pint
Local Server   : Laravel Artisan atau Laragon
```

### Alasan Pemilihan Teknologi

| Teknologi | Alasan Pemilihan |
|-----------|------------------|
| **Laravel** | Menyediakan routing, middleware, validasi, ORM, migrasi, queue, dan struktur MVC yang sesuai untuk aplikasi pelaporan. |
| **Blade dan Tailwind CSS** | Memungkinkan pembuatan antarmuka server-rendered yang cepat, responsif, dan konsisten. |
| **Gemini API** | Membantu menghasilkan analisis laporan dari foto dan deskripsi secara otomatis. |
| **Leaflet dan OpenStreetMap** | Menyediakan peta interaktif dan penanda lokasi tanpa bergantung pada layanan peta berbayar. |
| **MySQL atau SQLite** | Mendukung pengembangan lokal yang ringan sekaligus deployment ke database produksi. |

### Dependencies Utama

```json
{
  "backend": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1"
  },
  "frontend": {
    "tailwindcss": "^4.0.0",
    "vite": "^7.0.7",
    "laravel-vite-plugin": "^2.0.0",
    "axios": "^1.11.0"
  }
}
```

---

## Arsitektur Sistem

### System Architecture

```mermaid
flowchart LR
    User[Warga] --> Browser[Browser dan Blade UI]
    Admin[Admin] --> Browser
    Browser --> Routes[Laravel Web Routes]
    Routes --> Controllers[Controllers]
    Controllers --> Models[Eloquent Models]
    Models --> Database[(SQLite atau MySQL)]
    Controllers --> Gemini[Gemini API]
    Browser --> Maps[Leaflet dan OpenStreetMap]
    Browser --> Weather[Open-Meteo API]
    Queue[Laravel Queue] --> AIJob[AnalyzeReport Job]
    AIJob --> Gemini
    AIJob --> Database
```

### Alur Pelaporan

```mermaid
sequenceDiagram
    actor Warga
    participant UI as Blade UI
    participant App as Laravel
    participant AI as Gemini API
    participant DB as Database

    Warga->>UI: Isi form laporan dan unggah foto
    UI->>App: POST /laporan
    App->>DB: Simpan laporan
    App->>AI: Analisis foto dan deskripsi
    AI-->>App: Severity, urgency, risk, summary
    App->>DB: Simpan hasil AI dan priority score
    App-->>UI: Redirect ke detail laporan
```

### Database Schema

```mermaid
erDiagram
    ADMINS ||--o{ REPORTS : manages
    REPORTS ||--o{ REPORT_UPVOTES : receives
    ADMINS ||--o{ ARTIKELS : publishes

    ADMINS {
        bigint id PK
        string name
        string email UK
        string password
    }

    REPORTS {
        bigint id PK
        bigint admin_id FK
        string reporter_key
        string reporter
        string phone
        string title
        text description
        string image
        decimal latitude
        decimal longitude
        string location
        string severity
        string urgency
        decimal priority_score
        string status
    }

    REPORT_UPVOTES {
        bigint id PK
        bigint report_id FK
        string voter_key UK
    }

    ARTIKELS {
        bigint id PK
        string title
        string slug
        text content
        string status
    }
```

### Folder Structure

```text
itechnoo/
├── app/
│   ├── Console/Commands/       # Perintah Artisan
│   ├── Http/Controllers/       # Controller publik dan admin
│   ├── Jobs/                   # Background job analisis laporan
│   ├── Models/                 # Model Eloquent
│   ├── Services/               # Gemini dan weather service
│   └── Providers/              # Service provider aplikasi
├── bootstrap/                  # Bootstrap aplikasi Laravel
├── config/                    # Konfigurasi aplikasi dan layanan
├── database/
│   ├── factories/              # Factory testing
│   ├── migrations/             # Skema database
│   └── seeders/                # Seeder admin dan data awal
├── public/                    # Entry point dan hasil build aset
├── resources/
│   ├── css/                    # CSS aplikasi
│   ├── js/                     # JavaScript dan bootstrap frontend
│   └── views/                  # Blade views dan komponen
├── routes/web.php              # Route publik dan admin
├── storage/                   # Upload, cache, session, dan log
├── tests/                     # Unit test dan feature test
├── composer.json               # Dependency dan script PHP
├── package.json                # Dependency dan script frontend
└── vite.config.js              # Konfigurasi Vite
```

---

## Instalasi dan Setup

### Prerequisites

Pastikan sudah terpasang:

- **PHP 8.2 atau lebih tinggi**
- **Composer**
- **Node.js dan npm**
- **SQLite atau MySQL**
- **Git**
- **Ekstensi PHP** yang dibutuhkan Laravel, termasuk PDO, OpenSSL, Mbstring, dan Fileinfo

### Langkah Instalasi

#### 1. Clone Repository

```bash
git clone https://github.com/[username]/[repo-name].git
cd itechnoo
```

#### 2. Install Dependencies

```bash
composer install
npm install
```

#### 3. Setup Environment Variables

Salin file environment:

```bash
copy .env.example .env
php artisan key:generate
```

Atau pada macOS/Linux:

```bash
cp .env.example .env
php artisan key:generate
```
API Key yang dapat digunakan: **[Link Drive API Key](https://drive.google.com/drive/folders/1Hl-Nyr0qRRZMLZmuqdUZHgHUWGqqCJAx?usp=sharing)**

Contoh konfigurasi SQLite:

```env
APP_NAME=Katstruction.
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-3.6-flash

# Opsional, sesuai layanan yang digunakan aplikasi
WEATHER_API_KEY=
WINDY_API_KEY=
GROQ_API_KEY=
GEMINI_SUMMARY_API_KEY=
```

Untuk MySQL, gunakan konfigurasi berikut sebagai pengganti SQLite:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=itechnoo
DB_USERNAME=root
DB_PASSWORD=
```

#### 4. Setup Database dan Storage

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Seeder pengembangan membuat akun admin:

```text
Email    : admin@gmail.com
Password : password123
```

Ganti kredensial tersebut untuk lingkungan produksi.

#### 5. Jalankan Aplikasi

Untuk server Laravel dan Vite secara terpisah:

```bash
php artisan serve
npm run dev
```

Aplikasi tersedia di `http://localhost:8000`.

Alternatif menggunakan script Composer:

```bash
composer run dev
```

---

## Penggunaan

### Menjalankan Aplikasi

```bash
# Development server dan Vite
composer run dev

# Build asset production
npm run build

# Jalankan test
php artisan test

# Cek kualitas kode PHP
vendor/bin/phpstan analyse
vendor/bin/pint --test
```

### User Guide

#### Untuk Pengguna Umum

1. Buka halaman utama dan pilih menu **Buat Laporan**.
2. Isi judul, nama, nomor telepon, deskripsi, foto, serta lokasi laporan.
3. Pilih apakah nama ingin ditampilkan dan setujui persyaratan pengiriman.
4. Kirim laporan dan buka halaman detail untuk melihat hasil analisis AI.
5. Pada daftar laporan, tekan **Upvote** untuk memberi dukungan.
6. Tekan kembali tombol dukungan untuk melakukan **Unvote**.
7. Lihat status penanganan, skor prioritas, peta, dan artikel informasi yang tersedia.

#### Untuk Admin

1. Buka `/admin/login` dan masuk menggunakan akun admin.
2. Gunakan dashboard untuk melihat jumlah laporan berdasarkan kategori prioritas dan status.
3. Buka daftar laporan untuk mencari atau memfilter laporan.
4. Buka detail laporan untuk melihat data warga, foto, peta, ringkasan AI admin, dan jumlah dukungan.
5. Ubah status laporan lalu tekan **Submit**.
6. Kelola artikel melalui menu artikel admin.
7. Gunakan fitur hapus hanya ketika laporan memang perlu dihapus.

---

## API Documentation

Proyek ini menggunakan route web Laravel dan belum menyediakan prefix REST `/api`. Request AJAX untuk upvote menggunakan endpoint web berikut.

### Base URL

```text
Development: http://localhost:8000
Production:  http://ptrs.site.je/
Masyarakat : http://ptrs.site.je/
Admin     : http://ptrs.site.je/admin/
```

### Route Publik

```http
GET  /                            # Homepage
GET  /laporan                     # Daftar laporan
GET  /laporan/buat                # Form laporan
POST /laporan                    # Simpan laporan baru
GET  /laporan/{id}                # Detail laporan
POST /laporan/{id}/upvote         # Toggle upvote atau unvote via AJAX
POST /laporan/{id}/analisis-ulang # Jalankan analisis AI ulang
GET  /artikel                     # Daftar artikel
GET  /artikel/{artikel}           # Detail artikel
```

### Route Admin

```http
GET    /admin/login
POST   /admin/login
POST   /admin/logout
GET    /admin/dashboard
GET    /admin/laporan
GET    /admin/laporan/{id}
PATCH  /admin/laporan/{id}/status
DELETE /admin/laporan/{id}
GET    /admin/artikel
GET    /admin/artikel/buat
POST   /admin/artikel/simpan
PATCH  /admin/artikel/{artikel}
DELETE /admin/artikel/{artikel}
```

### Contoh Request Upvote AJAX

```javascript
const response = await fetch('/laporan/1/upvote', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        'X-Requested-With': 'XMLHttpRequest'
    }
});

const data = await response.json();
// { upvote_count: 1, has_upvoted: true, priority_score: 23.4 }
```

---

## Testing

### Menjalankan Test

```bash
php artisan test
```

Test yang tersedia berada di folder `tests/Feature` dan `tests/Unit`. Tambahkan test feature untuk alur pembuatan laporan, autentikasi admin, perubahan status, serta toggle upvote ketika fitur dikembangkan lebih lanjut.

### Static Analysis dan Formatting

```bash
vendor/bin/phpstan analyse
vendor/bin/pint --test
```

### Status Validasi Saat Ini

```text
Laravel tests : 2 passed
Frontend build: npm run build berhasil
```

---

## Lisensi

Proyek ini dilisensikan di bawah **MIT License**. Tambahkan file `LICENSE` berisi teks lisensi MIT sebelum mempublikasikan repository secara resmi.

---

<div align="center">

**Made with care by WNI Suka Bobo for ITECHNO CUP 2026**

</div>
