<?php

namespace Core;

class CLI
{
    public static function handle($argv)
    {
        $command = $argv[1] ?? null;

        switch ($command) {
            case 'serve':
                echo "Starting local server at http://localhost:8000\n";
                shell_exec('php -S localhost:8000 -t public');
                break;

            case 'migrate':
                echo "Running migrations...\n";
                require_once __DIR__ . '/../database/migrations/2025_01_create_users_table.php';
                $migration = new \Migration\CreateUsersTable();
                $migration->up();
                echo "Migration completed.\n";
                break;

            case 'test':
                echo "Running tests...\n";
                require_once __DIR__ . '/../tests/ExampleTest.php';
                $test = new \Tests\ExampleTest();
                $test->run();
                break;

            default:
                echo "Mezur Framework CLI\n";
                echo "Usage:\n";
                echo "  php mezur serve    Start local server\n";
                echo "  php mezur migrate  Run migrations\n";
                echo "  php mezur test     Run tests\n";
                break;
        }
    }
}
