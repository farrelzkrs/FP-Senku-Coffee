<?php
// filepath: c:\laragon\www\FP Pemweb\Admin\file_hapus_produk.php
session_start();
include '../koneksi.php';

// Pastikan admin login
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}

// Fungsi verifikasi password admin
function cek_password_admin($conn, $password) {
    if (!isset($_SESSION['admin_username'])) return false;
    // Ganti 'admin' menjadi 'admins'
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username=?");
    $stmt->bind_param("s", $_SESSION['admin_username']);
    $stmt->execute();
    $hash = null;
    $stmt->bind_result($hash);
    $found = $stmt->fetch();
    $stmt->close();
    return $found && $hash && password_verify($password, $hash);
}

// Hapus Produk Kopi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['admin_password'])) {
    $id = intval($_POST['id']);
    $admin_password = $_POST['admin_password'];

    if (!cek_password_admin($conn, $admin_password)) {
        $_SESSION['pesan_error'] = "Password admin salah!";
        header('Location: produk.php');
        exit;
    }

    // Hapus gambar produk jika ada
    $q = $conn->prepare("SELECT gambar_url FROM produk WHERE id_produk=?");
    $q->bind_param("i", $id);
    $q->execute();
    $q->bind_result($gambar);
    $q->fetch();
    $q->close();
    if ($gambar && file_exists(__DIR__ . '/../uploads/' . $gambar)) {
        unlink(__DIR__ . '/../uploads/' . $gambar);
    }

    // Hapus produk dari database
    $del = $conn->prepare("DELETE FROM produk WHERE id_produk=?");
    $del->bind_param("i", $id);
    $del->execute();
    $del->close();

    $_SESSION['pesan_sukses'] = "Produk berhasil dihapus.";
    header('Location: produk.php');
    exit;
}

// Hapus Jenis Kopi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_kopi'], $_POST['admin_password'])) {
    $id_kopi = intval($_POST['id_kopi']);
    $admin_password = $_POST['admin_password'];

    if (!cek_password_admin($conn, $admin_password)) {
        $_SESSION['pesan_error'] = "Password admin salah!";
        header('Location: produk.php');
        exit;
    }

    // Hapus gambar jenis kopi jika ada
    $q = $conn->prepare("SELECT gambar_kopi FROM jeniskopi WHERE id_kopi=?");
    $q->bind_param("i", $id_kopi);
    $q->execute();
    $q->bind_result($gambar);
    $q->fetch();
    $q->close();
    if ($gambar && file_exists(__DIR__ . '/../uploads/' . $gambar)) {
        unlink(__DIR__ . '/../uploads/' . $gambar);
    }

    // Hapus jenis kopi dari database
    $del = $conn->prepare("DELETE FROM jeniskopi WHERE id_kopi=?");
    $del->bind_param("i", $id_kopi);
    $del->execute();
    $del->close();

    $_SESSION['pesan_sukses'] = "Jenis kopi berhasil dihapus.";
    header('Location: produk.php');
    exit;
}

// Jika tidak ada aksi, kembali ke produk.php
header('Location: produk.php');
exit;