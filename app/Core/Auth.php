<?php

namespace App\Core;

class Auth
{
    public static function loginAdmin(array $admin): void
    {
        session_regenerate_id(true);
        $_SESSION['role'] = 'admin';
        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['nama'] = $admin['nama_admin'];
    }

    public static function loginSiswa(array $siswa): void
    {
        session_regenerate_id(true);
        $_SESSION['role'] = 'anggota';
        $_SESSION['nis'] = $siswa['nis'];
        $_SESSION['nama'] = $siswa['nama_siswa'];
        $_SESSION['kelas'] = $siswa['kelas'];
    }

    public static function check(): bool
    {
        return isset($_SESSION['role']);
    }

    public static function isAdmin(): bool
    {
        return self::check() && $_SESSION['role'] === 'admin';
    }

    public static function isSiswa(): bool
    {
        return self::check() && $_SESSION['role'] === 'anggota';
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . ($_SERVER['SCRIPT_NAME'] ?? 'index.php') . '?page=login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header('Location: ' . ($_SERVER['SCRIPT_NAME'] ?? 'index.php') . '?page=dashboard');
            exit;
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    public static function user(): ?array
    {
        if (!self::check()) return null;

        if (self::isAdmin()) {
            return [
                'role' => 'admin',
                'id_admin' => $_SESSION['id_admin'],
                'username' => $_SESSION['username'],
                'nama' => $_SESSION['nama'],
            ];
        }

        return [
            'role' => 'anggota',
            'nis' => $_SESSION['nis'],
            'nama' => $_SESSION['nama'],
            'kelas' => $_SESSION['kelas'],
        ];
    }
}
