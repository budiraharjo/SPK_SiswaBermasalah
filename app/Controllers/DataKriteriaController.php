<?php
namespace App\Controllers;

use App\Models\Kriteria;


class DataKriteriaController
{
    protected $kModel;
    protected $kmModel;

    public function __construct()
    {
        $this->kModel = new Kriteria(); 
    }

    // List kriteria umum
    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        $total = $this->kModel->countAll($search);
        $kriteria = $this->kModel->paginate($perPage, $offset, $search);

        $currentPage = $page;
        $totalPages = ($total > 0) ? (int) ceil($total / $perPage) : 1;

        include __DIR__ . '/../Views/dashboard/kriteria.php';
    }

    public function store()
    {
        $this->kModel->create($_POST);
        header('Location: /dashboard/data-kriteria');
        exit;
    }

    public function update($id)
    {
        $this->kModel->update($id, $_POST);
        header('Location: /dashboard/data-kriteria');
        exit;
    }

    public function delete($id)
    {
        $this->kModel->delete($id);
        header('Location: /dashboard/data-kriteria');
        exit;
    }

}
