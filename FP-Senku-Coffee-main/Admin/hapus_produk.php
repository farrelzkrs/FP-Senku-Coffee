<?php
// filepath: c:\laragon\www\FP Pemweb\Admin\hapus_produk.php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}
include '../koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id) {
    // Hapus gambar jika ada
    $q = $conn->prepare("SELECT gambar_url FROM produk WHERE id_produk=?");
    $q->bind_param("i", $id);
    $q->execute();
    $q->bind_result($gambar);
    $q->fetch();
    $q->close();
    if ($gambar && file_exists(__DIR__ . '/uploads/' . $gambar)) {
        unlink(__DIR__ . '/uploads/' . $gambar);
    }

    // Hapus data produk
    $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: produk.php?success=Produk berhasil dihapus");
    exit;
} else {
    header("Location: produk.php?error=ID produk tidak valid");
    exit;
}