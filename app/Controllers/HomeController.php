<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        if (isset($_SESSION['user'])) {
            $role = strtolower($_SESSION['user']['role']);

            $routes = [
                'admin' => '/dashboard/admin',
                'guru_bk' => '/dashboard/guru_bk',
                'wali_kelas' => '/dashboard/wali_kelas',
            ];

            if (array_key_exists($role, $routes)) {
                // Redirect selalu — hilangkan pengecekan URI
                header('Location: ' . $routes[$role]);
                exit;
            } else {
                echo "Role tidak dikenali.";
                exit;
            }
        }

        // Kalau belum login, tampilkan home
        include_once __DIR__ . '/../Views/home.php';
    }
}
