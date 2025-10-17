🚀 Tukel App

Tukel App adalah platform berbasis web untuk mengatur dan mendistribusikan tugas belajar antar anggota tim.
Dibangun menggunakan Laravel 10, Inertia.js, Vue 3, dan Vite.

📦 Tech Stack

Backend: Laravel 10 (Sanctum, REST API)
Frontend: Vue 3 + Inertia.js
Bundler: Vite
Auth: Laravel Breeze + Google OAuth
Database: MySQL / MariaDB
Styling: TailwindCSS

⚙️ Instalasi dan Setup (Local)

1️⃣ Clone Repository

cd tukel-app

2️⃣ Install Dependencies
composer install
npm install

3️⃣ Buat File .env
cp .env.example .env

Lalu ubah variabel berikut sesuai environment kamu:

APP_NAME="Tukel App"
APP_URL=http://127.0.0.1:8000
VITE_APP_URL=${APP_URL}

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tukel_app
DB_USERNAME=root
DB_PASSWORD=

# Optional (untuk login Google)
GOOGLE_CLIENT_ID=xxxxx
GOOGLE_CLIENT_SECRET=xxxxx
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

4️⃣ Generate Key
php artisan key:generate

5️⃣ Jalankan Migrasi
php artisan migrate

🧩 Jalankan Aplikasi (Development)

Buka dua terminal terpisah:

Terminal 1 — Laravel backend
php artisan serve

Terminal 2 — Vite dev server
npm run dev


Akses aplikasi di http://127.0.0.1:8000

🧱 Build untuk Production
1️⃣ Build Frontend
npm run build

2️⃣ Jalankan Optimisasi Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache

3️⃣ Pastikan .env di server production sudah benar
APP_ENV=production
APP_DEBUG=false
APP_URL=https://iswara-app.site
VITE_APP_URL=${APP_URL}

🔐 Login dan Logout

Login via email/password atau Google OAuth
Logout ditangani via route POST /logout dengan CSRF protection

🧾 Fitur Utama

    📘 Dashboard Assignment

    ✅ Konfirmasi Distribusi Tugas

    🧮 Skor Otomatis Berdasarkan Soal yang Selesai

    📤 Export Data ke CSV

    👥 Manajemen Anggota Tim

    🔒 Login dengan Google

    🧰 Commands Berguna

Perintah	Fungsi
php artisan migrate:fresh --seed	Reset dan isi ulang database
php artisan route:list	Lihat semua route
npm run dev	Jalankan frontend dev server
npm run build	Build untuk production
php artisan serve	Jalankan backend Laravel
php artisan optimize:clear	Bersihkan cache konfigurasi

🧑‍💻 Kontributor

- Aflaha
- Ridho
- Iswara
- Della
- Alima

📄 Lisensi

    MIT License © 2025 — LHCA Group 4