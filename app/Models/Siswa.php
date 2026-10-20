<?php

namespace App\Models;

use App\Core\Database;

class Siswa
{
    /** @var \mysqli */
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $result = mysqli_query($this->db, 'SELECT nis, nama_siswa, kelas, jurusan, no_hp FROM siswa ORDER BY nama_siswa ASC');
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function find(int $nis): ?array
    {
        $stmt = mysqli_prepare($this->db, 'SELECT nis, nama_siswa, kelas, jurusan, no_hp, password FROM siswa WHERE nis = ?');
        mysqli_stmt_bind_param($stmt, 'i', $nis);
        mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_get_result($stmt)->fetch_assoc();
        return $row ?: null;
    }

    public function findForLogin(int $nis): ?array
    {
        return $this->find($nis);
    }

    public function verifyPassword(array $siswa, string $password): bool
    {
        if (isset($siswa['password']) && !empty(password_get_info($siswa['password'])['algo'])) {
            return password_verify($password, $siswa['password']);
        }
        return isset($siswa['password']) && hash_equals((string)$siswa['password'], $password);
    }

    public function rehashPassword(int $nis, string $password): bool
    {
        // Jangan rehash bila kolom password terlalu pendek (hash akan terpotong -> login rusak).
        $col = mysqli_query($this->db, "SHOW COLUMNS FROM siswa LIKE 'password'");
        $type = $col ? (string) ($col->fetch_assoc()['Type'] ?? '') : '';
        if (preg_match('/\((\d+)\)/', $type, $mm) && (int) $mm[1] < 60) {
            return false;
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($this->db, 'UPDATE siswa SET password = ? WHERE nis = ?');
        mysqli_stmt_bind_param($stmt, 'si', $hash, $nis);
        return mysqli_stmt_execute($stmt);
    }

    public function exists(int $nis): bool
    {
        $stmt = mysqli_prepare($this->db, 'SELECT nis FROM siswa WHERE nis = ?');
        mysqli_stmt_bind_param($stmt, 'i', $nis);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        return mysqli_stmt_num_rows($stmt) > 0;
    }

    public function create(array $data): bool
    {
        $password = password_hash((string)$data['nis'], PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($this->db, 'INSERT INTO siswa (nis, nama_siswa, kelas, jurusan, no_hp, password) VALUES (?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'isssss', $data['nis'], $data['nama_siswa'], $data['kelas'], $data['jurusan'], $data['no_hp'], $password);
        return mysqli_stmt_execute($stmt);
    }

    public function update(int $nis, array $data): bool
    {
        $stmt = mysqli_prepare($this->db, 'UPDATE siswa SET nama_siswa = ?, kelas = ?, jurusan = ?, no_hp = ? WHERE nis = ?');
        mysqli_stmt_bind_param($stmt, 'ssssi', $data['nama_siswa'], $data['kelas'], $data['jurusan'], $data['no_hp'], $nis);
        return mysqli_stmt_execute($stmt);
    }

    public function delete(int $nis): bool
    {
        mysqli_begin_transaction($this->db);
        try {
            $stmt = mysqli_prepare($this->db, 'DELETE FROM aspirasi WHERE id_pelaporan IN (SELECT id_pelaporan FROM input_aspirasi WHERE nis = ?)');
            mysqli_stmt_bind_param($stmt, 'i', $nis);
            if (!mysqli_stmt_execute($stmt)) throw new \Exception(mysqli_error($this->db));
            $stmt = mysqli_prepare($this->db, 'DELETE FROM input_aspirasi WHERE nis = ?');
            mysqli_stmt_bind_param($stmt, 'i', $nis);
            if (!mysqli_stmt_execute($stmt)) throw new \Exception(mysqli_error($this->db));
            $stmt = mysqli_prepare($this->db, 'DELETE FROM siswa WHERE nis = ?');
            mysqli_stmt_bind_param($stmt, 'i', $nis);
            if (!mysqli_stmt_execute($stmt)) throw new \Exception(mysqli_error($this->db));
            mysqli_commit($this->db);
            return true;
        } catch (\Throwable $e) {
            mysqli_rollback($this->db);
            return false;
        }
    }
}
