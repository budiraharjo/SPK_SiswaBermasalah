<?php
namespace Core;

class Migration
{
    public function run()
    {
        echo "Menjalankan migrasi...\n";
        foreach (glob(__DIR__ . '/../database/migrations/*.php') as $file) {
            require_once $file;
            $class = pathinfo($file, PATHINFO_FILENAME);
            $className = str_replace('_', '', ucwords($class, '_'));
            if (class_exists($className)) {
                (new $className)->up();
            }
        }
        echo "Migrasi selesai.\n";
    }
}

