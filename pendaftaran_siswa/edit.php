<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_siswa WHERE id='$id'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Siswa</title>

    <!-- Penghubung ke file CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container-edit">

        <h2>Edit Data Siswa</h2>

        <form action="update.php" method="POST">

            <!-- Input Hidden menyimpan Primary Key ID -->
            <input
                type="hidden"
                name="id"
                value="<?php echo $data['id']; ?>"
            >


            <div class="form-group">

                <label for="nis">
                    NIS:
                </label>

                <input
                    type="text"
                    id="nis"
                    name="nis"
                    value="<?php echo $data['nis']; ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="nama">
                    Nama Lengkap:
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?php echo $data['nama']; ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="jurusan">
                    Jurusan:
                </label>

                <select
                    id="jurusan"
                    name="jurusan"
                >

                    <option
                        value="RPL"
                        <?php
                        if ($data['jurusan'] == 'RPL') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Rekayasa Perangkat Lunak
                    </option>


                    <option
                        value="TKJ"
                        <?php
                        if ($data['jurusan'] == 'TKJ') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Teknik Komputer & Jaringan
                    </option>


                    <option
                        value="MM"
                        <?php
                        if ($data['jurusan'] == 'MM') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Multimedia
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label for="alasan">
                    Alasan Masuk:
                </label>

                <textarea
                    id="alasan"
                    name="alasan"
                    rows="3"
                ><?php echo $data['alasan']; ?></textarea>

            </div>


            <button
                type="submit"
                class="btn btn-blue"
            >
                Simpan Perubahan
            </button>


            <a
                href="index.php"
                class="btn btn-secondary"
            >
                Batal
            </a>

        </form>

    </div>

</body>
</html>

