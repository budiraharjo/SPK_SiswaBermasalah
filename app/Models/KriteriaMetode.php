<?php
namespace App\Models;

use Core\Database;

class KriteriaMetode
{
    protected $db;
    protected $table = 'tb_kriteria_metode';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua data kriteria-metode
    public function all($limit = null, $offset = null, $search = '')
    {
        $sql = "SELECT m.*, k.kode, k.nama_kriteria 
                FROM {$this->table} m
                JOIN tb_kriteria k ON m.id_kriteria = k.id_kriteria";

        if (trim($search) !== '') {
            $sql .= " WHERE k.kode LIKE :q OR k.nama_kriteria LIKE :q";
        }

        $sql .= " ORDER BY m.urutan ASC";

        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        if (trim($search) !== '') {
            $q = "%{$search}%";
            $stmt->bindValue(':q', $q, \PDO::PARAM_STR);
        }
        if ($limit !== null && $offset !== null) {
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Hitung semua data
    public function countAll($search = '')
    {
        $sql = "SELECT COUNT(*) AS cnt 
                FROM {$this->table} m
                JOIN tb_kriteria k ON m.id_kriteria = k.id_kriteria";

        if (trim($search) !== '') {
            $sql .= " WHERE k.kode LIKE :q OR k.nama_kriteria LIKE :q";
        }

        $stmt = $this->db->prepare($sql);

        if (trim($search) !== '') {
            $q = "%{$search}%";
            $stmt->bindValue(':q', $q, \PDO::PARAM_STR);
        }

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($row['cnt'] ?? 0);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare(
            "SELECT m.*, k.kode, k.nama_kriteria 
             FROM {$this->table} m 
             JOIN tb_kriteria k ON m.id_kriteria = k.id_kriteria 
             WHERE m.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

	public function create($data)
	{
		// Cek apakah kombinasi id_kriteria + metode sudah ada
		$stmtCheck = $this->db->prepare(
			"SELECT COUNT(*) AS cnt 
			 FROM {$this->table} 
			 WHERE id_kriteria = :id_kriteria 
			   AND metode = :metode"
		);
		$stmtCheck->bindValue(':id_kriteria', $data['id_kriteria'], \PDO::PARAM_INT);
		$stmtCheck->bindValue(':metode', $data['metode'], \PDO::PARAM_STR);
		$stmtCheck->execute();
		$exists = (int) $stmtCheck->fetch(\PDO::FETCH_ASSOC)['cnt'];

		if ($exists > 0) {
			// Sudah ada, jangan insert
			return false;
		}

		// Ambil urutan terbesar untuk metode yang dipilih
		$stmtMax = $this->db->prepare(
			"SELECT MAX(urutan) AS max_urutan 
			 FROM {$this->table} 
			 WHERE metode = :metode"
		);
		$stmtMax->bindValue(':metode', $data['metode'], \PDO::PARAM_STR);
		$stmtMax->execute();
		$maxUrutan = (int) ($stmtMax->fetch(\PDO::FETCH_ASSOC)['max_urutan'] ?? 0);

		// Urutan baru = max + 1
		$urutanBaru = $maxUrutan + 1;

		// Insert data baru
		$stmt = $this->db->prepare(
			"INSERT INTO {$this->table} (id_kriteria, metode, bobot, sifat, urutan, created_at) 
			 VALUES (?, ?, ?, ?, ?, NOW())"
		);
		return $stmt->execute([
			$data['id_kriteria'],
			$data['metode'] ?? '',
			$data['bobot'],
			$data['sifat'],
			$urutanBaru
		]);
	}

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} 
             SET id_kriteria = ?, bobot = ?, sifat = ?, urutan = ? 
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['id_kriteria'],
            $data['bobot'],
            $data['sifat'],
            $data['urutan'] ?? 0,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

	public function getAllKriteria()
	{
		$stmt = $this->db->prepare("SELECT * FROM tb_kriteria ORDER BY kode ASC");
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

}
