<?php $user = \App\Core\Auth::user(); ?>
<h2>Dashboard <?= $user['role'] === 'admin' ? 'Admin' : 'Anggota'; ?></h2>
<p>Selamat datang, <strong><?= htmlspecialchars($user['nama']); ?></strong>.</p>
<?php if ($user['role'] === 'admin'): ?>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;margin-top:20px;">
<a class="btn" href="index.php?page=pengaduan">Data Pengaduan</a><a class="btn" href="index.php?page=siswa">Data Siswa</a><a class="btn" href="index.php?page=kategori">Data Kategori</a><a class="btn" href="index.php?page=admin">Data Admin</a>
</div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;margin-top:20px;">
<a class="btn" href="index.php?page=pengaduan">Pengaduan Saya</a><a class="btn" href="index.php?page=pengaduan.create">Buat Pengaduan</a>
</div>
<?php endif; ?>
