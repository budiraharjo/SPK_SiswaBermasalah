<?php
namespace App\Controllers;

use Core\Database;
use PDO;

class PerbandinganController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function index()
    {
        // Ambil periode aktif
        $periodeAktif = $this->getPeriodeAktif();
        $periode = $periodeAktif['periode'] ?? null;
		
		// Hapus data lama sesuai periode
        $this->db->prepare("DELETE FROM tb_perbandingan WHERE periode = :periode")
                 ->execute(['periode' => $periode]);

        // Panggil simpan perbandingan
        $this->simpanPerbandingan($periode);
		
        $data['title'] = "Perbandingan Hasil SAW dan WP";
        $data['periode'] = $periode;
        $data['perbandingan'] = [];

        if ($periode) {
            $data['perbandingan'] = $this->getPerbandinganByPeriode($periode);
        }

        extract($data);
        require __DIR__ . '/../Views/dashboard/perbandingan.php';
    }

	private function getPeriodeAktif()
	{
		$stmt = $this->db->query("SELECT * FROM tb_periode WHERE is_active = 1 LIMIT 1");
		$periode = $stmt->fetch(PDO::FETCH_ASSOC);
		return $periode ?: null;
	}

    private function getPerbandinganByPeriode($periode)
    {
        $sql = "
            SELECT 
                s.id_siswa,
                s.nama,
                saw.nilai_akhir AS nilai_saw,
                saw.status AS status_saw,
                wp.nilai_akhir AS nilai_wp,
                wp.status AS status_wp
            FROM tb_siswa s
            LEFT JOIN tb_hasil_saw saw 
                ON s.id_siswa = saw.id_siswa AND saw.periode = :periode
            LEFT JOIN tb_hasil_wp wp 
                ON s.id_siswa = wp.id_siswa AND wp.periode = :periode
            ORDER BY s.nama ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['periode' => $periode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function simpanPerbandingan($periode)
{
    // Ambil hasil SAW
    $sqlSaw = "SELECT id_siswa, nilai_akhir, peringkat 
               FROM tb_hasil_saw 
               WHERE periode = :periode";
    $stmtSaw = $this->db->prepare($sqlSaw);
    $stmtSaw->execute(['periode' => $periode]);
    $hasilSaw = $stmtSaw->fetchAll(PDO::FETCH_ASSOC);

    // Ambil hasil WP
    $sqlWp = "SELECT id_siswa, nilai_akhir, peringkat 
              FROM tb_hasil_wp 
              WHERE periode = :periode";
    $stmtWp = $this->db->prepare($sqlWp);
    $stmtWp->execute(['periode' => $periode]);
    $hasilWp = $stmtWp->fetchAll(PDO::FETCH_ASSOC);

    // Gabungkan data
    $dataGabung = [];
    foreach ($hasilSaw as $rowSaw) {
        foreach ($hasilWp as $rowWp) {
            if ($rowSaw['id_siswa'] == $rowWp['id_siswa']) {
                $dataGabung[] = [
                    'id_siswa'     => $rowSaw['id_siswa'],
                    'nilai_saw'    => $rowSaw['nilai_akhir'],
                    'peringkat_saw'=> $rowSaw['peringkat'],
                    'nilai_wp'     => $rowWp['nilai_akhir'],
                    'peringkat_wp' => $rowWp['peringkat'],
                    'selisih'      => abs($rowSaw['peringkat'] - $rowWp['peringkat']),
                    'periode'      => $periode
                ];
                break;
            }
        }
    }

    // Simpan ke tb_perbandingan
    $sqlInsert = "INSERT INTO tb_perbandingan 
                  (id_siswa, nilai_saw, peringkat_saw, nilai_wp, peringkat_wp, selisih, periode, created_at) 
                  VALUES (:id_siswa, :nilai_saw, :peringkat_saw, :nilai_wp, :peringkat_wp, :selisih, :periode, NOW())";
    $stmtInsert = $this->db->prepare($sqlInsert);

    foreach ($dataGabung as $data) {
        $stmtInsert->execute($data);
    }
}


}
