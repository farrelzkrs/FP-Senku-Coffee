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
    <link rel="stylesheet" href="StyleCSS2/informasistyle2.css">
    <title>Informasi - Senku Coffee</title>
</head>

<body>
    <div class="header">
        <!-- header -->
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top" style="background-color: burlywood;">
            <div class="container-fluid" style="margin: 0px 50px 0px 50px; padding: 10px;">
                <a href="" style="display: flex; align-items: center;">
                    <img src="../Resource/Senku kafe.png" alt="Logo Senku Coffee" class="navbar-logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto me-2 mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="home2.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="menu2.php">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ulasan2.php">Ulasan</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Informasi
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="informasi2.php#about">About Us</a></li>
                                <li><a class="dropdown-item" href="informasi2.php#kontak">Kontak</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-2">
                        <li class="nav-profil-item dropdown dropdown-profil">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="../Resource/fotoprofil2.jpg" alt="Profil" class="rounded-circle" width="47" height="47" style="object-fit: cover; margin-right: 10px;">
                                Profil
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profil" aria-labelledby="navbarProfileDropdown">
                                <li><a class="dropdown-item" href="profil.php">Lihat Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../Bf Login/home.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <small>
        &copy; 2025 Senku Coffee &middot; 
        Jl. Kopi No. 123, Jakarta &middot; 
        <a href="mailto:info@senkucoffee.com" class="text-decoration-none">info@senkucoffee.com</a>
    </small>
</body>

</html>