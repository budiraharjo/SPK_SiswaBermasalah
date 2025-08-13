<?php
require_once __DIR__ . '/../routes/web.php';

use Core\Route;

Route::dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

