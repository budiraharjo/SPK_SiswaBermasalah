<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database;
use PDO;

class BatasMasalahController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];
        if ($search !== '') {
            $where = "WHERE metode LIKE :search";
            $params[':search'] = "%$search%";
        }

        // Hitung total data
        $stmtTotal = $this->db->prepare("SELECT COUNT(*) FROM tb_batas_masalah $where");
        $stmtTotal->execute($params);
        $total = $stmtTotal->fetchColumn();
        $totalPages = ceil($total / $perPage);

        // Ambil data paginasi
        $stmt = $this->db->prepare("SELECT * FROM tb_batas_masalah $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'rows' => $rows,
            'search' => $search,
            'currentPage' => $page,
            'total' => $total,
            'totalPages' => $totalPages
        ];

        extract($data);
        require __DIR__ . '/../Views/dashboard/batas_masalah.php';
    }

	public function store()
	{
		$metode = $_POST['metode'] ?? '';
		$batas_min = $_POST['batas_min'] ?? '';
		
		if ($metode && $batas_min !== '') {
			// Cek apakah metode sudah ada
			$stmtCheck = $this->db->prepare("SELECT COUNT(*) FROM tb_batas_masalah WHERE metode = ?");
			$stmtCheck->execute([$metode]);
			$exists = $stmtCheck->fetchColumn();

			if ($exists > 0) {
				$_SESSION['error'] = "Metode '$metode' sudah memiliki batas masalah.";
			} else {
				$stmt = $this->db->prepare("
					INSERT INTO tb_batas_masalah (metode, batas_min, created_at) 
					VALUES (?, ?, NOW())
				");
				$stmt->execute([$metode, $batas_min]);
				$_SESSION['success'] = "Batas masalah untuk metode '$metode' berhasil ditambahkan.";
			}
		} else {
			$_SESSION['error'] = "Semua field wajib diisi.";
		}

		header('Location: /dashboard/batas-masalah');
		exit;
	}

    public function update($id)
    {
        $metode = $_POST['metode'] ?? '';
        $batas_min = $_POST['batas_min'] ?? '';

        if ($metode && $batas_min !== '' ) {
            $stmt = $this->db->prepare("UPDATE tb_batas_masalah SET metode=?, batas_min=?  WHERE id_batas=?");
            $stmt->execute([$metode, $batas_min, $id]);
            $_SESSION['success'] = "Batas masalah berhasil diupdate.";
        } else {
            $_SESSION['error'] = "Semua field wajib diisi.";
        }
        header('Location: /dashboard/batas-masalah');
        exit;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tb_batas_masalah WHERE id_batas=?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Batas masalah berhasil dihapus.";
        header('Location: /dashboard/batas-masalah');
        exit;
    }
}
