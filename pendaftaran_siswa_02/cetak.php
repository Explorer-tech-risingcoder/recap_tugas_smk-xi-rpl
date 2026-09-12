<?php
include 'koneksi.php';

// AMAN DARI SQL INJECTION
$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$query = mysqli_query($koneksi, "
    SELECT tb_siswa.*, tb_jurusan.nama_jurusan 
    FROM tb_siswa
    JOIN tb_jurusan ON tb_siswa.id_jurusan = tb_jurusan.id_jurusan
    WHERE tb_siswa.id='$id'
");

$data = mysqli_fetch_assoc($query);

// Hentikan proses jika data tidak ditemukan (URL diubah sembarangan)
if (!$data) { 
    die("Data tidak ditemukan atau ID tidak valid."); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pendaftaran - <?= htmlspecialchars($data['nama']); ?></title>
    
    <!-- Memanggil file CSS eksternal -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="card-print">
        <div class="card-header-print">
            <h3>BUKTI PENDAFTARAN SISWA</h3>
            <small>SMK NEGERI KEBANGSAAN</small>
        </div>
        
        <div class="card-body-print">
            <!-- Tampilkan Pasfoto -->
            <img src="uploads/<?= htmlspecialchars($data['foto']); ?>" class="pasfoto-print" alt="Pasfoto Siswa">
            
            <div class="info-print">
                <table>
                    <tr>
                        <td width="30%"><strong>NIS</strong></td>
                        <td>: <?= htmlspecialchars($data['nis']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: <?= htmlspecialchars($data['nama']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jurusan</strong></td>
                        <td>: <?= htmlspecialchars($data['nama_jurusan']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: <strong>TERDAFTAR</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Tombol Cetak Manual -->
        <button class="btn-print" onclick="window.print()">Cetak Kartu (Print)</button>
    </div>

    <!-- Script otomatis membuka jendela print saat halaman pertama kali dimuat -->
    <script>
        window.onload = function() { 
            window.print(); 
        }
    </script>

</body>
</html>
