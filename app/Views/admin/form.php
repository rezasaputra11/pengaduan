<?php use App\Core\Csrf; ?>
<h2><?= $mode === 'edit' ? 'Edit Admin' : 'Tambah Admin'; ?></h2>
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" action="index.php?page=admin.<?= $mode === 'edit' ? 'edit&id=' . urlencode((string) $data['id_admin']) : 'create'; ?>">
    <?= Csrf::field(); ?>
    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($data['username'] ?? ''); ?>" required>
    <label>Password <?= $mode === 'edit' ? '(kosongkan jika tidak diubah)' : ''; ?></label>
    <input type="password" name="password" <?= $mode === 'edit' ? '' : 'required'; ?>>
    <label>Nama Admin</label>
    <input type="text" name="nama_admin" value="<?= htmlspecialchars($data['nama_admin'] ?? ''); ?>" required>
    <label>Gmail</label>
    <input type="email" name="gmail" value="<?= htmlspecialchars($data['gmail'] ?? ''); ?>" required>
    <div class="form-actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn secondary" href="index.php?page=admin">Batal</a>
    </div>
</form>
