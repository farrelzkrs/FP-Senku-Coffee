<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../Bf Login/masuk.php');
    exit;
}
include '../koneksi.php';

// PAGINASI PRODUK
$per_page_produk = 15;
$page_produk = isset($_GET['page_produk']) ? max(1, intval($_GET['page_produk'])) : 1;
$offset_produk = ($page_produk - 1) * $per_page_produk;
$total_produk = $conn->query("SELECT COUNT(*) FROM produk")->fetch_row()[0];
$total_page_produk = ceil($total_produk / $per_page_produk);

$produk = $conn->query("SELECT p.*, j.nama_jenis FROM produk p LEFT JOIN jeniskopi j ON p.jenis_kopi_id = j.id_kopi ORDER BY p.id_produk DESC LIMIT $per_page_produk OFFSET $offset_produk");

// PAGINASI JENIS KOPI
$per_page_jeniskopi = 15;
$page_jeniskopi = isset($_GET['page_jeniskopi']) ? max(1, intval($_GET['page_jeniskopi'])) : 1;
$offset_jeniskopi = ($page_jeniskopi - 1) * $per_page_jeniskopi;
$total_jeniskopi = $conn->query("SELECT COUNT(*) FROM jeniskopi")->fetch_row()[0];
$total_page_jeniskopi = ceil($total_jeniskopi / $per_page_jeniskopi);

