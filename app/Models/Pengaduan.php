<?php

namespace App\Models;

use App\Core\Database;

class Pengaduan
{
    /** @var \mysqli */
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $sql = "SELECT ia.id_pelaporan, ia.nis, s.nama_siswa, ia.id_kategori, k.ket_kategori,
                       ia.lokasi, ia.ket,
                       COALESCE(a.status, 'Menunggu') AS status,
                       COALESCE(a.feedback, '') AS feedback
                FROM input_aspirasi ia
                INNER JOIN siswa s ON ia.nis = s.nis
                INNER JOIN kategori k ON ia.id_kategori = k.id_kategori
                LEFT JOIN aspirasi a ON a.id_pelaporan = ia.id_pelaporan
                ORDER BY ia.id_pelaporan DESC";

        $result = mysqli_query($this->db, $sql);

        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function byNis(int $nis): array
    {
        $stmt = mysqli_prepare($this->db, "SELECT ia.id_pelaporan, ia.nis, s.nama_siswa, ia.id_kategori, k.ket_kategori, ia.lokasi, ia.ket, COALESCE(a.status, 'Menunggu') AS status, COALESCE(a.feedback, '') AS feedback FROM input_aspirasi ia INNER JOIN siswa s ON ia.nis=s.nis INNER JOIN kategori k ON ia.id_kategori=k.id_kategori LEFT JOIN aspirasi a ON a.id_pelaporan=ia.id_pelaporan WHERE ia.nis=? ORDER BY ia.id_pelaporan DESC");
        mysqli_stmt_bind_param($stmt, 'i', $nis); mysqli_stmt_execute($stmt); $result=mysqli_stmt_get_result($stmt);
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public function find(int $id): ?array
    {
        $stmt = mysqli_prepare(
            $this->db,
            "SELECT ia.id_pelaporan, ia.nis, ia.id_kategori, ia.lokasi, ia.ket,
                    COALESCE(a.status, 'Menunggu') AS status,
                    COALESCE(a.feedback, '') AS feedback
             FROM input_aspirasi ia
             LEFT JOIN aspirasi a ON a.id_pelaporan = ia.id_pelaporan
             WHERE ia.id_pelaporan = ?"
        );
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_stmt_get_result($stmt)->fetch_assoc();

        return $row ?: null;
    }

    /**
     * Buat laporan pengaduan baru + baris aspirasi awal berstatus Menunggu (transaksi).
     */
    public function create(array $data): bool
    {
        mysqli_begin_transaction($this->db);

        try {
            $stmt = mysqli_prepare($this->db, 'INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket) VALUES (?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'iiss', $data['nis'], $data['id_kategori'], $data['lokasi'], $data['ket']);
            if (!mysqli_stmt_execute($stmt)) {
                throw new \Exception(mysqli_error($this->db));
            }

            $idPelaporan = mysqli_insert_id($this->db);
            $status = 'Menunggu';
            $feedback = null;

            $stmt = mysqli_prepare($this->db, 'INSERT INTO aspirasi (id_pelaporan, id_kategori, status, feedback) VALUES (?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'iiss', $idPelaporan, $data['id_kategori'], $status, $feedback);
            if (!mysqli_stmt_execute($stmt)) {
                throw new \Exception(mysqli_error($this->db));
            }

            mysqli_commit($this->db);

            return true;
        } catch (\Throwable $e) {
            mysqli_rollback($this->db);

            return false;
        }
    }

    /**
     * Update data laporan + status/feedback aspirasi (transaksi, insert aspirasi jika belum ada).
     */
    public function update(int $id, array $data): bool
    {
        mysqli_begin_transaction($this->db);

        try {
            $stmt = mysqli_prepare($this->db, 'UPDATE input_aspirasi SET nis = ?, id_kategori = ?, lokasi = ?, ket = ? WHERE id_pelaporan = ?');
            mysqli_stmt_bind_param($stmt, 'iissi', $data['nis'], $data['id_kategori'], $data['lokasi'], $data['ket'], $id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new \Exception(mysqli_error($this->db));
            }

            $stmt = mysqli_prepare($this->db, 'SELECT id_aspirasi FROM aspirasi WHERE id_pelaporan = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $existing = mysqli_stmt_get_result($stmt)->fetch_assoc();

            if ($existing) {
                $stmt = mysqli_prepare($this->db, 'UPDATE aspirasi SET id_kategori = ?, status = ?, feedback = ? WHERE id_pelaporan = ?');
                mysqli_stmt_bind_param($stmt, 'issi', $data['id_kategori'], $data['status'], $data['feedback'], $id);
            } else {
                $stmt = mysqli_prepare($this->db, 'INSERT INTO aspirasi (id_pelaporan, id_kategori, status, feedback) VALUES (?, ?, ?, ?)');
                mysqli_stmt_bind_param($stmt, 'iiss', $id, $data['id_kategori'], $data['status'], $data['feedback']);
            }
            if (!mysqli_stmt_execute($stmt)) {
                throw new \Exception(mysqli_error($this->db));
            }

            mysqli_commit($this->db);

            return true;
        } catch (\Throwable $e) {
            mysqli_rollback($this->db);

            return false;
        }
    }

    public function delete(int $id): bool
    {
        mysqli_begin_transaction($this->db);

        try {
            $stmt = mysqli_prepare($this->db, 'DELETE FROM aspirasi WHERE id_pelaporan = ?');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new \Exception(mysqli_error($this->db));
            }

            $stmt = mysqli_prepare($this->db, 'DELETE FROM input_aspirasi WHERE id_pelaporan = ?');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new \Exception(mysqli_error($this->db));
            }

            mysqli_commit($this->db);

            return true;
        } catch (\Throwable $e) {
            mysqli_rollback($this->db);

            return false;
        }
    }
}
