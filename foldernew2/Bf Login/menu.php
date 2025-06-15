<?php
include 'auth.php';
include '../koneksi.php';

// Ambil semua promosi yang aktif dan tanggal berlaku
$promosi = $conn->query("SELECT * FROM promosi WHERE is_aktif=1 AND CURDATE() BETWEEN tanggal_mulai AND tanggal_berakhir ORDER BY tanggal_mulai DESC");

// Ambil semua produk kopi beserta jenisnya
$produk = $conn->query("SELECT p.*, j.nama_jenis FROM produk p LEFT JOIN jeniskopi j ON p.jenis_kopi_id = j.id_kopi ORDER BY p.id_produk DESC");

// Ambil semua jenis kopi
$jeniskopi = $conn->query("SELECT * FROM jeniskopi ORDER BY id_kopi DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="StyleCSS/menustyle.css">
    <title>Menu - Senku Coffee</title>
    <style>
/* Style untuk card produk dan jenis kopi */
.card {
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08), 0 1.5px 4px rgba(0,0,0,0.07);
    transition: transform 0.18s, box-shadow 0.18s;
    border: none;
    background: #fffbe9;
}

.card:hover {
    transform: translateY(-7px) scale(1.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.13), 0 2px 8px rgba(0,0,0,0.10);
}

.card-img-top {
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
    object-fit: cover;
    height: 243px;
    background: #f5f5f5;
}

.card-title {
    font-weight: bold;
    color: #6b3e26;
    font-size: 1.25rem;
}

.card-text {
    color: #444;
    font-size: 1rem;
}

.card-footer {
    background: transparent;
    border-top: none;
    font-size: 1.1rem;
    color: #7c5e3c;
    font-weight: bold;
}

.badge.bg-secondary {
    background: #a67c52 !important;
    color: #fffbe9;
    font-size: 0.95em;
    margin-bottom: 8px;
    margin-right: 5px;
    border-radius: 8px;
    padding: 6px 12px;
}
footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
</style>
<style>
    
</style>
</head>
<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbarbf.php'; ?>    
    </div>
    
    <div class="content">
        <section id="poster" class="position-relative text-center">
            <img src="https://asset.mediaindonesia.com/news/2023/09/8a5e048b398c2ce7e0cf87d0f8569b33.jpeg" 
                alt="Iklan Senku Coffee" 
                class="img-fluid w-100" 
                style="max-height: 400px; object-fit: cover;">
            <div class="position-absolute top-50 start-50 translate-middle text-white" style="margin-top: 60px;">
                <h1>Daftar Menu Senku Coffee</h1>
                <h3>Get your coffee now!</h3>
            </div>
        </section>
        <section id="iklan">
            <div class="row container-fluid" style="margin-left: 0px;">
                <h1 style="margin-top: 20px; margin-left: 37px;">Promo</h1>
                <div id="carouselPromo" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="margin-left: 50px;">
                        <?php
                        $first = true;
                        while($row = $promosi->fetch_assoc()): ?>
                        <div class="carousel-item<?= $first ? ' active' : '' ?>">
                            <div class="row g-0">
                                <!-- Kolom Gambar -->
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <img src="../uploads/<?= htmlspecialchars($row['gambar_url']) ?>" class="d-block w-100 bg-light" alt="<?= htmlspecialchars($row['judul']) ?>">
                                </div>
                                <!-- Kolom Penjelasan -->
                                <div class="col-md-7 d-flex align-items-center bg-light">
                                    <div class="p-3">
                                        <h2><?= htmlspecialchars($row['judul']) ?></h2>
                                        <h4><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($row['tanggal_berakhir'])) ?></h4>
                                        <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $first = false; endwhile; ?>
                    </div>
                    <!-- Tombol Navigasi -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselPromo" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselPromo" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
        <section id="menuu">
            <div class="row container-fluid">
                <div class="col-12 p-5 mb-2" style="margin-left: 13px;">
                    <!-- artikel -->
                    <h1 style="margin-top: 20px;">Minuman Coffee</h1>
                    <div class="row row-cols-1 row-cols-md-3 g-4" style="margin-top: 20px;">
                        <?php
                        // Ambil semua produk kopi beserta jenisnya
                        $produk = $conn->query("SELECT p.*, j.nama_jenis FROM produk p LEFT JOIN jeniskopi j ON p.jenis_kopi_id = j.id_kopi ORDER BY p.id_produk DESC");
                        while($row = $produk->fetch_assoc()): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="../uploads/<?= htmlspecialchars($row['gambar_url']) ?>" class="card-img-top" style="height: 243px; object-fit:cover;" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($row['nama_jenis']) ?></span>
                                    <p class="card-text"><?= htmlspecialchars($row['deskripsi']) ?></p>
                                </div>
                                <div class="card-footer">
                                    <big class="text-body-secondary">Harga Rp. <?= number_format($row['harga'],0,',','.') ?></big>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <h1 style="margin-top: 50px;">Jenis Kopi</h1>
                    <div class="row row-cols-1 row-cols-md-3 g-4" style="margin-top: 20px;">
                        <?php while($row = $jeniskopi->fetch_assoc()): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="../uploads/<?= htmlspecialchars($row['gambar_kopi']) ?>" class="card-img-top" style="height: 243px; object-fit:cover;" alt="<?= htmlspecialchars($row['nama_jenis']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($row['nama_jenis']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($row['deskripsi']) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="penutup">
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
</body>
</html>