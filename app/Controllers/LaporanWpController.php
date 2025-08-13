<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database;
use FPDF;

require_once __DIR__ . '/../libraries/fpdf/fpdf.php';

class PDFWp extends FPDF
{
    function Header()
    {
        $this->Image('assets/images/kop_surat.jpg', 3, 3, 205);
        $this->Ln(40);
    }
}

class LaporanWpController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function index()
    {
        $periodeAktif = $_GET['periode'] ?? '';
        $search       = $_GET['search'] ?? '';
        $page         = max(1, (int)($_GET['page'] ?? 1));
        $limit        = 5;
        $offset       = ($page - 1) * $limit;

        // Ambil semua periode
        $periodeList = $this->db->query("SELECT * FROM tb_periode ORDER BY created_at DESC")->fetchAll(\PDO::FETCH_ASSOC);

        // Query hasil WP join siswa
        $sql = "SELECT h.*, s.nama, s.nisn, s.kelas, p.periode 
                FROM tb_hasil_wp h
                JOIN tb_siswa s ON s.id_siswa = h.id_siswa
                JOIN tb_periode p ON p.periode = h.periode
                WHERE 1=1";

        $params = [];

        if ($periodeAktif !== '') {
            $sql .= " AND h.periode = :periode";
            $params['periode'] = $periodeAktif;
        }

        if (trim($search) !== '') {
            $sql .= " AND (s.nama LIKE :search OR s.nisn LIKE :search OR s.kelas LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $sql .= " ORDER BY h.peringkat ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->bindValue(":limit", $limit, \PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $hasilWp = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Hitung total data
        $countSql = "SELECT COUNT(*) as cnt
                     FROM tb_hasil_wp h
                     JOIN tb_siswa s ON s.id_siswa = h.id_siswa
                     WHERE 1=1";
        if ($periodeAktif !== '') {
            $countSql .= " AND h.periode = :periode";
        }
        if (trim($search) !== '') {
            $countSql .= " AND (s.nama LIKE :search OR s.nisn LIKE :search OR s.kelas LIKE :search)";
        }
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue(":{$key}", $value);
        }
        $countStmt->execute();
        $totalData = (int) $countStmt->fetch(\PDO::FETCH_ASSOC)['cnt'];

        $totalPages = ceil($totalData / $limit);

        $data = [
            'title'       => 'Laporan WP',
            'hasilWp'    => $hasilWp,
            'periodeList' => $periodeList,
            'periode'     => $periodeAktif,
            'search'      => $search,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'totalData'   => $totalData,
            'limit'       => $limit
        ];

        extract($data);
        require __DIR__ . '/../Views/dashboard/laporan_wp.php';
    }

    public function downloadPdf()
    {
        $periodeAktif = $_GET['periode'] ?? '';
        $search       = $_GET['search'] ?? '';

        $sql = "SELECT h.*, s.nama, s.nisn, s.kelas 
                FROM tb_hasil_wp h
                JOIN tb_siswa s ON s.id_siswa = h.id_siswa
                WHERE 1=1";
        $params = [];
        if ($periodeAktif !== '') {
            $sql .= " AND h.periode = :periode";
            $params['periode'] = $periodeAktif;
        }
        if (trim($search) !== '') {
            $sql .= " AND (s.nama LIKE :search OR s.nisn LIKE :search OR s.kelas LIKE :search)";
            $params['search'] = "%{$search}%";
        }
        $sql .= " ORDER BY h.peringkat ASC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $pdf = new PDFWp();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'Laporan Hasil WP',0,1,'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,8,'No',1);
        $pdf->Cell(30,8,'NISN',1);
        $pdf->Cell(50,8,'Nama',1);
        $pdf->Cell(20,8,'Kelas',1);
        $pdf->Cell(30,8,'Nilai Akhir',1);
        $pdf->Cell(20,8,'Peringkat',1);
        $pdf->Ln();

        $pdf->SetFont('Arial','',10);
        $no = 1;
        foreach ($rows as $row) {
            $pdf->Cell(10,8,$no++,1);
            $pdf->Cell(30,8,$row['nisn'],1);
            $pdf->Cell(50,8,$row['nama'],1);
            $pdf->Cell(20,8,$row['kelas'],1);
            $pdf->Cell(30,8,$row['nilai_akhir'],1);
            $pdf->Cell(20,8,$row['peringkat'],1);
            $pdf->Ln();
        }

        $pdf->Output('I', 'laporan_wp.pdf');
    }
}
