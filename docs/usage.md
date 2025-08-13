# Panduan Penggunaan Mezur Framework

Selamat datang di Mezur Framework! Framework PHP ringan berbasis MVC ini dirancang untuk kesederhanaan dan kemudahan penggunaan.

---

## 📁 Struktur Direktori

📁 MezurFramework/
├── 📁 app/
│   ├── 📁 Controllers/
│   │   └── HomeController.php
│   ├── 📁 Models/
│   │   └── User.php
│   └── 📁 Views/
│       ├── home.php
│       └── 404.php
├── 📁 config/
│   ├── config.php
│   └── .env.example
├── 📁 core/
│   ├── App.php
│   ├── CLI.php
│   ├── Controller.php
│   ├── Model.php
│   ├── Route.php
│   ├── Router.php
│   ├── Migration.php
│   ├── Tester.php
│   └── Helpers.php
├── 📁 database/
│   └── 📁 migrations/
│       └── 2025_01_create_users_table.php
├── 📁 docs/
│   └── usage.md
├── 📁 public/
│   ├── index.php
│   └── 📁 assets/
│       ├── 📁 css/
│       │   └── style.css
│       └── 📁 js/
│           └── script.js
├── 📁 routes/
│   └── web.php
├── 📁 storage/
│   └── 📁 log/
│       └── .gitkeep
├── 📁 tests/
│   └── ExampleTest.php
├── 📁 vendor/ (di-generate Composer)
├── .gitignore
├── composer.json
├── composer.lock
├── mezur
└── README.md




---

## 🚀 Menjalankan Aplikasi

1. Pastikan kamu sudah menjalankan:

```bash
composer install


2. Jalankan server:
php mezur serve
Akses aplikasi di: http://localhost:8000


✨ Menambahkan Rute Baru
Edit file routes/web.php:

Route::get('/about', 'HomeController@about');



🧠 Menambahkan Controller Baru
Buat file baru di app/Controllers/ExampleController.php:

class ExampleController extends Controller {
    public function index() {
        $this->view('example');
    }
}



🎨 Menambahkan View
Tambahkan file app/Views/example.php:

<h1>Ini halaman contoh!</h1>


🛠️ Menjalankan Migrasi
Buat file migrasi di database/migrations/, lalu jalankan:

php mezur migrate


🧪 Menjalankan Test
Letakkan file test di tests/, lalu jalankan:
php mezur test

📝 .env Configuration
Salin .env.example menjadi .env dan sesuaikan:

DB_HOST=localhost
DB_NAME=mezur
DB_USER=root
DB_PASS=


📌 Catatan
Pastikan semua class mengikuti PSR-4.

Framework ini cocok untuk skala kecil dan menengah.

Direkomendasikan menggunakan PHP versi 8.0 ke atas.


Selamat menggunakan Mezur Framework! 🎉


Jika kamu ingin saya buatkan file lain seperti `README.md`, `.gitignore`, atau bagian lain dari struktur project, tinggal beri tahu saja.
