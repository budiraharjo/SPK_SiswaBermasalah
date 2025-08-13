<?php
namespace App\Controllers;

use App\Models\KriteriaMetode;

class KriteriaMetodeController
{
    protected $kmModel;

    public function __construct()
    {
        $this->kmModel = new KriteriaMetode();
    }

    // Index untuk menampilkan semua kriteria-metode (tanpa filter)
	public function indexMetode()
	{
		$search = isset($_GET['search']) ? trim($_GET['search']) : '';
		$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
		$perPage = 15;
		$offset = ($page - 1) * $perPage;

		$total = $this->kmModel->countAll($search);
		$rows = $this->kmModel->all($perPage, $offset, $search);

		// Ambil semua kriteria untuk dropdown
		$allKriteria = $this->kmModel->getAllKriteria();

		$currentPage = $page;
		$totalPages = ($total > 0) ? (int) ceil($total / $perPage) : 1;

		include __DIR__ . '/../Views/dashboard/kriteria_metode.php';
	}

	public function storeMetode()
	{
		if ($this->kmModel->create($_POST)) {
			$_SESSION['success'] = "Data bobot kriteria berhasil ditambahkan.";
		} else {
			$_SESSION['error'] = "Data bobot kriteria untuk metode {$_POST['metode']} sudah ada.";
		}

		header('Location: /dashboard/kriteria-metode');
		exit;
	}

    public function updateMetode($id)
    {
        $this->kmModel->update($id, $_POST);
        header('Location: /dashboard/kriteria-metode');
        exit;
    }

    public function deleteMetode($id)
    {
        $this->kmModel->delete($id);
        header('Location: /dashboard/kriteria-metode');
        exit;
    }

}
