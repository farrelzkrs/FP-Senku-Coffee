<?php
// filepath: c:\laragon\www\FP Pemweb\Bf Login\navbarprofil.php
session_start();
$current = basename($_SERVER['PHP_SELF']);
$foto_profil = isset($_SESSION['foto_profil']) && $_SESSION['foto_profil'] ? '../uploads/' . $_SESSION['foto_profil'] : '../Resource/fotoprofil2.jpg';
$nama_user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Profil';
?>
<style>
    body {
        overflow-x: hidden;
        /* Hilangkan scroll horizontal */
    }

    .nav-link {
        color: rgb(0, 100, 0);
    }

    .nav-link:hover {
        color: rgb(0, 0, 0) !important;
    }

    .nav-link.active {
        color: rgb(0, 0, 0) !important;
        border: 2px solid #28a745;
        border-radius: 5px;
        background-color: beige;
        color: black;
    }

    .navbar {
        border-bottom: 10px solid #006400;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: 'Trebuchet MS', Arial, sans-serif;
        font-size: 17px;
        font-weight: bold;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
    }

    .navbar-nav .nav-item {
        margin-right: 15px;
    }

    .btn-outline-success {
        width: 100px;
        height: 40px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        border: 2px solid #28a745;
        color: #000000;
        background-color: beige;
        transition: all 0.3s ease;
        margin-left: 20px;
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
        border-color: #218838;
    }

    .navbar-logo {
        height: 65px;
        /* Sesuaikan tinggi logo dengan tinggi tulisan di navbar */
        width: auto;
        /* Menjaga proporsi gambar */
    }

    .profil-container {
        max-width: 500px;
        margin: 90px auto 30px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        padding: 30px;
    }

    .profil-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #28a745;
        margin-bottom: 18px;
    }

    .profil-label {
        font-weight: bold;
    }

    .profil-btn {
        margin-top: 10px;
    }

    .profil-section {
        margin-bottom: 30px;
    }
</style>
<div class="header">
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top" style="background-color: burlywood;">
        <div class="container-fluid" style="margin: 0px 50px 0px 50px; padding: 10px;">
            <a href="" style="display: flex; align-items: center;">
                <img src="../Resource/Senku kafe.png" alt="Logo Senku Coffee" class="navbar-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto me-2 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link<?= $current == 'profil.php' ? ' active' : '' ?>" href="profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= $current == 'notifpesan.php' ? ' active' : '' ?>"
                            href="notifpesan.php">Pesan</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-2">
                    <li class="nav-profil-item dropdown dropdown-profil">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                            id="navbarProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= htmlspecialchars($foto_profil) . '?v=' . time() ?>" alt="Profil"
                                class="rounded-circle" width="47" height="47"
                                style="object-fit: cover; margin-right: 10px;">
                            <?= htmlspecialchars($nama_user) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profil"
                            aria-labelledby="navbarProfileDropdown">
                            <li>
                                <a class="dropdown-item" href="home.php">
                                    <i class="bi bi-house-door"></i> Kembali
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>