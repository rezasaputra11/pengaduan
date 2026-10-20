<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Kategori;

class KategoriController extends Controller
{
    private $model;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->model = new Kategori();
    }

    public function index(): void
    {
        $kategori = $this->model->all();
        $this->view('kategori/index', ['kategori' => $kategori]);
    }

    public function create(): void
    {
        $error = '';
        $old = ['prioritas' => 'Rendah', 'status' => 'Aktif'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                $error = 'Sesi form sudah kedaluwarsa, silakan coba lagi.';
            } else {
                $data = [
                    'ket_kategori' => trim($_POST['ket_kategori'] ?? ''),
                    'keterangan'   => trim($_POST['keterangan'] ?? ''),
                    'prioritas'    => trim($_POST['prioritas'] ?? ''),
                    'status'       => trim($_POST['status'] ?? ''),
                ];
                $old = $data;

                if ($data['ket_kategori'] === '') {
                    $error = 'Nama kategori wajib diisi.';
                } elseif ($this->model->create($data)) {
                    Flash::set('success', 'Kategori berhasil ditambahkan.');
                    $this->redirect('kategori');
                } else {
                    $error = 'Gagal menyimpan kategori.';
                }
            }
        }

        $this->view('kategori/form', ['mode' => 'create', 'error' => $error, 'data' => $old]);
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $kategori = $id > 0 ? $this->model->find($id) : null;

        if (!$kategori) {
            Flash::set('error', 'Kategori tidak ditemukan.');
            $this->redirect('kategori');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                $error = 'Sesi form sudah kedaluwarsa, silakan coba lagi.';
            } else {
                $data = [
                    'ket_kategori' => trim($_POST['ket_kategori'] ?? ''),
                    'keterangan'   => trim($_POST['keterangan'] ?? ''),
                    'prioritas'    => trim($_POST['prioritas'] ?? ''),
                    'status'       => trim($_POST['status'] ?? ''),
                ];

                if ($data['ket_kategori'] === '') {
                    $error = 'Nama kategori wajib diisi.';
                } elseif ($this->model->update($id, $data)) {
                    Flash::set('success', 'Kategori berhasil diubah.');
                    $this->redirect('kategori');
                } else {
                    $error = 'Gagal memperbarui kategori.';
                }

                $kategori = array_merge($kategori, $data);
            }
        }

        $this->view('kategori/form', ['mode' => 'edit', 'error' => $error, 'data' => $kategori]);
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0 && $this->model->delete($id)) {
            Flash::set('success', 'Kategori berhasil dihapus.');
        } else {
            Flash::set('error', 'Gagal menghapus kategori. Pastikan kategori ini tidak sedang dipakai oleh data pengaduan.');
        }

        $this->redirect('kategori');
    }
}
