<?php
namespace Core;

class Tester
{
    public function run()
    {
        echo "Menjalankan test sederhana...\n";
        require_once '../tests/ExampleTest.php';
        $test = new \Tests\ExampleTest();
        $test->run();
    }
}

