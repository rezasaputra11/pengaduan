<?php
/**
 * Konfigurasi koneksi database.
 * Sesuaikan nilai di bawah ini dengan environment server masing-masing.
 *
 * Catatan Termux/Android: gunakan host 127.0.0.1 (TCP), BUKAN "localhost",
 * karena "localhost" membuat PHP mencari file socket (error "No such file or directory").
 * Pastikan MariaDB sudah jalan:  mysqld_safe &
 */

return [
    'host'   => '127.0.0.1',
    'port'   => 3306,
    'user'   => 'root',
    'pass'   => 'root',
    'name'   => 'db_pengaduan_sarpras_fixed',
    // Opsional: isi path socket bila ingin pakai socket (mis. Termux:
    // '/data/data/com.termux/files/usr/var/run/mysqld.sock'). Kosongkan = auto.
    'socket' => null,
];
