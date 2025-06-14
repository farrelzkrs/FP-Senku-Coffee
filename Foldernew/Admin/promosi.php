<?php
include '../koneksi.php';

$success = '';
$error = '';

// Proses tambah promosi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_promosi'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];
    $is_aktif = isset($_POST['is_aktif']) ? 1 : 0;
    $gambar_url = '';

    // Upload gambar jika ada
    if (isset($_FILES['gambar_url']) && $_FILES['gambar_url']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar_url']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $gambar_url = uniqid('promo_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar_url']['tmp_name'], "../uploads/" . $gambar_url);
        } else {
            $error = "Format gambar harus jpg, jpeg, png, atau gif!";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO promosi (judul, gambar_url, deskripsi, tanggal_mulai, tanggal_berakhir, is_aktif) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $judul, $gambar_url, $deskripsi, $tanggal_mulai, $tanggal_berakhir, $is_aktif);
        if ($stmt->execute()) {
            $success = "Promosi berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah promosi.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="StyleAdmin/promosistyle.css">
    <title>Promosi - Senku Coffee</title>
</head>

<body>
    <nav class="sidebar">
        <img src="../Resource/Senku kafe.png" alt="Logo Senku Coffee" class="navbar-logo">
        <div class="nav-profil-item dropdown w-100">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="sidebarProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../Resource/fotoadmin.jpg" alt="Profil" class="rounded-circle" width="47" height="47" style="object-fit: cover; border: 2px solid #28a745;">
                Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-profil" aria-labelledby="sidebarProfileDropdown">
                <li>
                    <a class="dropdown-item" href="../Bf Login/home.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        <a class="nav-link" href="dashboard.php">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        <a class="nav-link" href="datapengunjung.php">
            <i class="bi bi-people me-2"></i>Data Pengunjung
        </a>
        <a class="nav-link active" href="promosi.php">
            <i class="bi bi-megaphone me-2"></i>Promosi
        </a>
        <a class="nav-link" href="produk.php">
            <i class="bi bi-box-seam me-2"></i>Produk
        </a>
        <a class="nav-link" href="faq.php">
            <i class="bi bi-question-circle me-2"></i>FAQ
        </a>
        
    </nav>
    <div class="main-content">
        <!-- Konten utama dashboard di sini -->
        <div class="konten">
            <h2 class="mb-4">Tambah Promosi Baru</h2>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="mb-5">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Promosi</label>
                    <input type="text" class="form-control" id="judul" name="judul" required>
                </div>
                <div class="mb-3">
                    <label for="gambar_url" class="form-label">Gambar Promosi</label>
                    <input type="file" class="form-control" id="gambar_url" name="gambar_url" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir</label>
                    <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="is_aktif" name="is_aktif" checked>
                    <label class="form-check-label" for="is_aktif">Aktifkan Promosi</label>
                </div>
                <button type="submit" name="tambah_promosi" class="btn btn-success">Tambah Promosi</button>
            </form>
        </div>
        <div class="penutup">
            <footer class="text-center mb-2 mt-3">
                <hr>
                <small>
                    &copy; 2025 Senku Coffee &middot;
                    Jl. Kopi No. 123, Jakarta &middot;
                    <a href="mailto:info@senkucoffee.com" class="text-decoration-none">info@senkucoffee.com</a>
                </small>
            </footer>
        </div>
    </div>
</body>
</html>