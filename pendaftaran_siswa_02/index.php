<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Hitung Statistik Agregat untuk Widget Card
$q_siswa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_siswa");
$total_siswa = mysqli_fetch_assoc($q_siswa)['total'];

$q_jurusan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_jurusan");
$total_jurusan = mysqli_fetch_assoc($q_jurusan)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pendaftaran Siswa</title>
    
    <!-- Panggil CSS Khusus Dashboard -->
    <link rel="stylesheet" href="css/index.css">
</head>

<!-- Tambahkan class dashboard-mode agar tidak bentrok dengan halaman lain -->
<body class="dashboard-mode">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="sidebar">
        <div class="brand">SMK Digital App</div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="index.php" class="nav-link active">Dashboard Utama</a>
            </li>
            <li class="nav-item">
                <a href="jurusan.php" class="nav-link">Master Data Jurusan</a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="main-content">
        
        <!-- TOPBAR -->
        <header class="topbar">
            <h1>Dashboard Pendaftaran Siswa Baru</h1>
            <div class="user-profile">Petugas: <strong>Administrator</strong></div>
        </header>

        <!-- STATS WIDGET CARDS -->
        <section class="stats-grid">
            <div class="stat-card">
                <p>Total Siswa Terdaftar</p>
                <h2><?= $total_siswa; ?></h2>
            </div>
            <div class="stat-card">
                <p>Jumlah Jurusan Tersedia</p>
                <h2><?= $total_jurusan; ?></h2>
            </div>
        </section>

        <!-- PANEL DATA SISWA -->
        <section class="panel">
            <div class="panel-header">
                <div class="panel-title">Daftar Calon Siswa Baru</div>
                <button class="btn btn-primary" onclick="openModal()">+ Tambah Siswa Baru</button>
            </div>

            <!-- FILTER & SEARCH FORM -->
            <form method="GET" class="filter-row">
                <!-- Gunakan htmlspecialchars untuk mencegah XSS Hack -->
                <input type="text" name="keyword" class="form-control" placeholder="Cari NIS atau Nama..."
                       value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                
                <select name="filter_jurusan" class="form-control" style="max-width: 200px;">
                    <option value="">Semua Jurusan</option>
                    <?php
                    $j_query = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
                    while($j_row = mysqli_fetch_assoc($j_query)):
                        $selected = (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] == $j_row['id_jurusan']) ? 'selected' : '';
                    ?>
                        <option value="<?= $j_row['id_jurusan'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($j_row['nama_jurusan']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="index.php" class="btn btn-secondary" style="background: #f1f5f9;">Reset</a>
            </form>

            <!-- TABEL DATA -->
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Pasfoto</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Pilihan Jurusan</th>
                        <th>Alasan Masuk</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // QUERY DENGAN JOIN & FILTER LIKE
                    $sql = "SELECT tb_siswa.*, tb_jurusan.nama_jurusan
                            FROM tb_siswa
                            JOIN tb_jurusan ON tb_siswa.id_jurusan = tb_jurusan.id_jurusan
                            WHERE 1=1";
                            
                    if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
                        $kw = mysqli_real_escape_string($koneksi, $_GET['keyword']);
                        $sql .= " AND (tb_siswa.nama LIKE '%$kw%' OR tb_siswa.nis LIKE '%$kw%')";
                    }
                    if (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] != '') {
                        $fj = mysqli_real_escape_string($koneksi, $_GET['filter_jurusan']);
                        $sql .= " AND tb_siswa.id_jurusan = '$fj'";
                    }
                    $sql .= " ORDER BY tb_siswa.id DESC";
                    
                    $result = mysqli_query($koneksi, $sql);
                    
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <tr>
                            <td><img src="uploads/<?= $row['foto'] ?>" class="img-thumb" alt="Foto"></td>
                            <td><strong><?= htmlspecialchars($row['nis']) ?></strong></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['nama_jurusan']) ?></td>
                            <td><?= htmlspecialchars($row['alasan']) ?></td>
                            <td>
                                <a href="cetak.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-blue btn-sm">Cetak</a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data siswa ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color: #94a3b8; padding: 20px;">
                                Data siswa tidak ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- MODAL POPUP FORM PENDAFTARAN -->
    <div class="modal-overlay" id="modalForm">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Form Pendaftaran Siswa Baru</h3>
                <button type="button" onclick="closeModal()" class="modal-close">×</button>
            </div>
            
            <form action="simpan.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>NIS:</label>
                    <input type="text" name="nis" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Pilihan Jurusan:</label>
                    <select name="id_jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <?php
                        $j_list = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
                        while($jl = mysqli_fetch_assoc($j_list)):
                        ?>
                            <option value="<?= $jl['id_jurusan'] ?>">
                                <?= htmlspecialchars($jl['nama_jurusan']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Pasfoto (JPG/PNG, Max 2MB):</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label>Alasan Masuk:</label>
                    <textarea name="alasan" class="form-control" rows="2" required></textarea>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT UNTUK MODAL -->
    <script>
        function openModal() { document.getElementById('modalForm').style.display = 'flex'; }
        function closeModal() { document.getElementById('modalForm').style.display = 'none'; }
    </script>
</body>
</html>
