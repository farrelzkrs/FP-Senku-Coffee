<?php
include '../koneksi.php';

$success = '';
$error = '';

// Fungsi TAMBAH
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
        $success = "Promosi berhasil ditambahkan!";
    }
}

// Fungsi EDIT
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
            $error = "Format gambar tidak valid.";
        }
    }

    if (!$error) {
        $params[] = $id;
        $types .= "i";
        $query = "UPDATE promosi SET judul=?, deskripsi=?, tanggal_mulai=?, tanggal_berakhir=?, is_aktif=?$gambar_sql WHERE id_promosi=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
        $success = "Promosi berhasil diperbarui!";
    }
}

// Fungsi HAPUS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_promosi'])) {
    $id = intval($_POST['id_promosi']);
    $inputPassword = $_POST['admin_password'];

    if ($inputPassword === $ADMIN_PASSWORD) {
        $stmt = $conn->prepare("DELETE FROM promosi WHERE id_promosi = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $success = "Promosi berhasil dihapus!";
    } else {
        $error = "Password admin salah. Gagal menghapus.";
    }
}
// Ambil data dari database
$promosi = $conn->query("SELECT * FROM promosi ORDER BY id_promosi DESC");

if (!$promosi) {
    die("Gagal mengambil data promosi: " . $conn->error);
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
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="sidebarProfileDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../Resource/fotoadmin.jpg" alt="Profil" class="rounded-circle" width="47" height="47"
                    style="object-fit: cover; border: 2px solid #28a745;">
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
    <div class="main-content p-4">
        <div class="konten">
            <h2>Kelola Promosi</h2>
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>
    
            <!-- Card Container dan Tiga Tombol di Kiri Atas -->
            <div class="card p-4 mb-4">
                <div class="d-flex mb-3">
                    <button class="btn btn-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#tambahModal">Tambah</button>
                </div>
    
                <!-- Grid Card Promosi -->
                <div class="row">
                    <?php while ($row = $promosi->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="../uploads/<?= htmlspecialchars($row['gambar_url']) ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <h5>
                                        <?= htmlspecialchars($row['judul']) ?>
                                    </h5>
                                    <p>
                                        <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
                                    </p>
                                    <p class="small text-muted">
                                        <?= $row['tanggal_mulai'] ?> –
                                        <?= $row['tanggal_berakhir'] ?><br>Status:
                                        <?= $row['is_aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                    </p>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $row['id_promosi'] ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusModal<?= $row['id_promosi'] ?>">Hapus</button>
                                </div>
                            </div>
                        </div>
    
                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal<?= $row['id_promosi'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_promosi" value="<?= $row['id_promosi'] ?>">
                                    <input type="hidden" name="edit_promosi" value="1">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Promosi</h5>
                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2"><label>Judul</label><input name="judul" class="form-control"
                                                    value="<?= htmlspecialchars($row['judul']) ?>"></div>
                                            <div class="mb-2"><label>Deskripsi</label><textarea name="deskripsi"
                                                    class="form-control"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                                            </div>
                                            <div class="mb-2"><label>Ganti Gambar</label><input type="file" name="gambar_url"
                                                    class="form-control"></div>
                                            <div class="mb-2"><label>Mulai</label><input type="date" name="tanggal_mulai"
                                                    class="form-control" value="<?= $row['tanggal_mulai'] ?>"></div>
                                            <div class="mb-2"><label>Akhir</label><input type="date" name="tanggal_berakhir"
                                                    class="form-control" value="<?= $row['tanggal_berakhir'] ?>"></div>
                                            <div class="form-check"><input type="checkbox" name="is_aktif"
                                                    class="form-check-input" <?= $row['is_aktif'] ? 'checked' : '' ?>><label
                                                    class="form-check-label">Aktifkan</label></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-primary" type="submit">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
    
                        <!-- Modal Hapus -->
                        <div class="modal fade" id="hapusModal<?= $row['id_promosi'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST">
                                    <input type="hidden" name="id_promosi" value="<?= $row['id_promosi'] ?>">
                                    <input type="hidden" name="hapus_promosi" value="1">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Masukkan password admin untuk menghapus:</p>
                                            <input type="password" name="admin_password" class="form-control"
                                                placeholder="Password admin" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-danger" type="submit">Hapus</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
    
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    
        <!-- Modal Tambah -->
        <div class="modal fade" id="tambahModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="tambah_promosi" value="1">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Promosi</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><label>Judul</label><input name="judul" class="form-control" required></div>
                            <div class="mb-2"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"
                                    required></textarea></div>
                            <div class="mb-2"><label>Gambar</label><input type="file" name="gambar_url" class="form-control"
                                    required></div>
                            <div class="mb-2"><label>Mulai</label><input type="date" name="tanggal_mulai"
                                    class="form-control" required></div>
                            <div class="mb-2"><label>Akhir</label><input type="date" name="tanggal_berakhir"
                                    class="form-control" required></div>
                            <div class="form-check"><input name="is_aktif" type="checkbox" class="form-check-input"
                                    checked><label class="form-check-label">Aktifkan</label></div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button class="btn btn-primary" type="submit">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="penutup">
            <!-- <footer class="text-center mb-2 mt-3">
                <hr>
                <small>
                    &copy; 2025 Senku Coffee &middot;
                    Jl. Kopi No. 123, Jakarta &middot;
                    <a href="mailto:info@senkucoffee.com" class="text-decoration-none">info@senkucoffee.com</a>
                </small>
            </footer> -->
            <footer class="text-center mt-4 mb-2 py-3"
                style="background:#343a40; color: #fff; border-top: 10px solid #006400;">
                &copy; 2025 Senku Coffee &middot;
                Jl. Kopi No. 123, Jakarta &middot;
                <a href="mailto:info@senkucoffee.com"
                class="link-warning link-underline-opacity-25 link-underline-opacity-100-hover">info@senkucoffee.com</a>
            </footer>
        </div>
    </div>
</body>

</html>