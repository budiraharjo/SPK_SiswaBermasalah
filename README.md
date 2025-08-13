# Mezur Framework

Mini PHP Framework yang terinspirasi dari Laravel, menggunakan arsitektur MVC dan Composer. Cocok untuk pembelajaran atau proyek kecil-menengah.

## 📁 Struktur Folder

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


Lihat file `docs/usage.md` atau petunjuk di bawah ini.

## 🚀 Instalasi

```bash
git clone https://github.com/budiraharjo/MezurFramework.git
cd MezurFramework
composer install
php mezur serve
