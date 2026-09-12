<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Proses Tambah Data
if (isset($_POST['tambah'])) {
    $nama_jurusan = mysqli_real_escape_string($koneksi, $_POST['nama_jurusan']);
    mysqli_query($koneksi, "INSERT INTO tb_jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
    header("Location: jurusan.php");
    exit;
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM tb_jurusan WHERE id_jurusan='$id'");
    header("Location: jurusan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Jurusan - db_siswa</title>
    <!-- Memanggil file CSS eksternal -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container-jurusan">
    <a href="index.php" class="btn btn-nav">« Kembali ke Dashboard</a>
    
    <h2>Kelola Master Jurusan</h2>
    
    <form method="POST" class="form-group-jurusan">
        <input type="text" name="nama_jurusan" placeholder="Nama Jurusan Baru..." required class="form-control-jurusan">
        <button type="submit" name="tambah" class="btn btn-green">Tambah Jurusan</button>
    </form>

    <table>
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th>Nama Jurusan</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q = mysqli_query($koneksi, "SELECT * FROM tb_jurusan ORDER BY id_jurusan DESC");
            if(mysqli_num_rows($q) > 0):
                while($r = mysqli_fetch_assoc($q)): 
            ?>
                <tr>
                    <td><?= htmlspecialchars($r['id_jurusan']); ?></td>
                    <td><?= htmlspecialchars($r['nama_jurusan']); ?></td>
                    <td>
                        <a href="jurusan.php?hapus=<?= $r['id_jurusan']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Yakin ingin menghapus jurusan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php 
                endwhile; 
            else:
            ?>
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada data jurusan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
