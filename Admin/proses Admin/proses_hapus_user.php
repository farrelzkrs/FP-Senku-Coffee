<?php
// filepath: c:\laragon\www\FP Pemweb\Admin\proses_hapus_user.php
session_start();
include '../koneksi.php';

$id_users = intval($_POST['id_users'] ?? 0);
$username_pengunjung = $_POST['username_pengunjung'] ?? '';
$admin_password = $_POST['admin_password'] ?? '';

// Cek password admin (misal: password statis, atau cek di DB)
$hapus_ok = false;
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $admin_password === 'admin123') {
    $hapus_ok = true;
}

if ($hapus_ok && $id_users > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id_users = ?");
    $stmt->bind_param("i", $id_users);
    $stmt->execute();
    $stmt->close();
    $pesan_sukses = "Akun " . htmlspecialchars($username_pengunjung) . " berhasil dihapus.";
    header("Location: datapengunjung.php?success=" . urlencode($pesan_sukses));
    exit;
} else {
    $pesan_error = "Password admin salah atau data tidak valid.";
    header("Location: datapengunjung.php?error=" . urlencode($pesan_error));
    exit;
}
?>