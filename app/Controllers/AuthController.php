<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Admin;
use App\Models\Siswa;

class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) $this->redirect('dashboard');

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                $error = 'Sesi form sudah kedaluwarsa, silakan coba lagi.';
            } else {
                $login = trim($_POST['login'] ?? '');
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? 'admin';

                if ($role === 'anggota') {
                    if (!ctype_digit($login)) {
                        $error = 'NIS harus berupa angka.';
                    } else {
                        $siswaModel = new Siswa();
                        $siswa = $siswaModel->findForLogin((int)$login);
                        if ($siswa && $siswaModel->verifyPassword($siswa, $password)) {
                            if (empty(password_get_info($siswa['password'])['algo'])) {
                                $siswaModel->rehashPassword((int)$siswa['nis'], $password);
                            }
                            Auth::loginSiswa($siswa);
                            $this->redirect('dashboard');
                        }
                        $error = 'NIS atau password anggota salah.';
                    }
                } else {
                    $adminModel = new Admin();
                    $admin = $adminModel->findByUsername($login);
                    if ($admin && $adminModel->verifyPassword($admin, $password)) {
                        if (empty(password_get_info($admin['password'])['algo'])) {
                            $adminModel->rehashPassword((int)$admin['id_admin'], $password);
                        }
                        Auth::loginAdmin($admin);
                        $this->redirect('dashboard');
                    }
                    $error = 'Username atau password admin salah.';
                }
            }
        }

        $this->bareView('auth/login', ['error' => $error]);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('login');
    }
}
