<?php
include '../koneksi.php';

// Proses hapus jika ada POST hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_users'], $_POST['admin_password'])) {
    session_start();
    $id_users = intval($_POST['id_users']);
    $admin_password = $_POST['admin_password'];

    // Cek password admin (misal: password statis, atau cek di DB)
    $hapus_ok = false;
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $admin_password === 'admin123') {
        $hapus_ok = true;
    }

    if ($hapus_ok && $id_users > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id_users = ?");
        $stmt->bind_param("i", $id_users);
        $stmt->execute();
        $stmt->close();
        // Pesan sukses menampilkan username yang dihapus
        $pesan_sukses = "Akun " . htmlspecialchars($_POST['username_pengunjung'] ?? '') . " berhasil dihapus.";
    } else {
        $pesan_error = "Password admin salah atau data tidak valid.";
    }
}

// PAGINASI
$per_page = 15;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;

// Search
$search = trim($_GET['q'] ?? '');
$where = '';
$params = [];
$types = '';

if ($search !== '') {
    $where = "WHERE username LIKE ? OR email LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types = 'ss';
}

// Hitung total data
if ($where) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users $where");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result_count = $stmt->get_result();
    $total_row = $result_count->fetch_assoc();
    $total_data = $total_row['total'];
    $stmt->close();
} else {
    $total_result = $conn->query("SELECT COUNT(*) as total FROM users");
    $total_row = $total_result->fetch_assoc();
    $total_data = $total_row['total'];
    $total_result->free();
}
$total_page = ceil($total_data / $per_page);

// Query data sesuai halaman
if ($where) {
    $stmt = $conn->prepare("SELECT id_users, username, email, created_at FROM users $where ORDER BY id_users DESC LIMIT $per_page OFFSET $offset");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $query = "SELECT id_users, username, email, created_at FROM users ORDER BY id_users DESC LIMIT $per_page OFFSET $offset";
    $result = $conn->query($query);
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
    <link rel="stylesheet" href="StyleAdmin/datapengunjungstyle.css">
    <title>Data Pengunjung - Senku Coffee</title>
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
        <a class="nav-link active" href="datapengunjung.php">
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
        <div classs="konten">
            <h4 class="mb-3">Data Pengunjung</h4>
            <?php if (!empty($pesan_sukses)): ?>
                <div class="alert alert-success text-center pesan-flash"><?= $pesan_sukses ?></div>
            <?php endif; ?>
            <?php if (!empty($pesan_error)): ?>
                <div class="alert alert-danger text-center pesan-flash"><?= htmlspecialchars($pesan_error) ?></div>
            <?php endif; ?>
            <form method="get" class="mb-3 d-flex" style="max-width:400px;">
                <input type="text" name="q" class="form-control me-2" placeholder="Cari username atau email..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit" class="btn btn-success">Cari</button>
                <?php if (!empty($_GET['q'])): ?>
                    <a href="datapengunjung.php" class="btn btn-outline-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <a href="editakun.php?id=<?= $row['id_users'] ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id_users'] ?>">
                                <i class="bi bi-trash"></i> Hapus
                            </button>

                            <!-- Modal Konfirmasi Hapus Pengunjung -->
                            <div class="modal fade" id="hapusModal<?= $row['id_users'] ?>" tabindex="-1" aria-labelledby="hapusModalLabel<?= $row['id_users'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="post" action="" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="hapusModalLabel<?= $row['id_users'] ?>">Konfirmasi Hapus Akun</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Masukkan password admin untuk menghapus akun <b><?= htmlspecialchars($row['username']) ?></b>?</p>
                                        <input type="hidden" name="id_users" value="<?= $row['id_users'] ?>">
                                        <input type="hidden" name="username_pengunjung" value="<?= htmlspecialchars($row['username']) ?>">
                                        <input type="password" name="admin_password" class="form-control" placeholder="Password admin" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <?php $result->free(); ?>
            <?php if ($total_page > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center mt-3">
                        <?php for ($i = 1; $i <= $total_page; $i++): ?>
                            <li class="page-item<?= $i == $page ? ' active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
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
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-hapus-akun').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('hapus-id-users').value = this.dataset.id;
            document.getElementById('hapus-username').textContent = this.dataset.username;
            document.getElementById('hapus-username-hidden').value = this.dataset.username; // Tambahkan baris ini
            var modal = new bootstrap.Modal(document.getElementById('modalHapusAkun'));
            modal.show();
        });
    });
    setTimeout(function() {
        document.querySelectorAll('.pesan-flash').forEach(function(el) {
            el.style.transition = "opacity 0.5s";
            el.style.opacity = 0;
            setTimeout(function() { el.remove(); }, 500);
        });
    }, 2500);
});
</script>
</body>
</html>