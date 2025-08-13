<?php
namespace App\Models;

use Core\Database;
use PDO;

class Periode 
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM tb_periode ORDER BY id_periode DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive()
    {
        $stmt = $this->db->query("SELECT * FROM tb_periode WHERE is_active = 1 LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store($periode)
    {
        $stmt = $this->db->prepare("INSERT INTO tb_periode (periode, is_active) VALUES (?, 0)");
        return $stmt->execute([$periode]);
    }

    public function update($id, $periode, $is_active)
    {
        if ($is_active == 1) {
            $this->db->exec("UPDATE tb_periode SET is_active = 0");
        }
        $stmt = $this->db->prepare("UPDATE tb_periode SET periode = ?, is_active = ? WHERE id_periode = ?");
        return $stmt->execute([$periode, $is_active, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tb_periode WHERE id_periode = ?");
        return $stmt->execute([$id]);
    }
}
