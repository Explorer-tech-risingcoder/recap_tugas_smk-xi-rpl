<?php 
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id'];
  $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
  $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan']);

  $sql = "UPDATE tb_siswa SET nis='$nis', nama='$nama', jurusan='$jurusan', alasan='$alasan' WHERE id='$id'";

  if (mysqli_query($koneksi, $sql)) {
    header("location: index.php");
    exit();

  } else {
    echo "Error: " .mysqli_error($koneksi);

  }
}
?>
