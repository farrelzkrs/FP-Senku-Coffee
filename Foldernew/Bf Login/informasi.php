<?php
include 'auth.php';
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
    <link rel="stylesheet" href="StyleCSS/informasistyle.css">
    <title>Informasi - Senku Coffee</title>
    <style>
        .wrapper {
    width: 415px;
    margin: auto;
    border-radius: 5px;
    overflow: hidden;
}

.swiper {
    width: 100%;
    height: auto;
}

.swiper-slide img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.slider-img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.map-container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    border-radius: 10px;
    overflow: hidden;
}

iframe {
    width: 100%;
    height: 400px;
    border: none;
}

.info-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.info-box {
    background-color: white;
    flex: 1 1 220px;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.info-box .icon {
    font-size: 32px;
    color: red;
    margin-bottom: 10px;
}

.info-box h4 {
    margin-bottom: 10px;
    font-size: 18px;
}

.info-box p {
    margin: 0;
    color: #333;
    font-size: 15px;
}

@media screen and (max-width: 600px) {
    h2 {
        font-size: 22px;
    }
}

.kelebihan {
    text-align: center;
    padding: 50px 20px;
}

.container-kelebihan h2 {
    font-size: 28px;
    margin-bottom: 40px;
    color: #222;
}

.container-kelebihan {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.card-kelebihan {
    background-color: #deb887;
    /* warna coklat muda mirip pada gambar */
    border-radius: 10px;
    padding: 30px 20px;
    width: 300px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.card-kelebihan .icon {
    font-size: 36px;
    margin-bottom: 15px;
    color: #222;
}

.card-kelebihan h4 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #222;
}

.card-kelebihan p {
    font-size: 14px;
    color: #222;
}

        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbarbf.php'; ?>
    </div>
    <div class="konten">
        <main class="py-5 bg-light">
            <div class="container text-center">
                <h1>Selamat Datang di Senku Coffee!</h1>
                <p>Nikmati kopi terbaik dari kami.</p>
            </div>
        </main>
        <h2 class="text-center">Tentang Kami</h2>
            <div class="container my-5">
                <div class="row align-items-center">
                <!-- Kolom Gambar (Slider) -->
                    <div class="col-md-6">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="../Resource/kafe1.jpg" class="slider-img" alt="Gambar 1">
                                </div>
                                <div class="swiper-slide">
                                    <img src="../Resource/kafe2.jpg" class="slider-img" alt="Gambar 2">
                                </div>
                                <div class="swiper-slide">
                                    <img src="../Resource/kafe3.jpg" class="slider-img" alt="Gambar 3">
                                </div>
                                <div class="swiper-slide">
                                    <img src="../Resource/kafe4.jpg" class="slider-img" alt="Gambar 4">
                                </div>
                            </div>
                            <!-- Navigasi & Pagination (opsional) -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>

                    <!-- Kolom Teks -->
                    <div class="col-md-6">
                        <h3>Tentang Senku Coffee</h3>
                        <p>
                        Selamat Datang di Senku Coffee
                        Senku Coffee lahir dari kecintaan kami terhadap kopi berkualitas dan keinginan untuk menciptakan ruang yang nyaman bagi komunitas.
                        </p>
                        <p>Kami percaya bahwa secangkir kopi yang nikmat dapat mencerahkan hari Anda. 
                        Oleh karena itu, kami hanya menggunakan biji kopi pilihan terbaik dan menyajikannya dengan sepenuh hati oleh barista kami yang berpengalaman.
                        </p>
                        <p>Lebih dari sekadar kedai kopi, 
                        Senku Coffee adalah tempat Anda bertemu teman, bekerja, atau sekadar menikmati waktu santai.
                        Datang dan rasakan pengalaman ngopi yang berbeda!
                        </p>
                    </div>
                </div>
            </div>
            <hr>
            <section class="kelebihan">
                <h2>Kenapa Memilih Senku Coffee?</h2>
                <div class="container-kelebihan">
                    <div class="card-kelebihan">
                        <div class="icon">🏠</div>
                        <h4>Tempat Nyaman</h4>
                        <p>Desain menarik, suasana cozy, dan fasilitas lengkap membuat pelanggan betah.</p>
                    </div>      
                    <div class="card-kelebihan">
                        <div class="icon">😊</div>
                        <h4>Pelayanan Ramah</h4>
                        <p>Staf sopan, sigap, berpengetahuan, dan menciptakan pengalaman positif.</p>
                    </div>      
                    <div class="card-kelebihan">
                        <div class="icon">📶</div>
                        <h4>Wi-Fi Kencang</h4>
                        <p>Koneksi internet stabil dan cepat mendukung berbagai kebutuhan pelanggan.</p>
                    </div>
                </div>
            </section>

            <hr>
            <h2 style="text-align: center;">Need Help? Contact Us</h2>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.179561534431!2d112.78574977500047!3d-7.333721192674741!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fab87edcad15%3A0xb26589947991eea1!2sUniversitas%20Pembangunan%20Nasional%20%E2%80%9CVeteran%E2%80%9D%20Jawa%20Timur!5e0!3m2!1sen!2sid!4v1749402845552!5m2!1sen!2sid" 
                    width="600" 
                    height="450" style="border:0;" allowfullscreen="" 
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

<!-- Kontak Informasi -->
    <div class="info-section">
        <div class="info-box">
            <div class="icon">📍</div>
            <h4>Address</h4>
            <p>Jl. Kopi Nikmat No. 10, Jakarta, Indonesia</p>
        </div>
        <div class="info-box">
            <div class="icon">📞
                <h4>Call Us</h4>
                <p>+62 12345678</p>
            </div>
            <div class="info-box">
                <div class="icon">📧</div>
                <h4>Email Us</h4>
                <p>info@senkucoffee.com</p>
            </div>
            <div class="info-box">
                <div class="icon">⏰</div>
                <h4>Jam Buka</h4>
                <p><strong>Setiap hari:</strong> 07.00 – 22.00</p>
            </div>
        </div>
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
        style="background:rgb(0, 0, 0); color: #fff; border-top: 10px solid #006400;">
        &copy; 2025 Senku Coffee &middot;
        Jl. Kopi No. 123, Jakarta &middot;
        <a href="mailto:info@senkucoffee.com"
            class="link-warning link-underline-opacity-25 link-underline-opacity-100-hover">info@senkucoffee.com</a>
    </footer>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper(".mySwiper", {
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false
        }
    });
</script>
</body>

</html>