<?php
// UNTUK HOSTING
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/staging/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/staging/vendor/autoload.php';

$app = require_once __DIR__.'/staging/bootstrap/app.php';

$app->handleRequest(Request::capture());