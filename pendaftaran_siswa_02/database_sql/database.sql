CREATE TABLE tb_jurusan (
    id_jurusan INT AUTO_INCREMENT PRIMARY KEY,
    nama_jurusan VARCHAR(100) NOT NULL
);

INSERT INTO tb_jurusan (nama_jurusan) VALUES
('Rekayasa Perangkat Lunak'),
('Teknik Komputer & Jaringan'),
('Desain Komunikasi Visual');

CREATE TABLE tb_siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(10) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    id_jurusan INT NOT NULL,
    foto VARCHAR(255) NOT NULL,
    alasan TEXT NOT NULL,
    FOREIGN KEY (id_jurusan)
        REFERENCES tb_jurusan(id_jurusan)
        ON DELETE CASCADE
);
