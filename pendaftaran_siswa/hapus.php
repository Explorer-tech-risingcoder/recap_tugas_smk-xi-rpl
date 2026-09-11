<?php 
include 'koneksi.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "DELETE FROM tb_siswa WHERE id='$id'";

  if (mysqli_query($koneksi, $sql)) {
    header("location: index.php");
    exit();
  } else {
    echo "error: " .mysqli_error($koneksi);
  }


}
?>
