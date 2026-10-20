<h2>Data Kategori</h2>
<p><a class="btn" href="index.php?page=kategori.create">+ Tambah Kategori</a></p>
<table>
<tr><th>No</th><th>Kategori</th><th>Keterangan</th><th>Prioritas</th><th>Status</th><th>Aksi</th></tr>
<?php if (empty($kategori)): ?>
<tr><td colspan="6" align="center">Belum ada data kategori.</td></tr>
<?php else: $no = 1; foreach ($kategori as $row): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($row['ket_kategori']); ?></td>
<td><?= htmlspecialchars($row['keterangan'] ?? ''); ?></td>
<td><?= htmlspecialchars($row['prioritas'] ?? ''); ?></td>
<td><?= htmlspecialchars($row['status'] ?? ''); ?></td>
<td class="aksi">
<a href="index.php?page=kategori.edit&id=<?= urlencode((string) $row['id_kategori']); ?>">Edit</a>
<a href="index.php?page=kategori.delete&id=<?= urlencode((string) $row['id_kategori']); ?>" onclick="return confirm('Hapus kategori ini?');">Hapus</a>
</td>
</tr>
<?php endforeach; endif; ?>
</table>
