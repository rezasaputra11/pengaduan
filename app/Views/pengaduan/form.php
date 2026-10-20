<?php use App\Core\Csrf; ?>
<h2><?= $mode === 'edit' ? 'Edit Pengaduan' : 'Tambah Pengaduan'; ?></h2>
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" action="index.php?page=pengaduan.<?= $mode === 'edit' ? 'edit&id=' . urlencode((string) $data['id_pelaporan']) : 'create'; ?>">
    <?= Csrf::field(); ?>
    <label>Siswa Pelapor</label>
    <select name="nis" required>
        <option value="">-- Pilih Siswa --</option>
        <?php foreach ($siswaList as $s): ?>
        <option value="<?= htmlspecialchars($s['nis']); ?>" <?= ((string) ($data['nis'] ?? '') === (string) $s['nis']) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($s['nama_siswa']); ?> | <?= htmlspecialchars($s['kelas']); ?> | NIS: <?= htmlspecialchars($s['nis']); ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label>Kategori</label>
    <select name="id_kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategoriList as $k): ?>
        <option value="<?= htmlspecialchars($k['id_kategori']); ?>" <?= ((string) ($data['id_kategori'] ?? '') === (string) $k['id_kategori']) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($k['ket_kategori']); ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label>Lokasi</label>
    <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi'] ?? ''); ?>" required>
    <label>Keterangan</label>
    <textarea name="ket" required><?= htmlspecialchars($data['ket'] ?? ''); ?></textarea>
    <?php if ($mode === 'edit'): ?>
    <label>Status</label>
    <select name="status" required>
        <?php foreach ($statusList as $s): ?>
        <option value="<?= $s; ?>" <?= (($data['status'] ?? '') === $s) ? 'selected' : ''; ?>><?= $s; ?></option>
        <?php endforeach; ?>
    </select>
    <label>Feedback</label>
    <textarea name="feedback"><?= htmlspecialchars($data['feedback'] ?? ''); ?></textarea>
    <?php endif; ?>
    <div class="form-actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn secondary" href="index.php?page=pengaduan">Batal</a>
    </div>
</form>
