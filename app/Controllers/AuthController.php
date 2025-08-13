<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function login()
    {
		
		// Cek CSRF token
        $postCsrf = $_POST['csrf_token'] ?? '';
        if (empty($postCsrf) || !hash_equals($_SESSION['csrf_token'] ?? '', $postCsrf)) {
            $_SESSION['error'] = 'Invalid request (CSRF).';
            header('Location: /');
            exit;
        }
		
        $username = $_POST['username'] ?? '';
        $password = md5($_POST['password_hash'] ?? '');

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user || $password !== $user['password_hash']) {
            $_SESSION['error'] = 'Username atau password salah.';
            header('Location: /');
            exit;
        }



        // Admin atau Pegawai
        if (in_array(strtolower($user['role']), ['admin'])) {
            $adminModel = new \App\Models\Admin();
            $adminpeg = $adminModel->findByUsername($username); // Asumsikan username = niy

            if ($adminpeg) {
                $user['username'] 	= $adminpeg['username'];
                $user['nama'] 		= $adminpeg['nama'];
            } else {
                $_SESSION['error'] = 'Data Admin / Pegawai tidak ditemukan.';
                header('Location: /');
                exit;
            }
        }

        $_SESSION['user'] = $user;

        // Redirect
        switch (strtolower($user['role'])) {
            case 'admin':
                header('Location: /dashboard/admin');
                break;
            case 'guru_bk':
                header('Location: /dashboard/guru_bk');
                break;
            case 'wali_kelas': // disamakan
                header('Location: /dashboard/wali_kelas');
                break;
            default:
				unset($_SESSION['user']); // tambahkan ini
				$_SESSION['error'] = 'Role Tidak Dikenlai.';
				header('Location: /');
				exit;
        }

        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    public static function generateCsrf()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
	
}
