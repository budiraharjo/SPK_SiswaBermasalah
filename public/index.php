<?php
session_start();

require_once __DIR__.'/../vendor/autoload.php';  // Composer autoload

require_once __DIR__.'/../core/App.php';

// Load routes
require_once __DIR__.'/../routes/web.php';

// Dispatch route
\Core\Route::dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
