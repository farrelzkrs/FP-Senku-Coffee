<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}
include '../koneksi.php';

$id_produk = intval($_GET['id'] ?? 0);
if (!$id_produk) {
    header("Location: produk.php?error=ID produk tidak valid");
    exit;
}

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk=?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

if (!$produk) {
    header("Location: produk.php?error=Produk tidak ditemukan");
    exit;
}

// Ambil data jenis kopi untuk dropdown
$jeniskopi = $conn->query("SELECT id_kopi, nama_jenis FROM jeniskopi ORDER BY nama_jenis ASC");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = trim($_POST['nama_produk']);
    $jenis_kopi_id = intval($_POST['jenis_kopi_id']);
    $harga = intval($_POST['harga']);
    $deskripsi = trim($_POST['deskripsi']);
    $gambar = $produk['gambar_url'];

    // Proses upload gambar baru jika ada
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $gambar_baru = uniqid('produk_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../uploads/' . $gambar_baru);
            // Hapus gambar lama jika ada
            if ($gambar && file_exists(__DIR__ . '/../uploads/' . $gambar)) {
                unlink(__DIR__ . '/../uploads/' . $gambar);
            }
            $gambar = $gambar_baru;
        } else {
            $error = "Format gambar harus jpg, jpeg, png, atau gif!";
        }
    }

    if ($nama_produk && $jenis_kopi_id && $harga && !$error) {
        $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, jenis_kopi_id=?, harga=?, deskripsi=?, gambar_url=? WHERE id_produk=?");
        $stmt->bind_param("siissi", $nama_produk, $jenis_kopi_id, $harga, $deskripsi, $gambar, $id_produk);
        $stmt->execute();
        $stmt->close();
        header("Location: produk.php?success=Produk berhasil diupdate");
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
    <title>Edit Produk Kopi - Senku Coffee</title>
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
            <h3 class="mb-0">Edit Produk Kopi</h3>
            <p class="mb-4">Ubah data produk kopi di bawah ini.</p>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" required value="<?= htmlspecialchars($produk['nama_produk']) ?>">
                <select name="jenis_kopi_id" class="form-control" required>
                    <option value="">Pilih Jenis Kopi</option>
                    <?php
                    mysqli_data_seek($jeniskopi, 0);
                    while($jk = $jeniskopi->fetch_assoc()): ?>
                        <option value="<?= $jk['id_kopi'] ?>" <?= $jk['id_kopi'] == $produk['jenis_kopi_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($jk['nama_jenis']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="harga" class="form-control" placeholder="Harga" required min="1" value="<?= $produk['harga'] ?>">
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Produk" rows="3" required><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
                <?php if ($produk['gambar_url']): ?>
                    <img src="../uploads/<?= htmlspecialchars($produk['gambar_url']) ?>" alt="Gambar Produk" class="img-preview">
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*">
                <button type="submit" class="btn btn-warning w-100 mb-2">Simpan Perubahan</button>
            </form>
        </div>
    </section>
</body>
</html>