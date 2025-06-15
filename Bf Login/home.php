<?php
session_start();
include '../koneksi.php';

// Ambil 3 produk terpopuler (misal berdasarkan id_produk terbaru)
$produk_populer = $conn->query("SELECT p.*, j.nama_jenis FROM produk p LEFT JOIN jeniskopi j ON p.jenis_kopi_id = j.id_kopi ORDER BY p.id_produk DESC LIMIT 3");

// Ambil 3 ulasan terbaru yang sudah di-approve
$ulasan = $conn->query("SELECT u.*, us.username FROM ulasan u JOIN users us ON u.user_id = us.id_users WHERE u.is_approved = 1 ORDER BY u.tanggal_ulasan DESC LIMIT 3");
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
    <link rel="stylesheet" href="StyleCSS/homestyle.css">
    <title>Home - Senku Coffee</title>
    <style>
        body {
            padding-top: 90px;
        }

        section {
            padding: 60px 0;
            min-height: 400px;
        }

        /* .promo-background {
            You might want to define styles for this if it's used
            Example: background: url('path/to/your/background.jpg') no-repeat center center; background-size: cover;
            For now, we'll remove it if it's not defined in Main Menu.css
        }
        */
        .carousel-item img {
            max-height: 500px;
            object-fit: cover;
        }

        .menu-item img {
            height: 200px;
            object-fit: cover;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 30px 0;
        }

        .navbar-custom {
            background-color: burlywood !important;
        }

        .navbar-brand,
        .nav-link {
            color: rgb(0, 100, 0) !important;
        }

        .nav-link.active {
            font-weight: bold;
        }

        .btn-outline-success {
            color: rgb(0, 100, 0);
            border-color: rgb(0, 100, 0);
        }

        .btn-outline-success:hover {
            background-color: rgb(0, 100, 0);
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbarbf.php'; ?>
    </div>

    <section id="home">
        <div class="content">
            <div class="promo">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel"
                    style="margin: 0px 60px 0px 60px;">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="Gambar/News Menu Utama/News1.png" class="d-block w-100" alt="Promo Banner 1">
                            <div class="carousel-caption d-none d-md-block"
                                style="background: rgba(63, 63, 63, 0.3); padding: 10px; border-radius: 5px;">
                                <h5>Menu Baru Spesial Hanya Minggu Ini</h5>
                                <p>Nikmati Bread Coffee-mu untuk menemani waktu luangmu.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="Gambar/News Menu Utama/News2.png" class="d-block w-100" alt="Promo Banner 2">
                            <div class="carousel-caption d-none d-md-block"
                                style="background: rgba(63, 63, 63, 0.3); padding: 10px; border-radius: 5px;">
                                <h5>Promo New Year Sale</h5>
                                <p>Dapatkan Coffee Impian-mu di Promo New Year Sale</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="Gambar/News Menu Utama/News3.png" class="d-block w-100" alt="Promo Banner 3">
                            <div class="carousel-caption d-none d-md-block"
                                style="background: rgba(63, 63, 63, 0.3); padding: 10px; border-radius: 5px;">
                                <h5>Senku Coffee: Tempat Terbaik untuk Bersantai</h5>
                                <p>Nikmatilah harimu dengan seruput kopi dari senku coffee.</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Menu Populer</h2>
            <div class="row">
                <?php while ($row = $produk_populer->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="../uploads/<?= htmlspecialchars($row['gambar_url']) ?>" class="card-img-top menu-item"
                                alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['deskripsi']) ?></p>
                                <p class="card-text mt-auto"><strong>Rp
                                        <?= number_format($row['harga'], 0, ',', '.') ?></strong></p>
                                <span class="badge bg-secondary"><?= htmlspecialchars($row['nama_jenis']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="text-center mt-4">
                <a href="menu.php" class="btn btn-outline-success w-25">Lihat Menu Lengkap</a>
            </div>
        </div>
    </section>

    <section id="ulasan">
        <div class="container">
            <h2 class="text-center mb-5">Apa Kata Mereka?</h2>
            <div class="row">
                <?php while ($row = $ulasan->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <blockquote class="blockquote mb-0">
                                    <p>"<?= htmlspecialchars($row['deskripsi']) ?>"</p>
                                    <footer class="blockquote-footer"><?= htmlspecialchars($row['username']) ?> <cite
                                            title="Tanggal"><?= date('d M Y', strtotime($row['tanggal_ulasan'])) ?></cite>
                                    </footer>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="about" class="bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Tentang Kami</h2>
            <div class="row align-items-center">
                <div class="col-md-6 mb-4">
                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="10000">
                                <img src="https://asset.kompas.com/crops/s6tT7L5RZpNCJp-WiMhjx6WfNtc=/0x0:1000x667/1200x800/data/photo/2023/06/11/648558734e431.jpeg"
                                    class="d-block w-100" style="height: 308px;" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <img src="https://static.promediateknologi.id/crop/0x0:0x0/0x0/webp/photo/p2/238/2024/04/27/RT-CAFE-N-CUT-15-2746554560.jpeg"
                                    class="d-block w-100" style="height: 308px;" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="https://nibble-images.b-cdn.net/nibble/original_images/cafe-view-danau-di-jakarta-00.jpg"
                                    class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <!-- <img src="Gambar/about-us.jpg" class="img-fluid rounded" alt="Tentang Senku Coffee"> -->
                </div>
                <div class="col-md-6" style="text-align: justify;">
                    <h3>Selamat Datang di Senku Coffee</h3>
                    <p>Senku Coffee lahir dari kecintaan kami terhadap kopi berkualitas dan keinginan untuk menciptakan
                        ruang yang nyaman bagi komunitas.</p>
                    <p>Kami percaya bahwa secangkir kopi yang nikmat dapat mencerahkan hari Anda. Oleh karena itu, kami
                        hanya menggunakan biji kopi pilihan terbaik dan menyajikannya dengan sepenuh hati oleh barista
                        kami yang berpengalaman.</p>
                    <p>Lebih dari sekadar kedai kopi, Senku Coffee adalah tempat Anda bertemu teman, bekerja, atau
                        sekadar menikmati waktu santai. Datang dan rasakan pengalaman ngopi yang berbeda!</p>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak">
        <div class="container">
            <h2 class="text-center mb-5">Hubungi Kami</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h4>Informasi Kontak</h4>
                    <p><i class="bi bi-geo-alt-fill"></i> Jl. Kopi Nikmat No. 10, Jakarta, Indonesia</p>
                    <p><i class="bi bi-telephone-fill"></i> (021) 123-4567</p>
                    <p><i class="bi bi-envelope-fill"></i> info@senkucoffee.com</p>
                    <p><i class="bi bi-clock-fill"></i> Buka Setiap Hari: 08:00 - 22:00 WIB</p>
                    <h4 class="mt-4">Ikuti Kami</h4>
                    <a href="#" class="text-dark me-2 fs-4"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-dark me-2 fs-4"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-dark fs-4"><i class="bi bi-twitter-x"></i></a>
                </div>
                <div class="col-md-6">
                    <h4>Kirim Pesan</h4>
                    <form>
                        <div class="mb-3">
                            <label for="contactName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="contactName" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="contactEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Pesan</label>
                            <textarea class="form-control" id="contactMessage" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
</body>

</html>