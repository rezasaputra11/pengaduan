<h2>Data Admin</h2>
<p><a class="btn" href="index.php?page=admin.create">+ Tambah Admin</a></p>
<table>
<tr><th>No</th><th>Username</th><th>Nama Admin</th><th>Gmail</th><th>Aksi</th></tr>
<?php if (empty($admins)): ?>
<tr><td colspan="5" align="center">Belum ada data admin.</td></tr>
<?php else: $no = 1; foreach ($admins as $row): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($row['username']); ?></td>
<td><?= htmlspecialchars($row['nama_admin']); ?></td>
<td><?= htmlspecialchars($row['gmail']); ?></td>
<td class="aksi">
<a href="index.php?page=admin.edit&id=<?= urlencode((string) $row['id_admin']); ?>">Edit</a>
<a href="index.php?page=admin.delete&id=<?= urlencode((string) $row['id_admin']); ?>" onclick="return confirm('Hapus admin ini?');">Hapus</a>
</td>
</tr>
<?php endforeach; endif; ?>
</table>
