<?php
session_start();
include '../koneksi.php';

// Ambil 3 poster aktif terbaru
$poster = $conn->query("SELECT * FROM poster WHERE is_aktif=1 ORDER BY id_poster DESC LIMIT 3");

// Ambil 3 produk terpopuler (misal berdasarkan id_produk terbaru)
$produk_populer = $conn->query("SELECT p.*, j.nama_jenis FROM produk p LEFT JOIN jeniskopi j ON p.jenis_kopi_id = j.id_kopi ORDER BY p.id_produk DESC LIMIT 3");

// Ambil 3 ulasan terbaik
$ulasan = $conn->query("SELECT u.*, us.username FROM ulasan u JOIN users us ON u.user_id = us.id_users WHERE u.rating >= 4 ORDER BY u.rating DESC");

// Ambil 4 foto galeri aktif terbaru
$galeri = $conn->query("SELECT * FROM galeri WHERE is_aktif=1 ORDER BY created_at DESC LIMIT 4");

// Proses kirim pertanyaan
$pesan_sukses = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_pertanyaan'])) {
    $pesan = trim($_POST['pesan']);

    // Gunakan user yang sedang login, atau guest (misal user_id = NULL)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Simpan pertanyaan ke tabel feedback
    if ($user_id) {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, pesan, is_approved) VALUES (?, ?, 0)");
        $stmt->bind_param("is", $user_id, $pesan);
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (pesan, is_approved) VALUES (?, 0)");
        $stmt->bind_param("s", $pesan);
    }
    $stmt->execute();
    $stmt->close();

    $pesan_sukses = "Pertanyaan/Keluhan Anda berhasil dikirim! Tunggu jawaban dari admin.";
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
            padding: 20px 0;
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

        /* Card Style untuk Menu Populer (sama seperti menu.php) */
        .card {
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), 0 1.5px 4px rgba(0, 0, 0, 0.07);
            transition: transform 0.18s, box-shadow 0.18s;
            border: none;
            background: #fffbe9;
        }

        .card:hover {
            transform: translateY(-7px) scale(1.03);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.13), 0 2px 8px rgba(0, 0, 0, 0.10);
        }

        .card-img-top.menu-item {
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

        .badge.bg-secondary {
            background: #a67c52 !important;
            color: #fffbe9;
            font-size: 0.95em;
            margin-bottom: 8px;
            margin-right: 5px;
            border-radius: 8px;
            padding: 6px 12px;
        }

        .carousel-caption-custom {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.4); /* putih transparan */
            color: #000;
            padding: 10px;
            text-align: center;
        }
        .carousel-item {
            position: relative;
        }
        .carousel-item img {
            height: 310px;
            object-fit: cover;
            width: 100%;
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
            <div class="postwelcome">
                <div id="carouselPosterWelcome" class="carousel slide" data-bs-ride="carousel"
                    style="margin: 0px 60px 0px 60px;">
                    <div class="carousel-indicators">
                        <?php
                        $i = 0;
                        foreach ($poster as $row) {
                            echo '<button type="button" data-bs-target="#carouselPosterWelcome" data-bs-slide-to="' . $i . '"' . ($i == 0 ? ' class="active" aria-current="true"' : '') . ' aria-label="Slide ' . ($i + 1) . '"></button>';
                            $i++;
                        }
                        ?>
                    </div>
                    <div class="carousel-inner">
                        <?php
                        $i = 0;
                        foreach ($poster as $row): ?>
                            <div class="carousel-item<?= $i == 0 ? ' active' : '' ?>">
                                <img src="../uploads/<?= htmlspecialchars($row['url_post']) ?>" class="d-block w-100"
                                    alt="<?= htmlspecialchars($row['judul_post']) ?>">
                                <div class="carousel-caption d-none d-md-block"
                                    style="background: rgba(63, 63, 63, 0.3); padding: 10px; border-radius: 5px;">
                                    <h5><?= htmlspecialchars($row['judul_post']) ?></h5>
                                    <p><?= nl2br(htmlspecialchars($row['caption'])) ?></p>
                                </div>
                            </div>
                            <?php $i++; endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselPosterWelcome"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselPosterWelcome"
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

    <section id="ulasan" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Apa Kata Mereka?</h2>
            <?php
            // Ambil maksimal 12 ulasan terbaru
            $ulasanResult = $conn->query("SELECT u.*, us.username FROM ulasan u JOIN users us ON u.user_id = us.id_users WHERE u.rating >= 4 ORDER BY u.rating DESC");
            $ulasanList = [];
            while ($row = $ulasanResult->fetch_assoc()) {
                $ulasanList[] = $row;
            }
            $totalUlasan = count($ulasanList);
            ?>
            <?php if ($totalUlasan > 0): ?>
                <div id="ulasanCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
                    <div class="carousel-inner">
                        <?php
                        $slideIndex = 0;
                        for ($i = 0; $i < $totalUlasan; $i += 3):
                            $active = ($slideIndex == 0) ? 'active' : '';
                            ?>
                            <div class="carousel-item <?= $active ?>">
                                <div class="row justify-content-center">
                                    <?php for ($j = $i; $j < $i + 3 && $j < $totalUlasan; $j++):
                                        $row = $ulasanList[$j];
                                        ?>
                                        <div class="col-md-4 mb-4 d-flex align-items-stretch">
                                            <div class="card h-100 w-100 p-3">
                                                <h4 class="text-center mt-3 mb-5">"<?= htmlspecialchars($row['deskripsi']) ?>"</h4>
                                                <h3 class="text-center fw-4">- <?= htmlspecialchars($row['username']) ?></h3>
                                                <div class="star-display mb-2 text-center">
                                                    <?php
                                                    $rating = isset($row['rating']) ? (int) $row['rating'] : 0;
                                                    for ($k = 1; $k <= 5; $k++) {
                                                        $filled = $k <= $rating ? 'color:gold;' : 'color:#ccc;';
                                                        echo '<span style="' . $filled . '">&#9733;</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php
                            $slideIndex++;
                        endfor;
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#ulasanCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#ulasanCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <script>
                    // Optional: Pause on hover, resume on mouse leave
                    const ulasanCarousel = document.getElementById('ulasanCarousel');
                    ulasanCarousel.addEventListener('mouseenter', function () {
                        const carousel = bootstrap.Carousel.getOrCreateInstance(ulasanCarousel);
                        carousel.pause();
                    });
                    ulasanCarousel.addEventListener('mouseleave', function () {
                        const carousel = bootstrap.Carousel.getOrCreateInstance(ulasanCarousel);
                        carousel.cycle();
                    });
                </script>
            <?php else: ?>
                <div class="d-flex justify-content-center align-items-center" style="height:200px;">
                    <div class="card p-4 text-center">
                        <p class="m-0">Belum ada ulasan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section id="about" class="bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Tentang Kami</h2>
            <div class="row align-items-center">
                <div class="col-md-6 mb-4">
                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                            $isFirst = true;
                            while ($row = $galeri->fetch_assoc()) {
                                $activeClass = $isFirst ? 'active' : '';
                                $isFirst = false;
                                echo '<div class="carousel-item ' . $activeClass . '" data-bs-interval="5000">';
                                echo '<img src="../uploads/' . $row['foto_url'] . '" class="d-block w-100" style="height: 308px; object-fit: cover;" alt="' . htmlspecialchars($row['judul_foto']) . '">';
                                echo '<div class="carousel-caption-custom">';
                                echo '<h5>' . htmlspecialchars($row['judul_foto']) . '</h5>';
                                echo '<p>' . htmlspecialchars($row['deskripsi_foto']) . '</p>';
                                echo '</div></div>';
                            }
                            ?>
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
                    <p>Senku Coffee hadir untuk Anda yang ingin menikmati kopi terbaik di suasana nyaman.</p>
                    <p>Kami hanya menggunakan biji kopi pilihan dan barista berpengalaman.</p>
                    <a href="informasi.php" class="btn btn-outline-success mt-3 px-4"
                        style="width: 170px;">Selengkapnya</a>
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
                    <h4>Kirim Pertanyaan</h4>
                    <form method="post">
                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Pesan</label>
                            <textarea class="form-control" id="contactMessage" name="pesan" rows="4"
                                required></textarea>
                        </div>
                        <button type="submit" name="kirim_pertanyaan" class="btn btn-success">Kirim Pesan</button>
                        <?php if ($pesan_sukses): ?>
                            <div class="alert alert-success mt-3"><?= $pesan_sukses ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">FAQ</h2>
            <div class="accordion" id="faqAccordion">
                <?php
                $faq = $conn->query("SELECT f.pesan AS pertanyaan, f.jawaban_admin AS jawaban 
                    FROM feedback f 
                    WHERE f.is_approved = 1 AND f.jawaban_admin IS NOT NULL 
                    ORDER BY f.tanggal_feedback DESC LIMIT 10");
                if ($faq->num_rows > 0):
                    $idx = 0;
                    while ($row = $faq->fetch_assoc()):
                        ?>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading<?= $idx ?>">
                                <button class="accordion-button <?= $idx !== 0 ? 'collapsed' : '' ?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse<?= $idx ?>"
                                    aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $idx ?>">
                                    <?= htmlspecialchars($row['pertanyaan']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $idx ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>"
                                aria-labelledby="heading<?= $idx ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?= nl2br(htmlspecialchars($row['jawaban'])) ?>
                                </div>
                            </div>
                        </div>
                        <?php $idx++; endwhile; else: ?>
                    <p class="text-muted">Belum ada pertanyaan yang dijawab admin.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

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