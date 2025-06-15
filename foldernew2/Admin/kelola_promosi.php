<?php
include '../koneksi.php';
$ADMIN_PASSWORD = 'admin123';

$success = '';
$error = '';

// Tambah Promosi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_promosi'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $is_aktif = isset($_POST['is_aktif']) ? 1 : 0;
    $gambar_url = '';

    if (isset($_FILES['gambar_url']) && $_FILES['gambar_url']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar_url']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $gambar_url = uniqid('promo_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar_url']['tmp_name'], "../uploads/" . $gambar_url);
        } else {
            $error = "Format gambar tidak valid.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO promosi (judul, gambar_url, deskripsi, tanggal_mulai, tanggal_berakhir, is_aktif) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $judul, $gambar_url, $deskripsi, $tanggal_mulai, $tanggal_berakhir, $is_aktif);
        $stmt->execute();
        $stmt->close();
        header("Location: promosi.php?status=added");
        exit;
    }
}

// Edit Promosi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_promosi'])) {
    $id = intval($_POST['id_promosi']);
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $is_aktif = isset($_POST['is_aktif']) ? 1 : 0;

    $gambar_sql = "";
    $params = [$judul, $deskripsi, $tanggal_mulai, $tanggal_berakhir, $is_aktif];
    $types = "ssssi";

    if (isset($_FILES['gambar_url']) && $_FILES['gambar_url']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar_url']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $gambar_url = uniqid('promo_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar_url']['tmp_name'], "../uploads/" . $gambar_url);
            $gambar_sql = ", gambar_url = ?";
            $params[] = $gambar_url;
            $types .= "s";
        } else {
            header("Location: promosi.php?status=formatgambar");
            exit;
        }
    }

    $params[] = $id;
    $types .= "i";
    $query = "UPDATE promosi SET judul=?, deskripsi=?, tanggal_mulai=?, tanggal_berakhir=?, is_aktif=?$gambar_sql WHERE id_promosi=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();
    header("Location: promosi.php?status=updated");
    exit;
}

// Hapus Promosi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_promosi'])) {
    $id = intval($_POST['id_promosi']);
    $inputPassword = $_POST['admin_password'];

    if ($inputPassword === $ADMIN_PASSWORD) {
        $stmt = $conn->prepare("DELETE FROM promosi WHERE id_promosi = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: promosi.php?status=deleted");
        exit;
    } else {
        header("Location: promosi.php?status=wrongpassword");
        exit;
    }
}

header("Location: promosi.php");
exit;
