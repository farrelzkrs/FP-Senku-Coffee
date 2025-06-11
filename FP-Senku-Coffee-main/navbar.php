<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top" style="background-color: burlywood;">
    <div class="container-fluid" style="margin: 0px 50px 0px 50px; padding: 10px;">
        <a href="home.php" style="display: flex; align-items: center;">
            <img src="Resource/Senku kafe.png" alt="Logo Senku Coffee" class="navbar-logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto me-2 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link<?php if($current=='home.php') echo ' active'; ?>" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php if($current=='menu.php') echo ' active'; ?>" href="menu.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php if($current=='ulasan.php') echo ' active'; ?>" href="ulasan.php">Ulasan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?php if($current=='informasi.php') echo ' active'; ?>" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="informasi.php#about">About Us</a></li>
                        <li><a class="dropdown-item" href="informasi.php#kontak">Kontak</a></li>
                    </ul>
                </li>
            </ul>
            <?php if(!$isLoggedIn): ?>
            <form class="d-flex" role="search">
                <a href="masuk.php"
                    class="btn btn-outline-success me-2<?php if($current=='masuk.php') echo ' active'; ?>"
                    role="button">Masuk</a>
                <a href="daftar.php" class="btn btn-outline-success<?php if($current=='daftar.php') echo ' active'; ?>"
                    role="button">Daftar</a>
            </form>
            <?php else: ?>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?php if($current=='profil.php') echo ' active'; ?>" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profil
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item<?php if($current=='profil.php') echo ' active'; ?>"
                                href="profil.php">Profil Saya</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>