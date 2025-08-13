<?php
namespace App\Controllers;

use App\Models\Siswa;
use Core\Database;

require_once __DIR__ . '/../libraries/fpdf/fpdf.php';

class PDF extends \FPDF
{
    function Header()
    {
        $this->Image('assets/images/kop_surat.jpg', 3, 3, 205);
        $this->Ln(40);
    }
}

class LaporanSiswaController
{
    protected $siswaModel;
    protected $db;

    public function __construct()
    {
        $this->siswaModel = new Siswa();
        $this->db = Database::getConnection();
    }

    public function index()
    {
        $search  = $_GET['search'] ?? '';
        $periode = $_GET['periode'] ?? '';
        $page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit   = 5;
        $offset  = ($page - 1) * $limit;

        // Ambil daftar periode untuk filter
        $stmt = $this->db->prepare("SELECT * FROM tb_periode ORDER BY created_at DESC");
        $stmt->execute();
        $data['periodes'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Filter berdasarkan periode jika dipilih
        $query = "SELECT * FROM tb_siswa";
        $params = [];

        if ($periode) {
            $query .= " WHERE tahun_ajaran = ?";
            $params[] = $periode;

            if ($search) {
                $query .= " AND (nama LIKE ? OR nisn LIKE ? OR kelas LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
        } else {
            if ($search) {
                $query .= " WHERE (nama LIKE ? OR nisn LIKE ? OR kelas LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
        }

        $countQuery = str_replace("SELECT *", "SELECT COUNT(*) AS cnt", $query);
        $stmtCount = $this->db->prepare($countQuery);
        $stmtCount->execute($params);
        $totalData = (int)$stmtCount->fetch(\PDO::FETCH_ASSOC)['cnt'];

        $query .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $data['siswa'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $data['search'] = $search;
        $data['periode_selected'] = $periode;
        $data['page'] = $page;
        $data['total'] = $totalData;
        $data['limit'] = $limit;
        $data['title'] = "Laporan Data Siswa";

        extract($data);
        require __DIR__ . '/../Views/dashboard/laporan_siswa.php';
    }

    public function downloadPdf()
    {
        $search  = $_GET['search'] ?? '';
        $periode = $_GET['periode'] ?? '';

        $query = "SELECT * FROM tb_siswa";
        $params = [];

        if ($periode) {
            $query .= " WHERE tahun_ajaran = ?";
            $params[] = $periode;

            if ($search) {
                $query .= " AND (nama LIKE ? OR nisn LIKE ? OR kelas LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
        } else {
            if ($search) {
                $query .= " WHERE (nama LIKE ? OR nisn LIKE ? OR kelas LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
        }

        $query .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $siswa = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $pdf = new PDF("P", "mm", "A4");
        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 14);
        $pdf->Cell(0, 10, "Laporan Data Siswa", 0, 1, "C");
        $pdf->Ln(5);

        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell(10, 7, "No", 1);
        $pdf->Cell(25, 7, "NISN", 1);
        $pdf->Cell(50, 7, "Nama", 1);
        $pdf->Cell(25, 7, "Kelas", 1);
        $pdf->Cell(25, 7, "J.Kelamin", 1);
        $pdf->Cell(30, 7, "Thn Ajaran", 1);
        $pdf->Cell(25, 7, "Tgl Dibuat", 1);
        $pdf->Ln();

        $pdf->SetFont("Arial", "", 10);
        $no = 1;
        foreach ($siswa as $row) {
            $pdf->Cell(10, 7, $no++, 1);
            $pdf->Cell(25, 7, $row['nisn'], 1);
            $pdf->Cell(50, 7, $row['nama'], 1);
            $pdf->Cell(25, 7, $row['kelas'], 1);
            $pdf->Cell(25, 7, $row['jenis_kelamin'], 1);
            $pdf->Cell(30, 7, $row['tahun_ajaran'], 1);
            $pdf->Cell(25, 7, date("d-m-Y", strtotime($row['created_at'])), 1);
            $pdf->Ln();
        }

        $pdf->Output("D", "laporan_siswa.pdf");
    }
}
