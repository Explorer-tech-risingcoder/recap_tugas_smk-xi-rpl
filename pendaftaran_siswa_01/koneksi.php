<?php 
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_siswa_lazarus_02"; #nama database sql sesuaikan

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
  die("koneksi kayaknya gagal: " .mysqli_connect_error());
}
?>

