<?php

namespace App\Models;

use App\Core\Database;

class Admin
{
    /** @var \mysqli */
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $result = mysqli_query(
            $this->db,
            'SELECT id_admin, username, nama_admin, gmail FROM admin ORDER BY id_admin ASC'
        );

        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function find(int $id): ?array
    {
        $stmt = mysqli_prepare($this->db, 'SELECT id_admin, username, nama_admin, gmail FROM admin WHERE id_admin = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_get_result($stmt)->fetch_assoc();

        return $row ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = mysqli_prepare($this->db, 'SELECT * FROM admin WHERE username = ?');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_get_result($stmt)->fetch_assoc();

        return $row ?: null;
    }

    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = mysqli_prepare($this->db, 'SELECT id_admin FROM admin WHERE username = ? AND id_admin <> ?');
            mysqli_stmt_bind_param($stmt, 'si', $username, $excludeId);
        } else {
            $stmt = mysqli_prepare($this->db, 'SELECT id_admin FROM admin WHERE username = ?');
            mysqli_stmt_bind_param($stmt, 's', $username);
        }

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        return mysqli_stmt_num_rows($stmt) > 0;
    }

    public function create(array $data): bool
    {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($this->db, 'INSERT INTO admin (username, password, nama_admin, gmail) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssss', $data['username'], $hash, $data['nama_admin'], $data['gmail']);

        return mysqli_stmt_execute($stmt);
    }

    public function update(int $id, array $data): bool
    {
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($this->db, 'UPDATE admin SET username = ?, password = ?, nama_admin = ?, gmail = ? WHERE id_admin = ?');
            mysqli_stmt_bind_param($stmt, 'ssssi', $data['username'], $hash, $data['nama_admin'], $data['gmail'], $id);
        } else {
            $stmt = mysqli_prepare($this->db, 'UPDATE admin SET username = ?, nama_admin = ?, gmail = ? WHERE id_admin = ?');
            mysqli_stmt_bind_param($stmt, 'sssi', $data['username'], $data['nama_admin'], $data['gmail'], $id);
        }

        return mysqli_stmt_execute($stmt);
    }

    public function delete(int $id): bool
    {
        $stmt = mysqli_prepare($this->db, 'DELETE FROM admin WHERE id_admin = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);

        return mysqli_stmt_execute($stmt);
    }

    /**
     * Mendukung dua bentuk password: hash bcrypt (baru) dan plain text (data lama).
     */
    public function verifyPassword(array $admin, string $password): bool
    {
        $info = password_get_info($admin['password']);

        if (!empty($info['algo'])) {
            return password_verify($password, $admin['password']);
        }

        return hash_equals($admin['password'], $password);
    }

    /**
     * Naikkan password lama (plain text) menjadi hash bcrypt setelah login berhasil.
     */
    public function rehashPassword(int $id, string $plainPassword): void
    {
        // Jangan rehash bila kolom password terlalu pendek (hash akan terpotong -> login rusak).
        $col = mysqli_query($this->db, "SHOW COLUMNS FROM admin LIKE 'password'");
        $type = $col ? (string) ($col->fetch_assoc()['Type'] ?? '') : '';
        if (preg_match('/\((\d+)\)/', $type, $mm) && (int) $mm[1] < 60) {
            return;
        }
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($this->db, 'UPDATE admin SET password = ? WHERE id_admin = ?');
        mysqli_stmt_bind_param($stmt, 'si', $hash, $id);
        mysqli_stmt_execute($stmt);
    }
}
