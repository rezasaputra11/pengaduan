<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Admin;

class AdminController extends Controller
{
    private $model;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->model = new Admin();
    }

    public function index(): void
    {
        $admins = $this->model->all();
        $this->view('admin/index', ['admins' => $admins]);
    }

    public function create(): void
    {
        $error = '';
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                $error = 'Sesi form sudah kedaluwarsa, silakan coba lagi.';
            } else {
                $data = [
                    'username'   => trim($_POST['username'] ?? ''),
                    'password'   => $_POST['password'] ?? '',
                    'nama_admin' => trim($_POST['nama_admin'] ?? ''),
                    'gmail'      => trim($_POST['gmail'] ?? ''),
                ];
                $old = $data;

                if ($data['username'] === '' || $data['password'] === '' || $data['nama_admin'] === '' || $data['gmail'] === '') {
                    $error = 'Semua field wajib diisi.';
                } elseif ($this->model->usernameExists($data['username'])) {
                    $error = 'Username sudah digunakan.';
                } elseif ($this->model->create($data)) {
                    Flash::set('success', 'Admin berhasil ditambahkan.');
                    $this->redirect('admin');
                } else {
                    $error = 'Gagal menyimpan data admin.';
                }
            }
        }

        $this->view('admin/form', ['mode' => 'create', 'error' => $error, 'data' => $old]);
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $admin = $id > 0 ? $this->model->find($id) : null;

        if (!$admin) {
            Flash::set('error', 'Admin tidak ditemukan.');
            $this->redirect('admin');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                $error = 'Sesi form sudah kedaluwarsa, silakan coba lagi.';
            } else {
                $data = [
                    'username'   => trim($_POST['username'] ?? ''),
                    'password'   => $_POST['password'] ?? '',
                    'nama_admin' => trim($_POST['nama_admin'] ?? ''),
                    'gmail'      => trim($_POST['gmail'] ?? ''),
                ];

                if ($data['username'] === '' || $data['nama_admin'] === '' || $data['gmail'] === '') {
                    $error = 'Username, nama, dan gmail wajib diisi.';
                } elseif ($this->model->usernameExists($data['username'], $id)) {
                    $error = 'Username sudah digunakan.';
                } elseif ($this->model->update($id, $data)) {
                    Flash::set('success', 'Admin berhasil diubah.');
                    $this->redirect('admin');
                } else {
                    $error = 'Gagal memperbarui data admin.';
                }

                $admin = array_merge($admin, $data);
            }
        }

        $this->view('admin/form', ['mode' => 'edit', 'error' => $error, 'data' => $admin]);
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $currentUser = Auth::user();

        if ($currentUser && $id === (int) $currentUser['id_admin']) {
            Flash::set('error', 'Tidak bisa menghapus akun yang sedang digunakan untuk login.');
            $this->redirect('admin');
        }

        if ($id > 0 && $this->model->delete($id)) {
            Flash::set('success', 'Admin berhasil dihapus.');
        } else {
            Flash::set('error', 'Gagal menghapus admin.');
        }

        $this->redirect('admin');
    }
}
