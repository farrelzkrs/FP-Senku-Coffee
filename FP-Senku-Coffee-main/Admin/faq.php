<?php
include '../koneksi.php';
$query = "SELECT u.deskripsi AS pertanyaan, u.jawaban_admin AS jawaban FROM ulasan u WHERE u.is_approved = 1 AND u.jawaban_admin IS NOT NULL";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="StyleAdmin/faqstyle.css">
    <title>FAQ - Senku Coffee</title>
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

    <div class="main-content p-4">
        <h3 class="mb-4 text-center">Frequently Asked Questions</h3>
        <div class="accordion" id="faqAccordion">
            <?php if ($result->num_rows > 0): ?>
            <?php $index = 0; while ($row = $result->fetch_assoc()): ?>
            <div class="accordion-item mb-3">
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
            <?php $index++; ?>
            <?php endwhile; ?>
            <?php else: ?>
            <p class="text-muted">Belum ada pertanyaan yang disetujui.</p>
            <?php endif; ?>
        </div>

        <footer class="text-center mb-2 mt-5">
            <hr>
            <small>
                &copy; 2025 Senku Coffee &middot;
                Jl. Kopi No. 123, Jakarta &middot;
                <a href="mailto:info@senkucoffee.com" class="text-decoration-none">info@senkucoffee.com</a>
            </small>
        </footer>
    </div>
</body>

</html>