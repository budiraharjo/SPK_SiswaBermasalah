<?php

class ExampleTest
{
    public function run()
    {
        $this->testBasicMath();
        $this->testStringContains();
    }

    private function testBasicMath()
    {
        $a = 2 + 3;
        if ($a === 5) {
            echo "[✓] testBasicMath passed.\n";
        } else {
            echo "[✗] testBasicMath failed. Expected 5, got {$a}\n";
        }
    }

    private function testStringContains()
    {
        $string = "Hello Mezur Framework!";
        if (strpos($string, "Mezur") !== false) {
            echo "[✓] testStringContains passed.\n";
        } else {
            echo "[✗] testStringContains failed. 'Mezur' not found in string.\n";
        }
    }
}
