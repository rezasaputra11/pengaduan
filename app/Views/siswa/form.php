<?php use App\Core\Csrf; ?>
<h2><?= $mode === 'edit' ? 'Edit Data Siswa' : 'Tambah Data Siswa'; ?></h2>
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" action="index.php?page=siswa.<?= $mode === 'edit' ? 'edit&nis=' . urlencode((string) $data['nis']) : 'create'; ?>">
    <?= Csrf::field(); ?>
    <label>NIS</label>
    <input type="text" name="nis" value="<?= htmlspecialchars($data['nis'] ?? ''); ?>" <?= $mode === 'edit' ? 'readonly' : 'required'; ?>>
    <label>Nama Siswa</label>
    <input type="text" name="nama_siswa" value="<?= htmlspecialchars($data['nama_siswa'] ?? ''); ?>" required>
    <label>Kelas</label>
    <input type="text" name="kelas" value="<?= htmlspecialchars($data['kelas'] ?? ''); ?>" required>
    <label>Jurusan</label>
    <input type="text" name="jurusan" value="<?= htmlspecialchars($data['jurusan'] ?? ''); ?>" required>
    <label>No HP / WA</label>
    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp'] ?? ''); ?>">
    <div class="form-actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn secondary" href="index.php?page=siswa">Batal</a>
    </div>
</form>
