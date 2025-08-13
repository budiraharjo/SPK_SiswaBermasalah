<?php
namespace App\Models;

use Core\Database;

class Kriteria
{
    protected $db;
    protected $table = 'tb_kriteria';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY id_kriteria ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_kriteria = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (kode, nama_kriteria, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([
            $data['kode'],
            $data['nama_kriteria']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET kode = ?, nama_kriteria = ? WHERE id_kriteria = ?");
        return $stmt->execute([
            $data['kode'],
            $data['nama_kriteria'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_kriteria = ?");
        return $stmt->execute([$id]);
    }

    public function paginate($limit, $offset, $search = '')
    {
        if (trim($search) !== '') {
            $q = "%{$search}%";
            $sql = "SELECT * FROM {$this->table}
                    WHERE kode LIKE :q OR nama_kriteria LIKE :q
                    ORDER BY id_kriteria ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':q', $q, \PDO::PARAM_STR);
        } else {
            $sql = "SELECT * FROM {$this->table} ORDER BY id_kriteria ASC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
        }
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countAll($search = '')
    {
        if (trim($search) !== '') {
            $q = "%{$search}%";
            $sql = "SELECT COUNT(*) AS cnt FROM {$this->table} WHERE kode LIKE :q OR nama_kriteria LIKE :q";
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
