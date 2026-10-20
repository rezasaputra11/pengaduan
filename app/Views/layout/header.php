<?php

use App\Core\Auth;
use App\Core\Flash;

$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Pengaduan Sekolah</title>
<style>
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 0; color: #222; }
.navbar { background: #198754; color: #fff; padding: 14px 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
.navbar a { color: #fff; text-decoration: none; margin-right: 16px; font-weight: bold; }
.navbar a:hover { text-decoration: underline; }
.navbar .user { font-size: 14px; }
.wrap { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
.card { background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow-x: auto; }
h2 { margin-top: 0; }
table { width: 100%; border-collapse: collapse; margin-top: 18px; }
th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
th { background: #eee; }
.btn { display: inline-block; padding: 9px 14px; background: #198754; color: #fff; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; font-size: 14px; }
.btn.secondary { background: #6c757d; }
.aksi a { margin-right: 10px; }
.alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
.alert.success { background: #d1e7dd; color: #0f5132; }
.alert.error { background: #f8d7da; color: #842029; }
form label { font-weight: bold; display: block; margin-top: 14px; }
form input, form select, form textarea { width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; font-family: inherit; }
.form-actions { margin-top: 20px; }
</style>
</head>
<body>
<div class="navbar"><div>
<a href="index.php?page=dashboard">Dashboard</a><a href="index.php?page=pengaduan"><?= $currentUser['role'] === 'admin' ? 'Pengaduan' : 'Pengaduan Saya'; ?></a>
<?php if ($currentUser['role'] === 'admin'): ?><a href="index.php?page=siswa">Siswa</a><a href="index.php?page=kategori">Kategori</a><a href="index.php?page=admin">Admin</a><?php endif; ?>
</div><div class="user">Halo, <?= htmlspecialchars($currentUser['nama']); ?>&nbsp;|&nbsp;<a href="index.php?page=logout">Logout</a></div></div>
<div class="wrap">
<?php
$flashSuccess = Flash::get('success');
$flashError = Flash::get('error');
?>
<?php if ($flashSuccess): ?><div class="alert success"><?= htmlspecialchars($flashSuccess); ?></div><?php endif; ?>
<?php if ($flashError): ?><div class="alert error"><?= htmlspecialchars($flashError); ?></div><?php endif; ?>
<div class="card">
