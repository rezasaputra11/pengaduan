<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Siswa;

class SiswaController extends Controller
{
    private $model;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->model = new Siswa();
    }

    public function index(): void
    {
        $siswa = $this->model->all();
        $this->view('siswa/index', ['siswa' => $siswa]);
    }

    public function create(): void
    {
        $error = '';
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                $error = 'Sesi form sudah kedaluwarsa, silakan coba lagi.';
            } else {
                $nis = trim($_POST['nis'] ?? '');
                $data = [
                    'nama_siswa' => trim($_POST['nama_siswa'] ?? ''),
                    'kelas'      => trim($_POST['kelas'] ?? ''),
                    'jurusan'    => trim($_POST['jurusan'] ?? ''),
                    'no_hp'      => trim($_POST['no_hp'] ?? ''),
                ];
                $old = array_merge(['nis' => $nis], $data);

                if ($nis === '' || $data['nama_siswa'] === '' || $data['kelas'] === '' || $data['jurusan'] === '') {
                    $error = 'NIS, nama, kelas, dan jurusan wajib diisi.';
                } elseif (!ctype_digit($nis)) {
                    $error = 'NIS harus berupa angka.';
                } elseif ($this->model->exists((int) $nis)) {
                    $error = 'NIS sudah terdaftar. Gunakan NIS lain.';
                } else {
                    $data['nis'] = (int) $nis;

                    if ($this->model->create($data)) {
                        Flash::set('success', 'Data siswa berhasil ditambahkan.');
                        $this->redirect('siswa');
                    }
                    $error = 'Gagal menambahkan data siswa.';
                }
            }
        }

        $this->view('siswa/form', ['mode' => 'create', 'error' => $error, 'data' => $old]);
    }

    public function edit(): void
    {
        $nis = (int) ($_GET['nis'] ?? 0);
        $siswa = $nis > 0 ? $this->model->find($nis) : null;

        if (!$siswa) {
            Flash::set('error', 'Data siswa tidak ditemukan.');
            $this->redirect('siswa');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                $error = 'Sesi form sudah kedaluwarsa, silakan coba lagi.';
            } else {
                $data = [
                    'nama_siswa' => trim($_POST['nama_siswa'] ?? ''),
                    'kelas'      => trim($_POST['kelas'] ?? ''),
                    'jurusan'    => trim($_POST['jurusan'] ?? ''),
                    'no_hp'      => trim($_POST['no_hp'] ?? ''),
                ];

                if ($data['nama_siswa'] === '' || $data['kelas'] === '' || $data['jurusan'] === '') {
                    $error = 'Nama, kelas, dan jurusan wajib diisi.';
                } elseif ($this->model->update($nis, $data)) {
                    Flash::set('success', 'Data siswa berhasil diperbarui.');
                    $this->redirect('siswa');
                } else {
                    $error = 'Gagal memperbarui data siswa.';
                }

                $siswa = array_merge($siswa, $data);
            }
        }

        $this->view('siswa/form', ['mode' => 'edit', 'error' => $error, 'data' => $siswa]);
    }

    public function delete(): void
    {
        $nis = (int) ($_GET['nis'] ?? 0);

        if ($nis > 0 && $this->model->delete($nis)) {
            Flash::set('success', 'Data siswa beserta pengaduannya berhasil dihapus.');
        } else {
            Flash::set('error', 'Gagal menghapus data siswa.');
        }

        $this->redirect('siswa');
    }
}
