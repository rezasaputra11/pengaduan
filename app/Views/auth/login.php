<?php use App\Core\Csrf; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Sistem Pengaduan</title>
<style>
*{box-sizing:border-box}body{font-family:Arial,sans-serif;background:#f5f6fa;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}.box{background:#fff;padding:35px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.08);width:340px}h2{text-align:center;margin-top:0}label{font-weight:bold;display:block;margin-top:14px}input,select{width:100%;padding:9px;margin-top:4px;border:1px solid #ccc;border-radius:5px}button{width:100%;margin-top:22px;padding:10px;background:#198754;color:#fff;border:0;border-radius:5px;font-weight:bold;cursor:pointer}.alert{background:#f8d7da;color:#842029;padding:10px;border-radius:6px;font-size:14px;margin-top:14px}.hint{font-size:12px;color:#666;margin-top:12px;text-align:center}
</style></head><body><div class="box">
<h2>Login</h2>
<?php if ($error): ?><div class="alert"><?= htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" action="index.php?page=login">
<?= Csrf::field(); ?>
<label>Login sebagai</label>
<select name="role" id="role" onchange="ubahLabel()"><option value="admin">Admin</option><option value="anggota">Anggota</option></select>
<label id="loginLabel">Username</label>
<input type="text" name="login" id="loginInput" required autofocus autocapitalize="none" autocorrect="off" autocomplete="username" spellcheck="false">
<label>Password</label><input type="password" name="password" required autocapitalize="none" autocorrect="off" autocomplete="current-password">
<button type="submit">Login</button>
</form>
<div class="hint" id="hint">Admin menggunakan username dan password admin.</div>
</div>
<script>function ubahLabel(){const a=document.getElementById('role').value==='anggota';document.getElementById('loginLabel').textContent=a?'NIS':'Username';document.getElementById('loginInput').type=a?'number':'text';document.getElementById('hint').textContent=a?'Anggota login menggunakan NIS dan password.':'Admin menggunakan username dan password admin.';}</script>
</body></html>
