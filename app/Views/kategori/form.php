<?php use App\Core\Csrf; ?>
<h2><?= $mode === 'edit' ? 'Edit Kategori' : 'Tambah Kategori'; ?></h2>
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" action="index.php?page=kategori.<?= $mode === 'edit' ? 'edit&id=' . urlencode((string) $data['id_kategori']) : 'create'; ?>">
    <?= Csrf::field(); ?>
    <label>Nama Kategori</label>
    <input type="text" name="ket_kategori" value="<?= htmlspecialchars($data['ket_kategori'] ?? ''); ?>" required>
    <label>Keterangan</label>
    <textarea name="keterangan"><?= htmlspecialchars($data['keterangan'] ?? ''); ?></textarea>
    <label>Prioritas</label>
    <select name="prioritas">
        <?php foreach (['Rendah', 'Sedang', 'Tinggi'] as $v): ?>
        <option value="<?= $v; ?>" <?= (($data['prioritas'] ?? '') === $v) ? 'selected' : ''; ?>><?= $v; ?></option>
        <?php endforeach; ?>
    </select>
    <label>Status</label>
    <select name="status">
        <?php foreach (['Aktif', 'Tidak Aktif'] as $v): ?>
        <option value="<?= $v; ?>" <?= (($data['status'] ?? '') === $v) ? 'selected' : ''; ?>><?= $v; ?></option>
        <?php endforeach; ?>
    </select>
    <div class="form-actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn secondary" href="index.php?page=kategori">Batal</a>
    </div>
</form>
