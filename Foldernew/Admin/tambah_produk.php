<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}
include '../koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_produk']);
    $jenis_kopi_id = intval($_POST['jenis_kopi_id']);
    $harga = intval($_POST['harga']);
    $deskripsi = trim($_POST['deskripsi']);

    // Proses upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $gambar = uniqid('produk_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../uploads/' . $gambar);
        } else {
            $error = "Format gambar harus jpg, jpeg, png, atau gif!";
        }
    }

    if ($nama && $jenis_kopi_id && $harga && !$error) {
        $stmt = $conn->prepare("INSERT INTO produk (nama_produk, jenis_kopi_id, harga, deskripsi, gambar_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiss", $nama, $jenis_kopi_id, $harga, $deskripsi, $gambar);
        $stmt->execute();
        $stmt->close();
        header("Location: produk.php?success=Produk berhasil ditambah");
        exit;
    } elseif (!$error) {
        $error = "Semua kolom wajib diisi!";
    }
}

// Ambil data jenis kopi untuk dropdown
$jeniskopi = $conn->query("SELECT id_kopi, nama_jenis FROM jeniskopi ORDER BY nama_jenis ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Produk - Senku Coffee</title>
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
            <h3 class="mb-0">Tambah Produk Kopi</h3>
            <p class="mb-4">Masukkan data produk kopi baru di bawah ini.</p>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" required>
                <select name="jenis_kopi_id" class="form-control" required>
                    <option value="">Pilih Jenis Kopi</option>
                    <?php while($jk = $jeniskopi->fetch_assoc()): ?>
                        <option value="<?= $jk['id_kopi'] ?>"><?= htmlspecialchars($jk['nama_jenis']) ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="harga" class="form-control" placeholder="Harga (angka saja)" required min="1">
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Produk" rows="3"></textarea>
                <input type="file" name="gambar" class="form-control" accept="image/*" required>
                <button type="submit" class="btn btn-success w-100 mb-2">Tambah Produk</button>
            </form>
        </div>
    </section>
</body>
</html>