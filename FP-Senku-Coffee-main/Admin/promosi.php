<?php

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