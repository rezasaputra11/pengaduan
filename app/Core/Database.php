<?php

namespace App\Core;

class Database
{
    private static $connection = null;

    public static function getConnection(): \mysqli
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $config = require __DIR__ . '/../../config/config.php';

        mysqli_report(MYSQLI_REPORT_OFF);

        $host = $config['host'] ?? '127.0.0.1';
        $port = (int) ($config['port'] ?? 3306);

        // "localhost" membuat PHP memakai unix socket -> error "No such file or directory"
        // bila path socket tidak cocok. Paksa TCP dengan mengganti ke 127.0.0.1.
        if ($host === 'localhost') {
            $host = '127.0.0.1';
        }

        $attempts = [[$host, $port, null]];

        // Fallback: coba beberapa lokasi socket umum (Termux, Linux, XAMPP/MAMP).
        $sockets = [];
        if (!empty($config['socket'])) {
            $sockets[] = $config['socket'];
        }
        $sockets = array_merge($sockets, [
            '/data/data/com.termux/files/usr/var/run/mysqld.sock',
            '/data/data/com.termux/files/usr/tmp/mysqld.sock',
            '/var/run/mysqld/mysqld.sock',
            '/run/mysqld/mysqld.sock',
            '/var/lib/mysql/mysql.sock',
            '/tmp/mysql.sock',
            '/opt/lampp/var/mysql/mysql.sock',
        ]);
        foreach ($sockets as $s) {
            if (@file_exists($s)) {
                $attempts[] = ['localhost', $port, $s];
            }
        }

        $connection = null;
        $lastError  = '';
        foreach ($attempts as [$h, $p, $s]) {
            $c = @mysqli_connect($h, $config['user'], $config['pass'], $config['name'], $p, $s);
            if ($c) {
                $connection = $c;
                break;
            }
            $lastError = mysqli_connect_error();
        }

        if (!$connection) {
            die('Koneksi database gagal: ' . htmlspecialchars((string) $lastError)
                . '<br>Pastikan server MySQL/MariaDB sudah berjalan dan database "'
                . htmlspecialchars($config['name']) . '" sudah dibuat &amp; di-import.');
        }

        mysqli_set_charset($connection, 'utf8mb4');

        self::$connection = $connection;

        return self::$connection;
    }
}
