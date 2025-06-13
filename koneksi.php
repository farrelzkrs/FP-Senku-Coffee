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
?>