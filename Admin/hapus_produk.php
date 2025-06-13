<?php
// filepath: c:\laragon\www\FP Pemweb\Admin\file_hapus_produk.php
session_start();
include '../koneksi.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true || !isset($_SESSION['admin_username'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: ../Bf Login/masuk.php');
    exit;
}

/**
 * Fungsi untuk memverifikasi password admin saat ini.
 * @param mysqli $conn Koneksi database
 * @param string $password Password yang diinput
 * @return bool True jika password cocok, false jika tidak.
 */
function cek_password_admin($conn, $password)
{
    // Ambil hashed password dari database berdasarkan username di session
    $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    if ($stmt === false) {
        // Gagal mempersiapkan statement
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    $stmt->bind_param("s", $_SESSION['admin_username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $hashed_password = $admin['password'];
        $stmt->close();
        // Verifikasi password yang diinput dengan hash dari database
        return password_verify($password, $hashed_password);
    }
    
    $stmt->close();
    return false;
}

// Cek apakah request method adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_password = $_POST['admin_password'] ?? '';

    // Verifikasi password admin terlebih dahulu
    if (!cek_password_admin($conn, $admin_password)) {
        $_SESSION['pesan_error'] = "Password admin salah!";
        header('Location: produk.php');
        exit;
    }

    // Aksi untuk menghapus Produk Kopi
    if (isset($_POST['hapus_produk'], $_POST['id'])) {
        $id_produk = intval($_POST['id']);

        // 1. Ambil nama file gambar untuk dihapus dari server
        $stmt = $conn->prepare("SELECT gambar_url FROM produk WHERE id_produk = ?");
        $stmt->bind_param("i", $id_produk);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $gambar = $row['gambar_url'];
            $file_path = __DIR__ . '/../uploads/' . $gambar;
            if ($gambar && file_exists($file_path)) {
                unlink($file_path); // Hapus file gambar
            }
        }
        $stmt->close();

        // 2. Hapus data produk dari database
        $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
        $stmt->bind_param("i", $id_produk);
        if ($stmt->execute()) {
            $_SESSION['pesan_sukses'] = "Produk berhasil dihapus.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus produk.";
        }
        $stmt->close();
        
        header('Location: produk.php');
        exit;
    }

    // Aksi untuk menghapus Jenis Kopi
    elseif (isset($_POST['hapus_jenis_kopi'], $_POST['id_kopi'])) {
        $id_kopi = intval($_POST['id_kopi']);

        // 1. Ambil nama file gambar untuk dihapus
        $stmt = $conn->prepare("SELECT gambar_kopi FROM jeniskopi WHERE id_kopi = ?");
        $stmt->bind_param("i", $id_kopi);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $gambar = $row['gambar_kopi'];
            $file_path = __DIR__ . '/../uploads/' . $gambar;
            if ($gambar && file_exists($file_path)) {
                unlink($file_path); // Hapus file gambar
            }
        }
        $stmt->close();

        // 2. Hapus data jenis kopi dari database
        $stmt = $conn->prepare("DELETE FROM jeniskopi WHERE id_kopi = ?");
        $stmt->bind_param("i", $id_kopi);
        if ($stmt->execute()) {
            $_SESSION['pesan_sukses'] = "Jenis kopi berhasil dihapus.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus jenis kopi.";
        }
        $stmt->close();

        header('Location: produk.php');
        exit;
    }
}

// Jika akses langsung atau tidak ada aksi yang cocok, kembalikan ke halaman produk
$_SESSION['pesan_error'] = "Aksi tidak valid.";
header('Location: produk.php');
exit;
?>
