<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}
include '../koneksi.php';

$id_kopi = intval($_GET['id'] ?? 0);
if (!$id_kopi) {
    header("Location: produk.php?error=ID jenis kopi tidak valid");
    exit;
}

// Ambil data jenis kopi
$stmt = $conn->prepare("SELECT * FROM jeniskopi WHERE id_kopi=?");
$stmt->bind_param("i", $id_kopi);
$stmt->execute();
$result = $stmt->get_result();
$jenis = $result->fetch_assoc();
$stmt->close();

if (!$jenis) {
    header("Location: produk.php?error=Jenis kopi tidak ditemukan");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_jenis = trim($_POST['nama_jenis']);
    $deskripsi = trim($_POST['deskripsi']);
    $gambar_kopi = $jenis['gambar_kopi'];

    // Proses upload gambar baru jika ada
    if (isset($_FILES['gambar_kopi']) && $_FILES['gambar_kopi']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar_kopi']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $gambar_baru = uniqid('jeniskopi_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar_kopi']['tmp_name'], __DIR__ . '/../uploads/' . $gambar_baru);
            // Hapus gambar lama jika ada
            if ($gambar_kopi && file_exists(__DIR__ . '../uploads/' . $gambar_kopi)) {
                unlink(__DIR__ . '../uploads/' . $gambar_kopi);
            }
            $gambar_kopi = $gambar_baru;
        } else {
            $error = "Format gambar harus jpg, jpeg, png, atau gif!";
        }
    }

    if ($nama_jenis && $deskripsi && !$error) {
        $stmt = $conn->prepare("UPDATE jeniskopi SET nama_jenis=?, gambar_kopi=?, deskripsi=? WHERE id_kopi=?");
        $stmt->bind_param("sssi", $nama_jenis, $gambar_kopi, $deskripsi, $id_kopi);
        $stmt->execute();
        $stmt->close();
        header("Location: produk.php?success=Jenis kopi berhasil diupdate");
        exit;
    } elseif (!$error) {
        $error = "Semua kolom wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Jenis Kopi - Senku Coffee</title>
    <style>
        body {
            background: #f7f7f7;
        }

        .header .p-3 {
            background: burlywood;
            color: #fff;
            text-align: left;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 10px solid #006400;
        }

        .login-container {
            max-width: 400px;
            margin: 20px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 20px 30px 20px 30px;
            text-align: center;
        }

        .header .btn-outline-success {
            background-color: beige;
            border: 2px solid #28a745;
        }

        .btn-outline-success:hover {
            background-color: #28a745;
            color: white;
            border-color: #218838;
        }

        .brand-logo {
            width: 100px;
            margin-bottom: 18px;
        }

        .form-control {
            margin-bottom: 18px;
        }

        .register-section {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 18px;
        }

        .img-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="header">
        <header class="p-3">
            <button class="btn btn-outline-success" onclick="history.back()">
                &larr; Kembali
            </button>
        </header>
    </div>
    <section id="content" class="content-section">
        <div class="login-container">
            <img src="../Resource/Senku kafe.png" alt="Brand Logo" class="brand-logo">
            <h3 class="mb-0">Edit Jenis Kopi</h3>
            <p class="mb-4">Ubah data jenis kopi di bawah ini.</p>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="nama_jenis" class="form-control" placeholder="Nama Jenis Kopi" required value="<?= htmlspecialchars($jenis['nama_jenis']) ?>">
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Jenis Kopi" rows="3" required><?= htmlspecialchars($jenis['deskripsi']) ?></textarea>
                <?php
                $gambar = $jenis['gambar_kopi'] ? '../uploads/' . $jenis['gambar_kopi'] : '../Resource/fotoprofil2.jpg';
                ?>
                <img src="<?= htmlspecialchars($gambar) . '?v=' . time() ?>" alt="Gambar Jenis Kopi" style="width:100px; height:100px; object-fit:cover;">
                <input type="file" name="gambar_kopi" class="form-control" accept="image/*">
                <button type="submit" class="btn btn-warning w-100 mb-2">Simpan Perubahan</button>
            </form>
        </div>
    </section>
</body>
</html>