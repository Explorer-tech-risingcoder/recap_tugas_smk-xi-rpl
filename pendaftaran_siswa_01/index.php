<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Siswa Baru - db_siswa</title>
    <!-- Memanggil file CSS eksternal -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container-index">
    
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Form Pendaftaran Siswa Baru</h2>
        <a href="jurusan.php" class="btn btn-purple">Kelola Master Jurusan »</a>
    </div>

    <!-- FORM PENDAFTARAN DENGAN UPLOAD FILE -->
    <form action="simpan.php" method="POST" enctype="multipart/form-data">
        <div class="form-group-index">
            <label>NIS:</label>
            <input type="text" name="nis" required>
        </div>
        <div class="form-group-index">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required>
        </div>
        <div class="form-group-index">
            <label>Pilih Jurusan (Relasi Data):</label>
            <select name="id_jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <?php
                $jur = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
                while($j = mysqli_fetch_assoc($jur)){
                    echo "<option value='{$j['id_jurusan']}'>{$j['nama_jurusan']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group-index">
            <label>Upload Pasfoto (JPG/PNG, Max 2MB):</label>
            <input type="file" name="foto" accept="image/*" required>
        </div>
        <div class="form-group-index">
            <label>Alasan Masuk:</label>
            <textarea name="alasan" rows="2" required></textarea>
        </div>
        <button type="submit" class="btn btn-green">Daftar Sekarang</button>
    </form>

    <hr style="margin: 25px 0;">

    <h3>Daftar Siswa Terdaftar</h3>

    <!-- FORM PENCARIAN & FILTER -->
    <form method="GET" class="filter-box">
        <input type="text" name="keyword" placeholder="Cari NIS / Nama..." 
               value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" 
               style="flex: 2;">
        
        <select name="filter_jurusan" style="flex: 1;">
            <option value="">Semua Jurusan</option>
            <?php
            $jur2 = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
            while($j2 = mysqli_fetch_assoc($jur2)){
                $selected = (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] == $j2['id_jurusan']) ? 'selected' : '';
                echo "<option value='{$j2['id_jurusan']}' $selected>{$j2['nama_jurusan']}</option>";
            }
            ?>
        </select>
        
        <button type="submit" class="btn btn-blue">Cari & Filter</button>
        <a href="index.php" class="btn btn-danger">Reset</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Pasfoto</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Alasan</th>
                <th>Aksi</th>
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
            $query = mysqli_query($koneksi, $sql);
            
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>
                            <td><img src='uploads/{$row['foto']}' class='img-thumb' alt='Foto'></td>
                            <td>{$row['nis']}</td>
                            <td>{$row['nama']}</td>
                            <td>{$row['nama_jurusan']}</td>
                            <td>{$row['alasan']}</td>
                            <td>
                                <a href='cetak.php?id={$row['id']}' target='_blank' class='btn btn-blue'>Cetak Kartu</a>
                                <a href='hapus.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Data tidak ditemukan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
