<h2>Data Pengaduan / Aspirasi</h2>
<p><a class="btn" href="index.php?page=pengaduan.create">+ Tambah Pengaduan</a></p>
<table>
<tr>
<th>No</th><th>NIS</th><th>Nama Siswa</th><th>Kategori</th><th>Lokasi</th><th>Keterangan</th><th>Status</th><th>Feedback</th><th>Aksi</th>
</tr>
<?php if (empty($pengaduan)): ?>
<tr><td colspan="9" align="center">Belum ada data pengaduan.</td></tr>
<?php else: $no = 1; foreach ($pengaduan as $row): ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($row['nis']); ?></td>
<td><?= htmlspecialchars($row['nama_siswa']); ?></td>
<td><?= htmlspecialchars($row['ket_kategori']); ?></td>
<td><?= htmlspecialchars($row['lokasi']); ?></td>
<td><?= htmlspecialchars($row['ket']); ?></td>
<td><?= htmlspecialchars($row['status']); ?></td>
<td><?= htmlspecialchars($row['feedback']); ?></td>
<td class="aksi">
<a href="index.php?page=pengaduan.edit&id=<?= urlencode((string) $row['id_pelaporan']); ?>">Edit</a>
<a href="index.php?page=pengaduan.delete&id=<?= urlencode((string) $row['id_pelaporan']); ?>" onclick="return confirm('Hapus pengaduan ini?');">Hapus</a>
</td>
</tr>
<?php endforeach; endif; ?>
</table>
