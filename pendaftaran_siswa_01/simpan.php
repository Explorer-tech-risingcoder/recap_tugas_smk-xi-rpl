<?php
include 'koneksi.php';

// Pastikan request benar-benar dari metode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// 1. Ambil & Sanitasi Data Input
$nis        = mysqli_real_escape_string($koneksi, $_POST['nis']);
$nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
$id_jurusan = mysqli_real_escape_string($koneksi, $_POST['id_jurusan']);
$alasan     = mysqli_real_escape_string($koneksi, $_POST['alasan']);

// 2. Ambil Informasi File Upload
$foto_name = $_FILES['foto']['name'];
$foto_tmp  = $_FILES['foto']['tmp_name'];
$foto_size = $_FILES['foto']['size'];
$ext       = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

$allowed_ext = ['jpg', 'jpeg', 'png'];
$max_size    = 2097152; // 2MB

// 3. Validasi Ekstensi File
if (!in_array($ext, $allowed_ext)) {
    echo "<script>
            alert('Gagal: Format file tidak diizinkan! Hanya JPG/PNG.');
            window.history.back();
          </script>";
    exit;
}

// 4. Validasi Ukuran File
if ($foto_size > $max_size) {
    echo "<script>
            alert('Gagal: Ukuran file terlalu besar! Maksimal 2MB.');
            window.history.back();
          </script>";
    exit;
}

// 5. Proses Pindah File (Upload)
$new_foto_name = time() . '_' . $nis . '.' . $ext;
$destination   = 'uploads/' . $new_foto_name;

if (!move_uploaded_file($foto_tmp, $destination)) {
    echo "<script>
            alert('Gagal upload file foto ke folder uploads.');
            window.history.back();
          </script>";
    exit;
}

// 6. Simpan ke Database
$sql = "INSERT INTO tb_siswa (nis, nama, id_jurusan, foto, alasan) 
        VALUES ('$nis', '$nama', '$id_jurusan', '$new_foto_name', '$alasan')";

if (mysqli_query($koneksi, $sql)) {
    // Jika sukses, kembali ke halaman utama
    header("Location: index.php");
    exit;
} else {
    // Jika gagal simpan ke database
    echo "Gagal simpan database: " . mysqli_error($koneksi);
}
?>
