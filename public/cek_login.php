<?php
session_start();
// ALAT DIAGNOSA SEMENTARA - HAPUS FILE INI SETELAH LOGIN BERHASIL!
require __DIR__ . '/../app/Core/Database.php';
$db = App\Core\Database::getConnection();
header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['reset'])) {
    foreach (['admin','siswa'] as $t) {
        if (!mysqli_query($db, "ALTER TABLE $t MODIFY password VARCHAR(255) NOT NULL")) {
            echo "<p style='color:red'>ALTER $t gagal: " . htmlspecialchars(mysqli_error($db)) . '</p>';
        }
    }
    $def = ['admin01'=>'admin123','admin02'=>'admin456','operator01'=>'operator123',
            'operator02'=>'operator456','admin03'=>'admin789','operator03'=>'operator789'];
    foreach ($def as $u => $p) {
        $st = mysqli_prepare($db, 'UPDATE admin SET password=? WHERE username=?');
        mysqli_stmt_bind_param($st, 'ss', $p, $u);
        mysqli_stmt_execute($st);
    }
    echo '<p><b>Password admin direset ke default (lihat tabel di bawah). Coba login lagi.</b></p>';
}

echo '<h3>Database: ' . htmlspecialchars(mysqli_fetch_row(mysqli_query($db, 'SELECT DATABASE()'))[0]) . '</h3>';
$r = mysqli_query($db, 'SELECT id_admin, username, password FROM admin');
if (!$r) { die('Tabel admin error: ' . htmlspecialchars(mysqli_error($db))); }
echo '<table border=1 cellpadding=5><tr><th>id</th><th>username</th><th>password (di DB)</th></tr>';
while ($row = mysqli_fetch_assoc($r)) {
    echo '<tr><td>' . $row['id_admin'] . '</td><td>[' . htmlspecialchars($row['username']) . ']</td><td>[' . htmlspecialchars($row['password']) . ']</td></tr>';
}
$c = mysqli_fetch_assoc(mysqli_query($db, "SHOW COLUMNS FROM admin LIKE 'password'"));
echo '</table><p>Tipe kolom password: <b>' . htmlspecialchars($c['Type']) . '</b> (harus varchar(255))</p>';
echo '<p>Jumlah baris di atas = 0 berarti data belum ter-import.</p>';
echo '<p><a href="?reset=1">Reset password admin ke default</a></p>';

// ---- TES LOGIN LANGKAH DEMI LANGKAH ----
echo '<hr><h3>Tes login (diagnosa)</h3>';
$_SESSION['tes'] = ($_SESSION['tes'] ?? 0) + 1;
echo '<p>Session ID: ' . htmlspecialchars(session_id()) . ' | counter sesi: <b>' . $_SESSION['tes'] . '</b> (harus naik 1,2,3 tiap refresh; kalau selalu 1 = SESI TIDAK TERSIMPAN)</p>';
echo '<p>Save path: ' . htmlspecialchars(session_save_path() ?: '(default)') . ' | writable: ' . (is_writable(session_save_path() ?: sys_get_temp_dir()) ? 'ya' : 'TIDAK') . ' | PHP ' . PHP_VERSION . '</p>';
echo '<form method="post"><input name="u" placeholder="username"> <input name="p" placeholder="password"> <button>Tes</button></form>';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['u'] ?? ''); $p = $_POST['p'] ?? '';
    echo '<p>POST diterima: username=[' . htmlspecialchars($u) . '] panjang password=' . strlen($p) . '</p>';
    $st = mysqli_prepare($db, 'SELECT * FROM admin WHERE username = ?');
    mysqli_stmt_bind_param($st, 's', $u); mysqli_stmt_execute($st);
    $a = mysqli_stmt_get_result($st)->fetch_assoc();
    if (!$a) { echo '<p style="color:red">Username TIDAK ditemukan.</p>'; }
    else {
        echo '<p>Username ditemukan. Password di DB: [' . htmlspecialchars($a['password']) . ']</p>';
        echo hash_equals($a['password'], $p) ? '<p style="color:green">Password COCOK (plain).</p>' : '<p style="color:red">Password TIDAK cocok.</p>';
    }
}
