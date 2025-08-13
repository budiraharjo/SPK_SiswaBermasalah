<?php
namespace App\Models;

use Core\Database;

class Users
{
    protected $db;
    protected $table = 'tb_user';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Ambil data terpaginated
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public function getAll($limit, $offset, $search = '')
    {
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($search)) {
            $sql .= " WHERE nama LIKE :search OR username LIKE :search";
        }
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%{$search}%", \PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Hitung total data (dengan search opsional)
     */
    public function countAll($search = '')
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if (!empty($search)) {
            $sql .= " WHERE nama LIKE :search OR username LIKE :search";
        }
        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', \PDO::PARAM_STR);
        }
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (username, password_hash, nama, role, created_at, updated_at) VALUES (:username, :password_hash, :nama, :role, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'],
            ':password_hash' => md5($data['password']),
            ':nama' => $data['nama'],
            ':role' => $data['role']
        ]);
    }

    public function update($id, $data)
    {
        $fields = [
            'username' => $data['username'],
            'nama' => $data['nama'],
            'role' => $data['role'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($data['password'])) {
            $fields['password_hash'] = md5($data['password']);
        }

        $setPart = [];
        foreach ($fields as $key => $value) {
            $setPart[] = "$key = :$key";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setPart) . " WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $fields['id'] = $id;
        $stmt->execute($fields);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
