<?php
include 'koneksi.php';

// 1. Guard Clause: Pastikan parameter 'id' ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

// 2. Amankan input dari SQL Injection
$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// 3. Cari data foto berdasarkan ID di database
$query = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id='$id'");

// 4. Guard Clause: Pastikan data siswa benar-benar ada di database
if (mysqli_num_rows($query) === 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);
$foto_lama = $data['foto'];

// 5. Hapus file fisik foto (Pastikan nama foto tidak kosong & filenya benar-benar ada)
if (!empty($foto_lama) && file_exists("uploads/" . $foto_lama)) {
    unlink("uploads/" . $foto_lama);
}

// 6. Hapus data dari tabel database
$hapus_sql = "DELETE FROM tb_siswa WHERE id='$id'";

if (mysqli_query($koneksi, $hapus_sql)) {
    // Jika sukses terhapus, kembali ke halaman utama
    header("Location: index.php");
    exit;
} else {
    // Jika gagal terhapus karena kendala database (munculkan pop-up lalu kembali)
    echo "<script>
            alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
            window.location.href = 'index.php';
          </script>";
    exit;
}
?>
