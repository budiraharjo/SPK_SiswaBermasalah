<?php
namespace App\Models;

use Core\Database;

class Siswa
{
    protected $db;
    protected $table = 'tb_siswa';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Untuk compatibility lama
    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_siswa = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nisn, nama, kelas, jenis_kelamin, tahun_ajaran, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        return $stmt->execute([
            $data['nisn'],
            $data['nama'],
            $data['kelas'],
            $data['jenis_kelamin'],
            $data['tahun_ajaran']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nisn = ?, nama = ?, kelas = ?, jenis_kelamin = ?, tahun_ajaran = ?, updated_at = NOW() WHERE id_siswa = ?");
        return $stmt->execute([
            $data['nisn'],
            $data['nama'],
            $data['kelas'],
            $data['jenis_kelamin'],
            $data['tahun_ajaran'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_siswa = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Ambil data halaman (pagination) dengan optional search.
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public function paginate($limit, $offset, $search = '')
    {
        if (trim($search) !== '') {
            $q = "%{$search}%";
            $sql = "SELECT * FROM {$this->table}
                    WHERE nama LIKE :q OR nisn LIKE :q OR kelas LIKE :q
                    ORDER BY created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':q', $q, \PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
            $stmt->execute();
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Hitung total baris (dengan optional search)
     * @param string $search
     * @return int
     */
    public function countAll($search = '')
    {
        if (trim($search) !== '') {
            $q = "%{$search}%";
            $sql = "SELECT COUNT(*) AS cnt FROM {$this->table} WHERE nama LIKE :q OR nisn LIKE :q OR kelas LIKE :q";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':q', $q, \PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $sql = "SELECT COUNT(*) AS cnt FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($row['cnt'] ?? 0);
    }
}
