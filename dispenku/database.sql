-- Create database if not exists
CREATE DATABASE IF NOT EXISTS dispenku;
USE dispenku;

-- Drop tables if they exist to avoid conflicts
DROP TABLE IF EXISTS izin;
DROP TABLE IF EXISTS siswa;
DROP TABLE IF EXISTS guru;
DROP TABLE IF EXISTS petugas;
DROP TABLE IF EXISTS kelas;

-- Create kelas table
CREATE TABLE IF NOT EXISTS kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create siswa table
CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    id_kelas INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id)
);

-- Create guru table
CREATE TABLE IF NOT EXISTS guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create petugas table
CREATE TABLE IF NOT EXISTS petugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create izin table
CREATE TABLE IF NOT EXISTS izin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    guru_id INT NOT NULL,
    waktu_keluar TIME NOT NULL,
    tanggal DATE NOT NULL,
    alasan TEXT NOT NULL,
    status_guru ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    status_petugas ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id),
    FOREIGN KEY (guru_id) REFERENCES guru(id)
);

-- Insert sample data
INSERT INTO kelas (nama_kelas) VALUES 
('X IPA 1'),
('X IPA 2'),
('X IPS 1'),
('X IPS 2');

INSERT INTO siswa (username, password, nama, id_kelas) VALUES 
('siswa1', 'password123', 'Siswa Satu', 1),
('siswa2', 'password123', 'Siswa Dua', 2);

INSERT INTO guru (username, password, nama) VALUES 
('guru1', 'password123', 'Guru Satu'),
('guru2', 'password123', 'Guru Dua');

INSERT INTO petugas (username, password, nama) VALUES 
('petugas1', 'password123', 'Petugas Satu'),
('petugas2', 'password123', 'Petugas Dua'); 