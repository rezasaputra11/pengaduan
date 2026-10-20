<h2>Data Siswa</h2>
<p><a class="btn" href="index.php?page=siswa.create">+ Tambah Siswa</a></p>
<table>
<tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Jurusan</th><th>No HP</th><th>Aksi</th></tr>
<?php if (empty($siswa)): ?>
<tr><td colspan="7" align="center">Belum ada data siswa.</td></tr>
<?php else: $no = 1; foreach ($siswa as $row): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($row['nis']); ?></td>
<td><?= htmlspecialchars($row['nama_siswa']); ?></td>
<td><?= htmlspecialchars($row['kelas']); ?></td>
<td><?= htmlspecialchars($row['jurusan']); ?></td>
<td><?= htmlspecialchars($row['no_hp'] ?? ''); ?></td>
<td class="aksi">
<a href="index.php?page=siswa.edit&nis=<?= urlencode((string) $row['nis']); ?>">Edit</a>
<a href="index.php?page=siswa.delete&nis=<?= urlencode((string) $row['nis']); ?>" onclick="return confirm('Hapus siswa ini beserta pengaduannya?');">Hapus</a>
</td>
</tr>
<?php endforeach; endif; ?>
</table>
