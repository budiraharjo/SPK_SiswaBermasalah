<?php
namespace App\Models;

use Core\Database;
use PDO;

class PenilaianSaw
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

	public function getAll()
	{
		$sql = "
			SELECT 
				n.id_nilai,
				s.id_siswa,
				s.nama,
				k.nama_kriteria,
				km.metode,
				n.nilai,
				n.periode
			FROM tb_nilai n
			JOIN tb_siswa s ON n.id_siswa = s.id_siswa
			JOIN tb_kriteria_metode km ON n.id_k_metode = km.id_k_metode
			JOIN tb_kriteria k ON km.id_kriteria = k.id_kriteria
			WHERE km.metode = 'SAW'
			ORDER BY s.id_siswa, km.id_k_metode
		";

		$stmt = $this->db->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    public function insert($data)
    {
        $sql = "INSERT INTO tb_nilai (id_siswa, id_k_metode, periode, nilai) VALUES (:id_siswa, :id_k_metode, :periode, :nilai)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateData($data)
    {
        $sql = "UPDATE tb_nilai SET nilai = :nilai WHERE id_nilai = :id_nilai";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

	public function deleteById($id)
	{
		$stmt = $this->db->prepare("DELETE FROM tb_nilai WHERE id_nilai = ?");
		$stmt->execute([$id]);
	}

    public function getSiswa()
    {
        $stmt = $this->db->query("SELECT * FROM tb_siswa ORDER BY nama ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function getKriteria()
	{
		$sql = "
			SELECT
				km.id_k_metode,
				km.id_kriteria,
				k.nama_kriteria,
				km.bobot,
				km.sifat,
				km.metode
			FROM tb_kriteria_metode km
			JOIN tb_kriteria k ON km.id_kriteria = k.id_kriteria
			WHERE km.metode = 'SAW'
			ORDER BY k.nama_kriteria ASC
		";
		
		$stmt = $this->db->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findBySiswaKriteria($id_siswa, $id_k_metode)
	{
		$sql = "SELECT * FROM tb_nilai WHERE id_siswa = :id_siswa AND id_k_metode = :id_k_metode";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'id_siswa' => $id_siswa,
			'id_k_metode' => $id_k_metode
		]);
		return $stmt->fetch(PDO::FETCH_ASSOC); // return satu baris data atau false
	}

	public function getActive()
	{
		$stmt = $this->db->query("SELECT * FROM tb_periode WHERE is_active = 1 LIMIT 1");
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
    public function getKriteriaSAW()
    {
        $sql = "
            SELECT 
                km.id_k_metode,
                km.id_kriteria,
                k.kode,
                k.nama_kriteria,
                km.bobot,
                km.sifat,
                km.urutan
            FROM tb_kriteria_metode km
            JOIN tb_kriteria k ON km.id_kriteria = k.id_kriteria
            WHERE km.metode = 'SAW'
            ORDER BY km.urutan ASC, k.nama_kriteria ASC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil semua nilai untuk periode tertentu namun hanya untuk kriteria yang adalah SAW
     * Mengembalikan rows: id_nilai, id_siswa, nama, id_k_metode, nilai, bobot, sifat
     */
    public function getNilaiByPeriodeSAW($periode)
    {
        $sql = "
            SELECT 
                n.id_nilai,
                n.id_siswa,
                s.nama,
                n.id_k_metode,
                n.nilai,
                km.bobot,
                km.sifat
            FROM tb_nilai n
            JOIN tb_siswa s ON n.id_siswa = s.id_siswa
            JOIN tb_kriteria_metode km ON n.id_k_metode = km.id_k_metode
            WHERE n.periode = :periode
              AND km.metode = 'SAW'
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['periode' => $periode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * (Optional) ambil periode aktif via model ini jika kamu mau gunakan dari sini
     */
    public function getPeriodeAktif()
    {
        $stmt = $this->db->query("SELECT * FROM tb_periode WHERE is_active = 1 LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	/**
	 * Ambil batas masalah (batas_min) untuk metode (SAW/WP)
	 * Return float (0 jika tidak ada)
	 */
	public function getBatasMasalah($metode = 'SAW')
	{
		$stmt = $this->db->prepare("SELECT batas_min FROM tb_batas_masalah WHERE metode = :metode ORDER BY id_batas DESC LIMIT 1");
		$stmt->execute(['metode' => $metode]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? floatval($row['batas_min']) : 0.0;
	}

	/**
	 * Hapus hasil per periode (supaya tidak duplikat)
	 */
	public function deleteHasilByPeriode($periode)
	{
		$stmt = $this->db->prepare("DELETE FROM tb_hasil_saw WHERE periode = :periode");
		return $stmt->execute(['periode' => $periode]);
	}

	/**
	 * Simpan 1 baris hasil SAW ke tb_hasil_saw
	 * $data: ['id_siswa','nilai_akhir','peringkat','status','id_user'(opt),'periode']
	 */
	public function simpanHasilSAW(array $data)
	{
		$sql = "INSERT INTO tb_hasil_saw (id_siswa, nilai_akhir, peringkat, status, id_user, periode, created_at)
				VALUES (:id_siswa, :nilai_akhir, :peringkat, :status, :id_user, :periode, :created_at)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			'id_siswa'     => $data['id_siswa'],
			'nilai_akhir'  => $data['nilai_akhir'],
			'peringkat'    => $data['peringkat'] ?? 0,
			'status'       => $data['status'],
			'id_user'      => $data['id_user'] ?? null,
			'periode'      => $data['periode'],
			'created_at'   => $data['created_at'] ?? date('Y-m-d H:i:s'),
		]);
	}

	public function getHasilByPeriodeSAW($periode)
	{
		$sql = "
			SELECT h.*, s.nama
			FROM tb_hasil_saw h
			JOIN tb_siswa s ON h.id_siswa = s.id_siswa
			WHERE h.periode = :periode
			ORDER BY h.peringkat ASC
		";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['periode' => $periode]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}
