<?php

namespace App\Models;

use Core\Database;

class User
{
    protected $db;
    protected $table = 'tb_user'; // nama tabel login

    public function __construct()
    {
        $this->db = Database::getConnection(); // langsung ambil koneksi
    }

    /**
     * Helper query
     */
    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function all()
    {
        return $this->query("SELECT * FROM {$this->table}")->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE id_user = ?", [$id]);
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE username = ?", [$username]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (username, password_hash, nama, role, created_at) VALUES (?, ?, ?, ?, ?)";
        return $this->query($sql, [
            $data['username'],
            md5($data['password_hash']),
			$data['nama'],
			$data['created_at'],
            $data['role']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET nama = ?, username = ?, role = ? WHERE id_user = ?";
        return $this->query($sql, [
            $data['nama'],
            $data['username'],
            $data['role'],
            $id
        ]);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE id_user = ?", [$id]);
    }
}
