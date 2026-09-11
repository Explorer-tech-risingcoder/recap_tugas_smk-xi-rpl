<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
  $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan']);

  $sql = "INSERT INTO tb_siswa (nis, nama, jurusan, alasan) VALUES ('$nis', '$nama', '$jurusan', '$alasan')";

  if (mysqli_query($koneksi, $sql)) {
    header("location: index.php");
    exit();
  
  } else {
    echo "Erorr dah: " .mysqli_error($koneksi);
  }
}
?>
