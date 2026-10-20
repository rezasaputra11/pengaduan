<?php

declare(strict_types=1);

session_start();

// Autoloader sederhana (PSR-4 manual) untuk namespace App\...
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\KategoriController;
use App\Controllers\PengaduanController;
use App\Controllers\SiswaController;

// Semua route aplikasi: 'nama_route' => [Controller, method]
$routes = [
    'login'     => [AuthController::class, 'login'],
    'logout'    => [AuthController::class, 'logout'],
    'dashboard' => [DashboardController::class, 'index'],

    'admin'        => [AdminController::class, 'index'],
    'admin.create' => [AdminController::class, 'create'],
    'admin.edit'   => [AdminController::class, 'edit'],
    'admin.delete' => [AdminController::class, 'delete'],

    'siswa'        => [SiswaController::class, 'index'],
    'siswa.create' => [SiswaController::class, 'create'],
    'siswa.edit'   => [SiswaController::class, 'edit'],
    'siswa.delete' => [SiswaController::class, 'delete'],

    'kategori'        => [KategoriController::class, 'index'],
    'kategori.create' => [KategoriController::class, 'create'],
    'kategori.edit'   => [KategoriController::class, 'edit'],
    'kategori.delete' => [KategoriController::class, 'delete'],

    'pengaduan'        => [PengaduanController::class, 'index'],
    'pengaduan.create' => [PengaduanController::class, 'create'],
    'pengaduan.edit'   => [PengaduanController::class, 'edit'],
    'pengaduan.delete' => [PengaduanController::class, 'delete'],
];

$page = $_GET['page'] ?? 'login';

if (!isset($routes[$page])) {
    http_response_code(404);
    echo 'Halaman tidak ditemukan.';
    exit;
}

[$controllerClass, $method] = $routes[$page];
$controller = new $controllerClass();
$controller->$method();
