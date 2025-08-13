<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database;
use PDO;
use FPDF;

require_once __DIR__ . '/../libraries/fpdf/fpdf.php';

class PDF_Perbandingan extends FPDF
{
    function Header()
    {
        $this->Image('assets/images/kop_surat.jpg', 3, 3, 205);
        $this->Ln(40);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Laporan Perbandingan SAW dan WP', 0, 1, 'C');
        $this->Ln(5);
    }
}

class LaporanPerbandinganController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function index()
    {
        $periode = $_GET['periode'] ?? '';
        $search  = $_GET['search'] ?? '';
        $page    = max(1, intval($_GET['page'] ?? 1));
        $limit   = 5;
        $offset  = ($page - 1) * $limit;

        // Ambil daftar periode
        $periodeList = $this->db->query("SELECT DISTINCT periode FROM tb_periode ORDER BY periode DESC")->fetchAll(PDO::FETCH_ASSOC);

        // Query data perbandingan
		$sql = "SELECT p.id, s.nisn, s.nama, s.kelas, s.jenis_kelamin, s.tahun_ajaran, 
					   p.periode, p.nilai_saw, p.peringkat_saw, 
					   p.nilai_wp, p.peringkat_wp, p.selisih
				FROM tb_perbandingan p
				JOIN tb_siswa s ON p.id_siswa = s.id_siswa
				WHERE 1=1";
        $params = [];

        if (!empty($periode)) {
            $sql .= " AND p.periode = :periode";
            $params[':periode'] = $periode;
        }

        if (!empty($search)) {
            $sql .= " AND (s.nama LIKE :search OR s.nisn LIKE :search OR s.kelas LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY s.nama ASC LIMIT :offset, :limit";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $dataPerbandingan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Hitung total data
        $countSql = "SELECT COUNT(*) FROM tb_perbandingan p
                     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
                     WHERE 1=1";
        $countParams = [];

        if (!empty($periode)) {
            $countSql .= " AND p.periode = :periode";
            $countParams[':periode'] = $periode;
        }

        if (!empty($search)) {
            $countSql .= " AND (s.nama LIKE :search OR s.nisn LIKE :search OR s.kelas LIKE :search)";
            $countParams[':search'] = '%' . $search . '%';
        }

        $countStmt = $this->db->prepare($countSql);
        foreach ($countParams as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $totalData = $countStmt->fetchColumn();
        $totalPages = ceil($totalData / $limit);

		$data = [
			'periodeList' => $periodeList,
			'periode'     => $periode,
			'search'      => $search,
			'dataPerbandingan' => $dataPerbandingan,
			'page'        => $page,
			'limit'       => $limit,
			'totalData'   => $totalData,
			'totalPages'  => $totalPages
		];

		extract($data);
		require __DIR__ . '/../Views/dashboard/laporan_perbandingan.php';

    }

    public function downloadPdf()
    {
        $periode = $_GET['periode'] ?? '';
        $search  = $_GET['search'] ?? '';

		$sql = "SELECT p.id, s.nisn, s.nama, s.kelas, s.jenis_kelamin, s.tahun_ajaran, 
					   p.periode, p.nilai_saw, p.peringkat_saw, 
					   p.nilai_wp, p.peringkat_wp, p.selisih
				FROM tb_perbandingan p
				JOIN tb_siswa s ON p.id_siswa = s.id_siswa
				WHERE 1=1";
        $params = [];

        if (!empty($periode)) {
            $sql .= " AND p.periode = :periode";
            $params[':periode'] = $periode;
        }

        if (!empty($search)) {
            $sql .= " AND (s.nama LIKE :search OR s.nisn LIKE :search OR s.kelas LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY s.nama ASC";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new PDF_Perbandingan();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(10, 7, 'No', 1);
		$pdf->Cell(25, 7, 'NISN', 1);
		$pdf->Cell(30, 7, 'Nama', 1);
		$pdf->Cell(10, 7, 'Kelas', 1);
		$pdf->Cell(20, 7, 'JK', 1);
		$pdf->Cell(20, 7, 'SAW', 1);
		$pdf->Cell(15, 7, 'RkS', 1);
		$pdf->Cell(24, 7, 'WP', 1);
		$pdf->Cell(15, 7, 'RkW', 1);
		$pdf->Cell(25, 7, 'Selisih', 1);
		$pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        $no = 1;
		foreach ($data as $row) {
			$pdf->Cell(10, 6, $no++, 1);
			$pdf->Cell(25, 6, $row['nisn'], 1);
			$pdf->Cell(30, 6, $row['nama'], 1);
			$pdf->Cell(10, 6, $row['kelas'], 1);
			$pdf->Cell(20, 6, $row['jenis_kelamin'], 1);
			$pdf->Cell(20, 6, $row['nilai_saw'], 1);
			$pdf->Cell(15, 6, $row['peringkat_saw'], 1);
			$pdf->Cell(24, 6, $row['nilai_wp'], 1);
			$pdf->Cell(15, 6, $row['peringkat_wp'], 1);
			$pdf->Cell(25, 6, $row['selisih'], 1);
			$pdf->Ln();
		}

        $pdf->Output('I', 'laporan_perbandingan.pdf');
    }
}
