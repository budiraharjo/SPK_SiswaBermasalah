<?php
namespace App\Controllers;

use App\Models\Siswa;

class SiswaController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Siswa();
    }

    /**
     * Tampilkan daftar siswa (dengan search & pagination)
     * URL: /dashboard/data-siswa?search=...&page=...
     */
    public function index()
    {
        // Ambil parameter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        // Config pagination
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        // Hitung total & ambil data
        $total = $this->model->countAll($search);
        $siswa = $this->model->paginate($perPage, $offset, $search);

        // Variabel untuk view
        $currentPage = $page;
        $totalPages = ($total > 0) ? (int) ceil($total / $perPage) : 1;
        $perWindow = 5; // tampilkan maksimal 5 nomor halaman

        // Include view (path sesuai permintaan)
        include __DIR__ . '/../Views/dashboard/siswa.php';
    }

    public function store()
    {
        // validasi sederhana bisa ditambah
        $this->model->create($_POST);
        header('Location: /dashboard/data-siswa');
        exit;
    }

    public function update($id)
    {
        $this->model->update($id, $_POST);
        header('Location: /dashboard/data-siswa');
        exit;
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header('Location: /dashboard/data-siswa');
        exit;
    }

    // (optional) keep find/create/edit methods if needed elsewhere
}