$jeniskopi = $conn->query("SELECT * FROM jeniskopi ORDER BY id_kopi DESC LIMIT $per_page_jeniskopi OFFSET $offset_jeniskopi");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Produk - Senku Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* CSS Anda sudah bagus, tidak perlu diubah. Saya hanya meringkasnya. */
        body { overflow-x: hidden; margin: 0; padding: 0; font-family: 'Trebuchet MS', Arial, sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 240px; background: burlywood; border-right: 8px solid #006400; box-shadow: 2px 0 8px rgba(0, 0, 0, 0.08); z-index: 1040; display: flex; flex-direction: column; align-items: center; padding-top: 18px; }
        .sidebar .navbar-logo { height: 65px; width: auto; margin-bottom: 18px; }
        .sidebar .nav-link { color: rgb(0, 100, 0); font-size: 15px; font-weight: bold; margin: 9px 0; border-radius: 5px; width: 90%; text-align: left; padding: 7px 16px; transition: background 0.2s, color 0.2s; display: flex; align-items: center; gap: 8px; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: beige; color: #006400 !important; }
        .sidebar .nav-profil-item { margin-top: 12px; margin-bottom: 30px; width: 90%; }
        .sidebar .nav-profil-item .nav-link { display: flex; align-items: center; background: #fffbe6; color: #006400; padding: 4px 15px; font-size: 15px; margin-top: 8px; }
        .sidebar .nav-profil-item img { width: 40px; height: 40px; margin-right: 8px; }
        .dropdown-menu-profil { min-width: 222px; background: #fffbe6; border-radius: 8px; border: 1px solid #e0cfa9; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08); font-size: 15px; margin-top: 8px; padding: 7px 10px; }
        .dropdown-menu-profil .dropdown-item { color: #006400; padding: 2px 18px; border-radius: 6px; transition: background 0.2s; display: flex; align-items: center; gap: 8px; font-weight: bold; font-size: 15px; }
        .dropdown-menu-profil .dropdown-item i { font-size: 1.1em; color: #006400; margin-right: 6px; vertical-align: middle; }
        .dropdown-menu-profil .dropdown-item:hover, .dropdown-menu-profil .dropdown-item:hover i { background: #f5e6c8; color: #4e2e0e; }
        .main-content { margin-left: 240px; padding: 40px 30px 0 30px; }
        @media (max-width: 991px) { .sidebar { width: 100vw; height: auto; flex-direction: row; padding: 10px 0; position: relative; } .main-content { margin-left: 0; padding: 80px 10px 0 10px; } }
    </style>
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
                <li><a class="dropdown-item" href="../Bf Login/keluar.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
        <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a class="nav-link" href="datapengunjung.php"><i class="bi bi-people me-2"></i>Data Pengunjung</a>
        <a class="nav-link" href="promosi.php"><i class="bi bi-megaphone me-2"></i>Promosi</a>
        <a class="nav-link active" href="produk.php"><i class="bi bi-box-seam me-2"></i>Produk</a>
        <a class="nav-link" href="faq.php"><i class="bi bi-question-circle me-2"></i>FAQ</a>
    </nav>
    <div class="main-content">
        
        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['pesan_sukses']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['pesan_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['pesan_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Daftar Produk Kopi</h4>
            <a href="tambah_produk.php" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Tambah Produk</a>
        </div>
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th><th>Nama Produk</th><th>Jenis Kopi</th><th>Harga</th><th>Deskripsi</th><th>Gambar</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1 + $offset_produk; while ($row = $produk->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($row['nama_jenis']) ?></td>
                        <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td>
                            <?php if ($row['gambar_url']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['gambar_url']) ?>" alt="Gambar Produk" style="width:60px; height:60px; object-fit:cover;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModalProduk<?= $row['id_produk'] ?>"><i class="bi bi-trash"></i> Hapus</button>

                            <div class="modal fade" id="hapusModalProduk<?= $row['id_produk'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="post" action="file_hapus_produk.php" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus Produk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Masukkan password admin untuk menghapus produk <b><?= htmlspecialchars($row['nama_produk']) ?></b>?</p>
                                            <input type="hidden" name="id" value="<?= $row['id_produk'] ?>">
                                            <input type="password" name="admin_password" class="form-control" placeholder="Password admin" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="hapus_produk" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php $produk->free(); ?>

        <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
            <h4 class="mb-0">Daftar Jenis Kopi</h4>
            <a href="tambah_jeniskopi.php" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Tambah Jenis Kopi</a>
        </div>
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th><th>Nama Jenis</th><th>Gambar</th><th>Deskripsi</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1 + $offset_jeniskopi; while ($row = $jeniskopi->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_jenis']) ?></td>
                        <td>
                            <?php if ($row['gambar_kopi']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['gambar_kopi']) ?>" alt="Gambar Jenis Kopi" style="width:60px; height:60px; object-fit:cover;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td>
                            <a href="edit_jeniskopi.php?id=<?= $row['id_kopi'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModalJenis<?= $row['id_kopi'] ?>"><i class="bi bi-trash"></i> Hapus</button>

                            <div class="modal fade" id="hapusModalJenis<?= $row['id_kopi'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="post" action="file_hapus_produk.php" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus Jenis Kopi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Masukkan password admin untuk menghapus jenis kopi <b><?= htmlspecialchars($row['nama_jenis']) ?></b>?</p>
                                            <input type="hidden" name="id_kopi" value="<?= $row['id_kopi'] ?>">
                                            <input type="password" name="admin_password" class="form-control" placeholder="Password admin" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="hapus_jenis_kopi" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php $jeniskopi->free(); ?>

        <?php if ($total_page_produk > 1): ?>
        <nav><ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_page_produk; $i++): ?>
                <li class="page-item <?= $i == $page_produk ? 'active' : '' ?>"><a class="page-link" href="?page_produk=<?= $i ?>&page_jeniskopi=<?= $page_jeniskopi ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul></nav>
        <?php endif; ?>

        <?php if ($total_page_jeniskopi > 1): ?>
        <nav><ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_page_jeniskopi; $i++): ?>
                <li class="page-item <?= $i == $page_jeniskopi ? 'active' : '' ?>"><a class="page-link" href="?page_produk=<?= $page_produk ?>&page_jeniskopi=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul></nav>
        <?php endif; ?>

        <div class="penutup">
            <footer class="text-center mb-2 mt-3">
                <hr>
                <small>&copy; 2025 Senku Coffee &middot; Jl. Kopi No. 123, Jakarta &middot; <a href="mailto:info@senkucoffee.com" class="text-decoration-none">info@senkucoffee.com</a></small>
            </footer>
        </div>
    </div>
</body>
</html>
