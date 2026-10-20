<?php

namespace App\Models;

use App\Core\Database;

class Kategori
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
            'SELECT id_kategori, ket_kategori, keterangan, prioritas, status FROM kategori ORDER BY id_kategori ASC'
        );

        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    /**
     * Hanya kategori berstatus Aktif, dipakai untuk dropdown pilihan di form pengaduan.
     */
    public function allAktif(): array
    {
        $result = mysqli_query(
            $this->db,
            "SELECT id_kategori, ket_kategori FROM kategori WHERE status IS NULL OR status = 'Aktif' ORDER BY ket_kategori ASC"
        );

        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function find(int $id): ?array
    {
        $stmt = mysqli_prepare($this->db, 'SELECT * FROM kategori WHERE id_kategori = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_get_result($stmt)->fetch_assoc();

        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = mysqli_prepare($this->db, 'INSERT INTO kategori (ket_kategori, keterangan, prioritas, status) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssss', $data['ket_kategori'], $data['keterangan'], $data['prioritas'], $data['status']);

        return mysqli_stmt_execute($stmt);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = mysqli_prepare($this->db, 'UPDATE kategori SET ket_kategori = ?, keterangan = ?, prioritas = ?, status = ? WHERE id_kategori = ?');
        mysqli_stmt_bind_param($stmt, 'ssssi', $data['ket_kategori'], $data['keterangan'], $data['prioritas'], $data['status'], $id);

        return mysqli_stmt_execute($stmt);
    }

    public function delete(int $id): bool
    {
        $stmt = mysqli_prepare($this->db, 'DELETE FROM kategori WHERE id_kategori = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);

        return mysqli_stmt_execute($stmt);
    }
}
