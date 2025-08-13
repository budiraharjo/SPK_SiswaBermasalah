<?php
namespace Core;

use PDO;

class Model
{
    protected $db;

    public function __construct()
    {
        $config = parse_ini_file(__DIR__ . '/../config/.env');

        $this->db = new PDO(
            "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']}",
            $config['DB_USER'],
            $config['DB_PASS']
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

