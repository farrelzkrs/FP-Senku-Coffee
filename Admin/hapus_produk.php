<?php
// filepath: c:\laragon\www\FP Pemweb\Admin\file_hapus_produk.php
session_start(); // Sesi HARUS dimulai di sini, SEBELUM include koneksi
include '../koneksi.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}

// Fungsi verifikasi password admin (kembali ke gaya asli Anda yang sudah benar)
function cek_password_admin($conn, $password)
{
    if (!isset($_SESSION['admin_username'])) {
        return false;
    }
    $stmt = $conn->prepare("SELECT password FROM admin WHERE username=?");
    $stmt->bind_param("s", $_SESSION['admin_username']);
    $stmt->execute();
    $hash = null;
    $stmt->bind_result($hash);
    $found = $stmt->fetch();
    $stmt->close();
    return $found && $hash && password_verify($password, $hash);
}

// Hanya proses jika metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan password dari form ada
    if (!isset($_POST['admin_password'])) {
        $_SESSION['pesan_error'] = "Password admin tidak dimasukkan.";
        header('Location: produk.php');
        exit;
    }
    $admin_password = $_POST['admin_password'];

    // Verifikasi password admin. Jika salah, langsung hentikan.
    if (!cek_password_admin($conn, $admin_password)) {
        $_SESSION['pesan_error'] = "Password admin salah!";
        header('Location: produk.php');
        exit;
    }

    // Blok LOGIKA HAPUS PRODUK
    // Cek apakah ini aksi untuk menghapus produk (berdasarkan nama tombol submit)
    if (isset($_POST['hapus_produk']) && isset($_POST['id'])) {
        $id = intval($_POST['id']);

        // Hapus gambar produk jika ada
        $stmt = $conn->prepare("SELECT gambar_url FROM produk WHERE id_produk=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($gambar);
        if ($stmt->fetch()) {
            $file_path = __DIR__ . '/../uploads/' . $gambar;
            if ($gambar && file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $stmt->close();

        // Hapus produk dari database
        $del = $conn->prepare("DELETE FROM produk WHERE id_produk=?");
        $del->bind_param("i", $id);
        if ($del->execute()) {
            $_SESSION['pesan_sukses'] = "Produk berhasil dihapus.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus produk dari database.";
        }
        $del->close();

        header('Location: produk.php');
        exit;
    }

    // Blok LOGIKA HAPUS JENIS KOPI
    // Cek apakah ini aksi untuk menghapus jenis kopi
    elseif (isset($_POST['hapus_jenis_kopi']) && isset($_POST['id_kopi'])) {
        $id_kopi = intval($_POST['id_kopi']);

        // Hapus gambar jenis kopi jika ada
        $stmt = $conn->prepare("SELECT gambar_kopi FROM jeniskopi WHERE id_kopi=?");
        $stmt->bind_param("i", $id_kopi);
        $stmt->execute();
        $stmt->bind_result($gambar);
        if ($stmt->fetch()) {
            $file_path = __DIR__ . '/../uploads/' . $gambar;
            if ($gambar && file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $stmt->close();

        // Hapus jenis kopi dari database
        $del = $conn->prepare("DELETE FROM jeniskopi WHERE id_kopi=?");
        $del->bind_param("i", $id_kopi);
        if ($del->execute()) {
            $_SESSION['pesan_sukses'] = "Jenis kopi berhasil dihapus.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus jenis kopi dari database.";
        }
        $del->close();

        header('Location: produk.php');
        exit;
    }
}

// Jika akses langsung (GET) atau aksi tidak cocok, kembalikan ke halaman produk
header('Location: produk.php');
exit;
?>
