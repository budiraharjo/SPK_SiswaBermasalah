<?php

namespace Core;

class Migration
{
    public static function run()
    {
        echo "\n[Mezur] Running database migrations...\n";

        // Contoh: Jalankan file SQL dari folder database/migrations
        \$migrationPath = BASE_PATH . '/database/migrations';

        if (!is_dir(\$migrationPath)) {
            echo "No migrations found.\n";
            return;
        }

        \$files = glob(\$migrationPath . '/*.php');

        if (empty(\$files)) {
            echo "No migration files found.\n";
            return;
        }

        foreach (\$files as \$file) {
            echo "Running migration: " . basename(\$file) . "... ";
            include_once \$file;
            echo "Done.\n";
        }

        echo "\n[Mezur] Migrations complete.\n";
    }
}
