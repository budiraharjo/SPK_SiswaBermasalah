<?php
namespace App\Controllers;

use Core\Database;
use PDO;

class DashboardController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function admin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
            header('Location: /');
            exit;
        }
		
		$data = $this->getDashboardData();
		extract($data);
	
        require_once __DIR__ . '/../Views/dashboard/admin.php';
    }

    public function guru_bk()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Guru_BK') {
            header('Location: /');
            exit;
        }

		$data = $this->getDashboardData();
		extract($data);
		
        require_once __DIR__ . '/../Views/dashboard/admin.php';
    }

    public function wali_kelas()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Wali_Kelas') {
            header('Location: /');
            exit;
        }

		$data = $this->getDashboardData();
		extract($data);
		
        require_once __DIR__ . '/../Views/dashboard/admin.php';
    }

	private function getDashboardData(): array
	{
		// Hitung jumlah guru
		$stmt = $this->db->query("
			SELECT COUNT(*) 
			FROM tb_user 
			WHERE role IN ('Wali_Kelas', 'Guru_BK')
		");
		$jumlah_guru = (int) $stmt->fetchColumn();

		// Hitung jumlah siswa total, laki, perempuan
		$stmt = $this->db->query("
			SELECT 
				COUNT(*) AS total,
				SUM(CASE WHEN jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) AS laki,
				SUM(CASE WHEN jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) AS perempuan
			FROM tb_siswa
		");
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);

		$jumlah_siswa     = (int) ($row['total'] ?? 0);
		$jumlah_laki      = (int) ($row['laki'] ?? 0);
		$jumlah_perempuan = (int) ($row['perempuan'] ?? 0);

		// Persentase aman
		$persen_laki      = $jumlah_siswa > 0 ? round(($jumlah_laki / $jumlah_siswa) * 100, 2) : 0;
		$persen_perempuan = $jumlah_siswa > 0 ? round(($jumlah_perempuan / $jumlah_siswa) * 100, 2) : 0;

		return [
			'jumlah_guru'        => $jumlah_guru,
			'jumlah_siswa'       => $jumlah_siswa,
			'jumlah_laki'        => $jumlah_laki,
			'jumlah_perempuan'   => $jumlah_perempuan,
			'persen_laki'        => $persen_laki,
			'persen_perempuan'   => $persen_perempuan,
		];
	}

}
