<?php
include '../koneksi.php';
// Mulai sesi
session_start();
// Cek apakah admin sudah login
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}

// Tambah Poster
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['judul_post']) && !isset($_POST['edit_poster_id'])) {
    $judul = $_POST['judul_post'];
    $caption = $_POST['caption'];
    $is_aktif = isset($_POST['is_aktif']) ? 1 : 0;
    $url_post = '';
    if (isset($_FILES['url_post']) && $_FILES['url_post']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['url_post']['name'], PATHINFO_EXTENSION));
        $url_post = uniqid('poster_') . '.' . $ext;
        move_uploaded_file($_FILES['url_post']['tmp_name'], "../uploads/" . $url_post);
    }
    $stmt = $conn->prepare("INSERT INTO poster (judul_post, url_post, caption, is_aktif) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $judul, $url_post, $caption, $is_aktif);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Edit Poster
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_poster_id'])) {
    $id = $_POST['edit_poster_id'];
    $judul = $_POST['judul_post'];
    $caption = $_POST['caption'];
    $is_aktif = isset($_POST['is_aktif']) ? 1 : 0;
    $url_post = '';
    if (isset($_FILES['url_post']) && $_FILES['url_post']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['url_post']['name'], PATHINFO_EXTENSION));
        $url_post = uniqid('poster_') . '.' . $ext;
        move_uploaded_file($_FILES['url_post']['tmp_name'], "../uploads/" . $url_post);
        $stmt = $conn->prepare("UPDATE poster SET judul_post=?, url_post=?, caption=?, is_aktif=? WHERE id_poster=?");
        $stmt->bind_param("sssii", $judul, $url_post, $caption, $is_aktif, $id);
    } else {
        $stmt = $conn->prepare("UPDATE poster SET judul_post=?, caption=?, is_aktif=? WHERE id_poster=?");
        $stmt->bind_param("ssii", $judul, $caption, $is_aktif, $id);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Hapus Poster
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_poster_id'])) {
    $id = $_POST['hapus_poster_id'];
    $stmt = $conn->prepare("DELETE FROM poster WHERE id_poster=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Toggle Aktif/Nonaktif Poster
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_aktif_id'])) {
    $id = $_POST['toggle_aktif_id'];
    $current = $_POST['current_status'];
    if ($current == 0) {
        // Hitung poster aktif
        $cek = $conn->query("SELECT COUNT(*) as total FROM poster WHERE is_aktif=1");
        $row = $cek->fetch_assoc();
        if ($row['total'] >= 3) {
            echo "<script>alert('Maksimal hanya 3 poster yang bisa aktif!');window.location='dashboard.php';</script>";
            exit;
        }
    }
    $new_status = $current ? 0 : 1;
    $stmt = $conn->prepare("UPDATE poster SET is_aktif=? WHERE id_poster=?");
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Toggle Aktif/Nonaktif Produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_aktif_produk_id'])) {
    $id = $_POST['toggle_aktif_produk_id'];
    $current = $_POST['current_status'];
    $new_status = $current ? 0 : 1;
    $stmt = $conn->prepare("UPDATE produk SET is_aktif=? WHERE id_produk=?");
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Toggle Aktif/Nonaktif Promosi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_aktif_promosi_id'])) {
    $id = $_POST['toggle_aktif_promosi_id'];
    $current = $_POST['current_status'];
    $new_status = $current ? 0 : 1;
    $stmt = $conn->prepare("UPDATE promosi SET is_aktif=? WHERE id_promosi=?");
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Set Produk sebagai Populer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_populer_id'])) {
    $id_produk = $_POST['set_populer_id'];
    // Cek apakah produk sudah ada di menu populer
    $cek = $conn->query("SELECT * FROM produk_populer WHERE produk_id='$id_produk'");
    if ($cek->num_rows == 0) {
        // Tambahkan produk ke menu populer
        $stmt = $conn->prepare("INSERT INTO produk_populer (produk_id) VALUES (?)");
        $stmt->bind_param("i", $id_produk);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php");
    exit;
}

// Set Produk jadi Populer (aktif)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_populer_id'])) {
    $id = $_POST['set_populer_id'];
    $stmt = $conn->prepare("UPDATE produk SET is_aktif=1 WHERE id_produk=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Set Promosi jadi Aktif
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_promosi_aktif_id'])) {
    $id = $_POST['set_promosi_aktif_id'];
    $stmt = $conn->prepare("UPDATE promosi SET is_aktif=1 WHERE id_promosi=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Tambah Galeri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['judul_foto']) && !isset($_POST['edit_galeri_id'])) {
    $judul = $_POST['judul_foto'];
    $deskripsi = $_POST['deskripsi_foto'];
    $foto_url = '';
    if (isset($_FILES['foto_url']) && $_FILES['foto_url']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto_url']['name'], PATHINFO_EXTENSION));
        $foto_url = uniqid('galeri_') . '.' . $ext;
        move_uploaded_file($_FILES['foto_url']['tmp_name'], "../uploads/" . $foto_url);
    }
    $stmt = $conn->prepare("INSERT INTO galeri (judul_foto, deskripsi_foto, foto_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $judul, $deskripsi, $foto_url);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Edit Galeri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_galeri_id'])) {
    $id = $_POST['edit_galeri_id'];
    $judul = $_POST['judul_foto'];
    $deskripsi = $_POST['deskripsi_foto'];
    $is_aktif = isset($_POST['is_aktif']) ? 1 : 0;
    if (isset($_FILES['foto_url']) && $_FILES['foto_url']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto_url']['name'], PATHINFO_EXTENSION));
        $foto_url = uniqid('galeri_') . '.' . $ext;
        move_uploaded_file($_FILES['foto_url']['tmp_name'], "../uploads/" . $foto_url);
        $stmt = $conn->prepare("UPDATE galeri SET judul_foto=?, deskripsi_foto=?, foto_url=?, is_aktif=? WHERE id_galeri=?");
        $stmt->bind_param("sssii", $judul, $deskripsi, $foto_url, $is_aktif, $id);
    } else {
        $stmt = $conn->prepare("UPDATE galeri SET judul_foto=?, deskripsi_foto=?, is_aktif=? WHERE id_galeri=?");
        $stmt->bind_param("ssii", $judul, $deskripsi, $is_aktif, $id);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Hapus Galeri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_galeri_id'])) {
    $id = $_POST['hapus_galeri_id'];
    $stmt = $conn->prepare("DELETE FROM galeri WHERE id_galeri=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Toggle Aktif/Nonaktif Galeri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_aktif_galeri_id'])) {
    $id = $_POST['toggle_aktif_galeri_id'];
    $current = $_POST['current_status'];
    $new_status = $current ? 0 : 1;
    $stmt = $conn->prepare("UPDATE galeri SET is_aktif=? WHERE id_galeri=?");
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
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
    <link rel="stylesheet" href="StyleAdmin/dashboardstyle.css">
    <title>Dashboard - Senku Coffee</title>
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
        <a class="nav-link active" href="dashboard.php">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        <a class="nav-link" href="datapengunjung.php">
            <i class="bi bi-people me-2"></i>Data Pengunjung
        </a>
        <a class="nav-link" href="promosi.php">
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
            <h2 class="mb-4">Dashboard Admin Senku Coffee</h2>

            <!-- Poster Welcome -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-images me-2"></i>Poster Welcome</span>
                    <!-- Tombol Tambah Poster -->
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPoster">
                        <i class="bi bi-plus-circle"></i> Tambah Poster
                    </button>
                </div>
                <div class="card-body">
                    <?php
                    $poster = $conn->query("SELECT * FROM poster ORDER BY id_poster DESC LIMIT 3");
                    if ($poster->num_rows > 0): ?>
                        <div class="row">
                            <?php while($row = $poster->fetch_assoc()): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="../uploads/<?= htmlspecialchars($row['url_post']) ?>" class="card-img-top" style="height:180px;object-fit:cover;" alt="<?= htmlspecialchars($row['judul_post']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($row['judul_post']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars(mb_strimwidth($row['caption'],0,60,'...')) ?></p>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between">
                                        <!-- Tombol Edit Poster -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditPoster<?= $row['id_poster'] ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <!-- Tombol Hapus Poster -->
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus poster ini? Tindakan ini tidak dapat dibatalkan!')">
                                            <input type="hidden" name="hapus_poster_id" value="<?= $row['id_poster'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="toggle_aktif_id" value="<?= $row['id_poster'] ?>">
                                            <input type="hidden" name="current_status" value="<?= $row['is_aktif'] ?>">
                                            <?php if ($row['is_aktif']): ?>
                                                <button type="submit" class="btn btn-secondary btn-sm" title="Nonaktifkan Poster">
                                                    <i class="bi bi-eye-slash"></i> Nonaktifkan
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Poster">
                                                    <i class="bi bi-eye"></i> Aktifkan
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit Poster -->
                            <div class="modal fade" id="modalEditPoster<?= $row['id_poster'] ?>" tabindex="-1" aria-labelledby="editPosterLabel<?= $row['id_poster'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="edit_poster_id" value="<?= $row['id_poster'] ?>">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editPosterLabel<?= $row['id_poster'] ?>">Edit Poster</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Judul</label>
                                                    <input type="text" class="form-control" name="judul_post" value="<?= htmlspecialchars($row['judul_post']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Caption</label>
                                                    <textarea class="form-control" name="caption" required><?= htmlspecialchars($row['caption']) ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ganti Gambar (opsional)</label>
                                                    <input type="file" class="form-control" name="url_post">
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_aktif" id="isAktifEdit<?= $row['id_poster'] ?>" <?= $row['is_aktif'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="isAktifEdit<?= $row['id_poster'] ?>">Aktif</label>
                                                </div>  
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Belum ada poster.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Menu Populer (Produk) -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-cup-hot me-2"></i>Menu Populer</span>
                    <div>
                        <a href="produk.php" class="btn btn-success btn-sm me-2"><i class="bi bi-plus-circle"></i> Kelola Produk</a>
                        <!-- Dropdown Tambah ke Populer -->
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-star"></i> Tambah ke Populer
                        </button>
                        <ul class="dropdown-menu">
                            <?php
                            $all_produk = $conn->query("SELECT id_produk, nama_produk FROM produk WHERE is_aktif=1 ORDER BY nama_produk");
                            while($p = $all_produk->fetch_assoc()):
                            ?>
                            <li>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="set_populer_id" value="<?= $p['id_produk'] ?>">
                                    <button type="submit" class="dropdown-item"><?= htmlspecialchars($p['nama_produk']) ?></button>
                                </form>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $produk = $conn->query("SELECT p.*, j.nama_jenis FROM produk p LEFT JOIN jeniskopi j ON p.jenis_kopi_id = j.id_kopi ORDER BY p.id_produk DESC LIMIT 3");
                    if ($produk->num_rows > 0): ?>
                        <div class="row">
                            <?php while($row = $produk->fetch_assoc()): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="../uploads/<?= htmlspecialchars($row['gambar_url']) ?>" class="card-img-top" style="height:180px;object-fit:cover;" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($row['nama_jenis']) ?></span>
                                        <p class="card-text"><?= htmlspecialchars(mb_strimwidth($row['deskripsi'],0,60,'...')) ?></p>
                                        <div class="fw-bold mt-2">Rp <?= number_format($row['harga'],0,',','.') ?></div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between">
                                        <a href="produk_edit.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="toggle_aktif_produk_id" value="<?= $row['id_produk'] ?>">
                                            <input type="hidden" name="current_status" value="<?= $row['is_aktif'] ?>">
                                            <?php if ($row['is_aktif']): ?>
                                                <button type="submit" class="btn btn-secondary btn-sm" title="Nonaktifkan Produk">
                                                    <i class="bi bi-eye-slash"></i> Nonaktifkan
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Produk">
                                                    <i class="bi bi-eye"></i> Aktifkan
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Belum ada produk.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Promosi -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-megaphone me-2"></i>Promosi</span>
                    <div>
                        <a href="promosi.php" class="btn btn-success btn-sm me-2"><i class="bi bi-plus-circle"></i> Kelola Promosi</a>
                        <!-- Dropdown Tambah ke Promosi -->
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-plus"></i> Tambah ke Promosi
                        </button>
                        <ul class="dropdown-menu">
                            <?php
                            $all_promosi = $conn->query("SELECT id_promosi, judul FROM promosi WHERE is_aktif=0 ORDER BY judul");
                            while($p = $all_promosi->fetch_assoc()):
                            ?>
                            <li>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="set_promosi_aktif_id" value="<?= $p['id_promosi'] ?>">
                                    <button type="submit" class="dropdown-item"><?= htmlspecialchars($p['judul']) ?></button>
                                </form>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $promosi = $conn->query("SELECT * FROM promosi ORDER BY tanggal_mulai DESC LIMIT 3");
                    if ($promosi->num_rows > 0): ?>
                        <div class="row">
                            <?php while($row = $promosi->fetch_assoc()): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="../uploads/<?= htmlspecialchars($row['gambar_url']) ?>" class="card-img-top" style="height:180px;object-fit:cover;" alt="<?= htmlspecialchars($row['judul']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars(mb_strimwidth($row['deskripsi'],0,60,'...')) ?></p>
                                        <small class="text-muted"><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($row['tanggal_berakhir'])) ?></small>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between">
                                        <a href="promosi_edit.php?id=<?= $row['id_promosi'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="hapus_promosi_id" value="<?= $row['id_promosi'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="toggle_aktif_promosi_id" value="<?= $row['id_promosi'] ?>">
                                            <input type="hidden" name="current_status" value="<?= $row['is_aktif'] ?>">
                                            <?php if ($row['is_aktif']): ?>
                                                <button type="submit" class="btn btn-secondary btn-sm" title="Nonaktifkan Promosi">
                                                    <i class="bi bi-eye-slash"></i> Nonaktifkan
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Promosi">
                                                    <i class="bi bi-eye"></i> Aktifkan
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Belum ada promosi.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="galeri">
                <!-- Galeri -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-images me-2"></i>Galeri</span>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahGaleri">
                            <i class="bi bi-plus-circle"></i> Tambah Foto
                        </button>
                    </div>
                    <div class="card-body">
                        <?php
                        $galeri = $conn->query("SELECT * FROM galeri ORDER BY created_at DESC");
                        if ($galeri->num_rows > 0): ?>
                            <div class="row">
                                <?php while($g = $galeri->fetch_assoc()): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <img src="../uploads/<?= htmlspecialchars($g['foto_url']) ?>" class="card-img-top" style="height:180px;object-fit:cover;" alt="<?= htmlspecialchars($g['judul_foto']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($g['judul_foto']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($g['deskripsi_foto']) ?></p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between">
                                            <!-- Edit -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditGaleri<?= $g['id_galeri'] ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <!-- Hapus -->
                                            <form method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                                <input type="hidden" name="hapus_galeri_id" value="<?= $g['id_galeri'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                            <!-- Aktif/Nonaktif -->
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="toggle_aktif_galeri_id" value="<?= $g['id_galeri'] ?>">
                                                <input type="hidden" name="current_status" value="<?= $g['is_aktif'] ?>">
                                                <?php if ($g['is_aktif']): ?>
                                                    <button type="submit" class="btn btn-secondary btn-sm" title="Nonaktifkan Foto">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Foto">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit Galeri -->
                                <div class="modal fade" id="modalEditGaleri<?= $g['id_galeri'] ?>" tabindex="-1" aria-labelledby="editGaleriLabel<?= $g['id_galeri'] ?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <form method="post" enctype="multipart/form-data">
                                      <input type="hidden" name="edit_galeri_id" value="<?= $g['id_galeri'] ?>">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="editGaleriLabel<?= $g['id_galeri'] ?>">Edit Foto Galeri</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="mb-3">
                                            <label class="form-label">Judul Foto</label>
                                            <input type="text" class="form-control" name="judul_foto" value="<?= htmlspecialchars($g['judul_foto']) ?>" required>
                                          </div>
                                          <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea class="form-control" name="deskripsi_foto" required><?= htmlspecialchars($g['deskripsi_foto']) ?></textarea>
                                          </div>
                                          <div class="mb-3">
                                            <label class="form-label">Ganti Foto (opsional)</label>
                                            <input type="file" class="form-control" name="foto_url">
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_aktif" id="isAktifEditGaleri<?= $g['id_galeri'] ?>" <?= $g['is_aktif'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="isAktifEditGaleri<?= $g['id_galeri'] ?>">Aktif</label>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Belum ada foto galeri.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Modal Tambah Galeri -->
                <div class="modal fade" id="modalTambahGaleri" tabindex="-1" aria-labelledby="tambahGaleriLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <form method="post" enctype="multipart/form-data">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="tambahGaleriLabel">Tambah Foto Galeri</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label">Judul Foto</label>
                            <input type="text" class="form-control" name="judul_foto" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi_foto" required></textarea>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" class="form-control" name="foto_url" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Tambah Foto</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
            </div>
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

    <!-- Modal Tambah Poster -->
    <div class="modal fade" id="modalTambahPoster" tabindex="-1" aria-labelledby="tambahPosterLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="tambahPosterLabel">Tambah Poster</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="judul_post" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Caption</label>
                <textarea class="form-control" name="caption" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" class="form-control" name="url_post" required>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_aktif" id="isAktifTambah" checked>
                <label class="form-check-label" for="isAktifTambah">Aktif</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Tambah Poster</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</body>
</html>