<?php
include '../koneksi.php';

$current = basename($_SERVER['PHP_SELF']);
$is_logged_in = isset($_SESSION['user_id']);
$foto_profil = '../Resource/fotoprofil2.jpg';
$nama_user = 'Profil';

if ($is_logged_in) {
    $id = $_SESSION['user_id'];
    $q = $conn->query("SELECT username, photo_profil FROM users WHERE id_users=$id");
    if ($row = $q->fetch_assoc()) {
        $nama_user = $row['username'];
        $foto_profil = $row['photo_profil'] ? '../uploads/' . $row['photo_profil'] : $foto_profil;
    }
}
?>
<div class="header">
    <!-- header -->
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
                        <a class="nav-link <?= $current == 'home.php' ? 'active' : '' ?>" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current == 'menu.php' ? 'active' : '' ?>" href="menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current == 'ulasan.php' ? 'active' : '' ?>" href="ulasan.php">Ulasan</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($current == 'informasi.php') ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Informasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="informasi.php#about">About Us</a></li>
                            <li><a class="dropdown-item" href="informasi.php#kontak">Kontak</a></li>
                        </ul>
                    </li>
                </ul>
                <?php if (!$is_logged_in): ?>
                <form class="d-flex" role="search">
                    <a href="masuk.php" class="btn btn-outline-success me-2" role="button">Masuk</a>
                    <a href="daftar.php" class="btn btn-outline-success" role="button">Daftar</a>
                </form>
                <?php else: ?>
                <ul class="navbar-nav ms-2">
                    <li class="nav-profil-item dropdown dropdown-profil">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= htmlspecialchars($foto_profil) . '?v=' . time() ?>" alt="Profil" class="rounded-circle" width="47" height="47" style="object-fit: cover; margin-right: 10px;">
                            <?= htmlspecialchars($nama_user) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profil" aria-labelledby="navbarProfileDropdown">
                            <li><a class="dropdown-item" href="profil.php">Lihat Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>    
</div>