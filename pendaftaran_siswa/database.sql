CREATE DATABASE db_siswa_lazarus;
USE db_siswa_lazarus;

CREATE TABLE tb_siswa (
  id INT AUTO_INCREMENT PRIMARY KEY, 
  nis VARCHAR(10) NOT NULL, 
  nama VARCHAR(100) NOT NULL,
  jurusan VARCHAR(50) NOT NULL,
  alasan TEXT NOT NULL
);
