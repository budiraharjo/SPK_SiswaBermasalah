<?php

namespace App\Models;

use Core\Database;

class Admin
{
    protected $db;
    protected $table = 'tb_user'; // sesuaikan dengan nama tabel

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Cari admin/pegawai berdasarkan username
     */
    public function findByUsername($username)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE username = ?", [$username]);
        return $stmt->fetch();
    }

    public function updateByUsername($username, $data)
    {
        $sql = "UPDATE {$this->table} SET nama = ? WHERE username = ?";
        return $this->query($sql, [
            $data['nama'],
            $username
        ]);
    }

    /**
     * Ambil semua admin/pegawai
     */
    public function all()
    {
        return $this->query("SELECT * FROM {$this->table}")->fetchAll();
    }
}
