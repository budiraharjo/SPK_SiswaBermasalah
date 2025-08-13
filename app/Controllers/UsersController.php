<?php
namespace App\Controllers;

use App\Models\Users;

class UsersController
{
    public function index()
    {
        $model = new Users();

        // Ambil parameter search & page
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int) $_GET['page'] : 1;

        // Config pagination
        $perPage = 15; // tampilkan 15 data / halaman
        $total = (int) $model->countAll($search); // total data (int)
        $totalPages = ($total > 0) ? (int) ceil($total / $perPage) : 1;

        // Jika page diluar range, koreksi
        if ($page > $totalPages) $page = $totalPages;
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $perPage;

        // Ambil data untuk halaman ini
        $users = $model->getAll($perPage, $offset, $search);

        // Variabel yang digunakan di view (samakan dengan data-siswa)
        $currentPage = $page;
        $totalPages = $totalPages;
        $perPage = $perPage;
        $total = $total;
        $s = $search; // optional, kalau mau pakai $s
        $qSearch = ($search !== '') ? '&search=' . urlencode($search) : '';

        // Hitung window pagination (maks 5 nomor)
        $window = 5;
        $start = max(1, $currentPage - (int)floor($window / 2));
        $end = $start + $window - 1;
        if ($end > $totalPages) {
            $end = $totalPages;
            $start = max(1, $end - $window + 1);
        }
        // expose start & end juga (compatibility)
        $startPage = $start;
        $endPage = $end;

        // offset yang view kadang pakai
        $offset = $offset;

        // Include view
        include __DIR__ . '/../Views/dashboard/data_user.php';
    }

    public function store()
    {
        $model = new Users();
        $model->create($_POST);
        header('Location: /dashboard/data-user');
        exit;
    }

    public function update($id)
    {
        $model = new Users();
        $model->update($id, $_POST);
        header('Location: /dashboard/data-user');
        exit;
    }

    public function delete($id)
    {
        $model = new Users();
        $model->delete($id);
        header('Location: /dashboard/data-user');
        exit;
    }
}
