<?php
session_start();
include '../../koneksi.php';

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$photo_profil = '';

// Validasi sederhana
if ($username === '' || $email === '' || $password === '' || $confirm_password === '') {
    header("Location: ../daftar.php?error=Lengkapi semua data!");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../daftar.php?email_error=Email tidak valid!");
    exit;
}
if (!preg_match('/@gmail\.com$/', $email)) {
    header("Location: ../daftar.php?email_error=Email harus menggunakan domain @gmail.com!");
    exit;
}
if ($password !== $confirm_password) {
    header("Location: ../daftar.php?error=Password tidak sama!");
    exit;
}
if (strlen($password) < 6) {
    header("Location: ../daftar.php?error=Password minimal 6 karakter!");
    exit;
}

// Cek username sudah ada
$stmt = $conn->prepare("SELECT id_users FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    header("Location: ../daftar.php?username_error=Username sudah terdaftar!");
    exit;
}
$stmt->close();

// Hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO users (username, email, user_password, photo_profil) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hash, $photo_profil);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../masuk.php?success=Berhasil daftar, silakan login!");
    exit;
} else {
    $stmt->close();
    header("Location: ../daftar.php?error=Gagal daftar, coba lagi!");
    exit;
}
?>