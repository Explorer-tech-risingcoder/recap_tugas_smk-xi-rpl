<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Siswa Baru</title>

    <!-- Penghubung ke file CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">

        <h2>Form Pendaftaran Siswa Baru</h2>
        <h1> GITHUB </h1>

        <form action="simpan.php" method="POST" onsubmit="return validasiForm()">

            <div class="form-group">
                <label for="nis">NIS:</label>

                <input
                    type="text"
                    id="nis"
                    name="nis"
                >

                <span id="errNis" class="error">
                    NIS wajib diisi angka!
                </span>
            </div>


            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                >

                <span id="errNama" class="error">
                    Nama wajib diisi!
                </span>
            </div>


            <div class="form-group">
                <label for="jurusan">Jurusan:</label>

                <select id="jurusan" name="jurusan">
                    <option value="RPL">
                        Rekayasa Perangkat Lunak
                    </option>

                    <option value="TKJ">
                        Teknik Komputer & Jaringan
                    </option>

                    <option value="MM">
                        Multimedia
                    </option>
                </select>
            </div>


            <div class="form-group">
                <label for="alasan">Alasan Masuk:</label>

                <textarea
                    id="alasan"
                    name="alasan"
                    rows="3"
                ></textarea>
            </div>


            <button type="submit" class="btn btn-green">
                Daftar Siswa
            </button>

        </form>


        <hr class="separator">


        <h2>Daftar Siswa Terdaftar</h2>

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Alasan</th>
                    <th>Aksi (Koneksi Form)</th>
                </tr>
            </thead>

            <tbody>

                <?php
                $no = 1;

                $query = mysqli_query(
                    $koneksi,
                    "SELECT * FROM tb_siswa ORDER BY id DESC"
                );

                while ($row = mysqli_fetch_assoc($query)) {
                ?>

                    <tr>
                        <td><?= $no ?></td>

                        <td><?= $row['nis'] ?></td>

                        <td><?= $row['nama'] ?></td>

                        <td><?= $row['jurusan'] ?></td>

                        <td><?= $row['alasan'] ?></td>

                        <td>
                            <a
                                href="edit.php?id=<?= $row['id'] ?>"
                                class="btn btn-warning"
                            >
                                Edit
                            </a>

                            <a
                                href="hapus.php?id=<?= $row['id'] ?>"
                                class="btn btn-danger"
                                onclick="return confirm('Hapus data ini?')"
                            >
                                Hapus
                            </a>
                        </td>
                    </tr>

                <?php
                    $no++;
                }
                ?>

            </tbody>

        </table>

    </div>


    <script>
        function validasiForm() {

            let nis = document.getElementById("nis").value;
            let nama = document.getElementById("nama").value;

            let valid = true;


            if (nis.trim() === "" || isNaN(nis)) {

                document.getElementById("errNis").style.display = "block";

                valid = false;

            } else {

                document.getElementById("errNis").style.display = "none";

            }


            if (nama.trim() === "") {

                document.getElementById("errNama").style.display = "block";

                valid = false;

            } else {

                document.getElementById("errNama").style.display = "none";

            }


            return valid;
        }
    </script>

</body>
</html>

