<?php
$host = "localhost";
$port = "3307"; // Port yang Anda gunakan (bukan default 3306)
$user = "root";
$password = "";
$namadb = "senku_coffee";

// Perbaikan: gunakan variabel $conn agar konsisten dengan file lain
$conn = new mysqli($host, $user, $password, $namadb, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tabel akun admin/user
$conn->query("CREATE TABLE IF NOT EXISTS users (
  id_users INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(150) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL,
  user_password VARCHAR(100) NOT NULL,
  photo_profil VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Tabel promosi
$conn->query("CREATE TABLE IF NOT EXISTS promosi (
  id_promosi INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(100),
  gambar_url VARCHAR(255),
  deskripsi TEXT,
  tanggal_mulai DATE,
  tanggal_berakhir DATE,
  is_aktif BOOLEAN DEFAULT TRUE
)");

// Tabel jenis kopi
$conn->query("CREATE TABLE IF NOT EXISTS jeniskopi (
  id_kopi INT AUTO_INCREMENT PRIMARY KEY,
  nama_jenis VARCHAR(50),
  gambar_kopi VARCHAR(255),
  deskripsi TEXT
)");

// Tabel produk
$conn->query("CREATE TABLE IF NOT EXISTS produk (
  id_produk INT AUTO_INCREMENT PRIMARY KEY,
  nama_produk VARCHAR(100),
  deskripsi TEXT,
  harga DECIMAL(10, 2),
  gambar_url VARCHAR(255),
  jenis_kopi_id INT,
  FOREIGN KEY (jenis_kopi_id) REFERENCES jeniskopi(id_kopi)
)");

// Tabel ulasan
$conn->query("CREATE TABLE IF NOT EXISTS ulasan (
  id_ulasan INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  rating INT CHECK (rating >= 1 AND rating <= 5),
  deskripsi TEXT,
  tanggal_ulasan DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_approved BOOLEAN DEFAULT FALSE,
  jawaban_admin TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id_users)
)");

// Tabel galeri
$conn->query("CREATE TABLE IF NOT EXISTS galeri (
  id_galeri INT AUTO_INCREMENT PRIMARY KEY,
  judul_foto VARCHAR(100),
  deskripsi_foto VARCHAR(255),
  foto_url VARCHAR(255) NOT NULL,
  is_aktif BOOLEAN DEFAULT TRUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Tabel feedback
$conn->query("CREATE TABLE IF NOT EXISTS feedback (
  id_feedback INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  user_id INT,
  pesan TEXT,
  tanggal_kirim DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id_users)
)");
$conn->query("CREATE TABLE IF NOT EXISTS poster (
	id_poster INT AUTO_INCREMENT PRIMARY KEY,
    judul_post VARCHAR(100),
    url_post VARCHAR(255),
    caption TEXT,
    is_aktif BOOLEAN DEFAULT TRUE
)");

$conn->query("CREATE TABLE IF NOT EXISTS admins (
    id_admins INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL -- simpan hash password
)");
?>