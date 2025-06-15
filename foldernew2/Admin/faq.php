<?php
session_start();
include '../koneksi.php';

// Ambil semua permintaan user yang pending
$q = $conn->query("SELECT n.id_notif, n.pesan, n.tipe, n.status, u.username, u.email 
    FROM notifpesan n JOIN users u ON n.user_id = u.id_users 
    WHERE n.status = 'pending' ORDER BY n.id_notif DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_notif'])) {
    $id = intval($_POST['id_notif']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';
    $conn->query("UPDATE notifpesan SET status='$action' WHERE id_notif=$id");
    header("Location: faq.php");
    exit;
}

$query = "SELECT u.deskripsi AS pertanyaan, u.jawaban_admin AS jawaban 
        FROM ulasan u WHERE u.is_approved = 1 AND u.jawaban_admin IS NOT NULL";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jawab'], $_POST['id_feedback'])) {
    $id_feedback = intval($_POST['id_feedback']);
    $jawaban = trim($_POST['jawaban_admin']);
    $conn->query("UPDATE feedback SET jawaban_admin='$jawaban', is_approved=1 WHERE id_feedback=$id_feedback");
    header("Location: faq.php");
    exit;
}

$pending = $conn->query("SELECT f.id_feedback, f.pesan, u.username 
    FROM feedback f 
    JOIN users u ON f.user_id = u.id_users 
    WHERE f.jawaban_admin IS NULL OR f.jawaban_admin = '' 
    ORDER BY f.tanggal_feedback DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>FAQ - Senku Coffee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="StyleAdmin/faqstyle.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .main-content {
            margin-left: 250px;
        }

        .card-custom {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .accordion-button {
            background-color: #fff;
        }

        textarea {
            resize: vertical;
        }

        footer {
            margin-top: 3rem;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR (TETAP SAMA) -->
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
                <li><a class="dropdown-item" href="../Bf Login/home.php"><i class="bi bi-box-arrow-right"></i>
                        Logout</a></li>
            </ul>
        </div>
        <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a class="nav-link" href="datapengunjung.php"><i class="bi bi-people me-2"></i>Data Pengunjung</a>
        <a class="nav-link" href="promosi.php"><i class="bi bi-megaphone me-2"></i>Promosi</a>
        <a class="nav-link" href="produk.php"><i class="bi bi-box-seam me-2"></i>Produk</a>
        <a class="nav-link active" href="faq.php"><i class="bi bi-question-circle me-2"></i>FAQ</a>
    </nav>

    <!-- KONTEN UTAMA -->
    <div class="main-content p-4">
        <h3 class="mb-4 text-center" style="margin-top: 30px;">Frequently Asked Questions</h3>

        <div class="accordion mb-4" id="faqAccordion">
            <?php if ($result->num_rows > 0): ?>
                <?php $index = 0;
                while ($row = $result->fetch_assoc()): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $index ?>">
                            <button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>"
                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                                <?= htmlspecialchars($row['pertanyaan']) ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>"
                            aria-labelledby="heading<?= $index ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= nl2br(htmlspecialchars($row['jawaban'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php $index++; endwhile; ?>
            <?php endif; ?>
        </div>

        <!-- PERMINTAAN USER -->
        <div class="mb-4">
            <h4 class="mb-3">Permintaan User</h4>
            <?php while ($row = $q->fetch_assoc()): ?>
                <div class="card-custom">
                    <p><strong><?= htmlspecialchars($row['username']) ?></strong> (<?= htmlspecialchars($row['email']) ?>)
                    </p>
                    <p><?= htmlspecialchars($row['pesan']) ?></p>
                    <?php if ($row['tipe'] === 'ganti_password'): ?>
                        <p class="text-info mb-1">Permintaan: <strong>Ganti Password</strong></p>
                    <?php elseif ($row['tipe'] === 'hapus_akun'): ?>
                        <p class="text-danger mb-1">Permintaan: <strong>Hapus Akun</strong></p>
                    <?php endif; ?>
                    <form method="post" class="d-flex gap-2">
                        <input type="hidden" name="id_notif" value="<?= $row['id_notif'] ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Setujui</button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Tolak</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- PERTANYAAN BELUM DIJAWAB -->
        <div>
            <h4 class="mb-3">Pertanyaan/Keluhan User yang Belum Dijawab</h4>
            <?php while ($row = $pending->fetch_assoc()): ?>
                <div class="card-custom">
                    <p><strong><?= htmlspecialchars($row['username']) ?></strong></p>
                    <p><?= htmlspecialchars($row['pesan']) ?></p>
                    <form method="post">
                        <input type="hidden" name="id_feedback" value="<?= $row['id_feedback'] ?>">
                        <textarea name="jawaban_admin" class="form-control mb-2" placeholder="Jawab di sini..."
                            required></textarea>
                        <button type="submit" name="jawab" class="btn btn-success btn-sm">Jawab & Tampilkan</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        <hr>
        <div class="penutup" style="padding-bottom: 0px;">
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