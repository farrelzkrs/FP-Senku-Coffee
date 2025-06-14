<?php
include 'auth.php';
$conn = new mysqli("localhost", "root", "", "senku_coffee", "3307");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT u.deskripsi, u.rating, usr.username
FROM ulasan u
JOIN users usr ON u.user_id = usr.id_users
ORDER BY u.tanggal_ulasan DESC
LIMIT 5";
$result = $conn->query($sql);
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
    <link rel="stylesheet" href="StyleCSS/ulasanstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Ulasan - Senku Coffee</title>
    <style>
        body {
    padding-top: 120px;
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
.Testimoni {
    padding: 60px 20px;
    display: flex;
    justify-content: center;
  }
  
  .mySwiper {
    width: 100%;
    max-width: 1200px;
    position: relative;
  }
  
  .swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .card {
    width: 100%;
    max-width: 350px;
    padding: 25px;
    margin: auto;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-align: center;
  }
  
  .testimoni-text {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px;
  }
  
  .testimoni-text:last-child {
    font-style: italic;
    font-weight: bold;
    color: #555;
  }
  
  .swiper-button-next,
  .swiper-button-prev {
    color: #333;
    top: 50%;
    transform: translateY(-50%);
  }
  
  .swiper-pagination {
    position: absolute;
    bottom: -25px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
  }
  
  .form {
    margin-left: 95px;
    margin-right: 95px;
  }

  hr {
    border: none;
    height: 2px;
    color: #333; 
    background-color: #333;
  }

  .star-rating {
    direction: rtl;
    display: inline-flex;
    gap: 5px;
  }
  
  .star-rating input[type="radio"] {
    display: none;
  }
  
  .star-rating label {
    font-size: 28px;
    color: #ccc;
    cursor: pointer;
  }
  
  .star-rating input[type="radio"]:checked ~ label,
  .star-rating label:hover,
  .star-rating label:hover ~ label {
    color: gold;
  }
  .star-display {
    display: inline-block;
    margin-top: 10px;
    font-size: 22px;
    color: #ccc;
  }
  
  .star-display .star.filled {
    color: gold;
  }
    </style>
</head>

<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbarbf.php'; ?>
    </div>
    <main class="py-5 bg-light">
        <div class="container text-center">
            <h1>Selamat Datang di Senku Coffee!</h1>
            <p>Nikmati kopi terbaik dari kami.</p>
        </div>
    </main>
    <h2 style="text-align: center;">Apa Kata Mereka</h2>
    <section class="Testimoni">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <?php
                $result = $conn->query($sql);
                if (!$result) {
                    die("Query error: " . $conn->error);
                }
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Escape output untuk keamanan
                        $deskripsi = htmlspecialchars($row['deskripsi']);
                        $username = htmlspecialchars($row['username']);
                        $rating = isset($row['rating']) ? (int) $row['rating'] : 0;

                        echo '<div class="swiper-slide">';
                        echo '  <div class="card">';
                        echo '    <p class="testimoni-text">"' . $deskripsi . '"</p>';
                        echo '    <p class="testimoni-text">- ' . $username . '</p>';
                        echo '    <div class="star-display">';
                        for ($i = 1; $i <= 5; $i++) {
                            $filled = $i <= $rating ? 'filled' : '';
                            echo '<span class="star ' . $filled . '">&#9733;</span>';
                        }
                        echo '    </div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="swiper-slide"><div class="card"><p class="testimoni-text">Belum ada ulasan.</p></div></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <h3 style="text-align: center;">Berikan Ulasan Anda</h3>
    <div class="form">
        <form action="simpan_ulasan.php" method="POST">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ulasan" class="form-label">Ulasan Anda</label>
                        <textarea class="form-control" name="ulasan" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Rating:</label>
                        <div class="star-rating">
                            <input type="radio" id="bintang5" name="rating" value="5" checked><label
                                for="bintang5">&#9733;</label>
                            <input type="radio" id="bintang4" name="rating" value="4"><label
                                for="bintang4">&#9733;</label>
                            <input type="radio" id="bintang3" name="rating" value="3"><label
                                for="bintang3">&#9733;</label>
                            <input type="radio" id="bintang2" name="rating" value="2"><label
                                for="bintang2">&#9733;</label>
                            <input type="radio" id="bintang1" name="rating" value="1"><label
                                for="bintang1">&#9733;</label>
                        </div>
                    </div>
                    <button type="submit" style="margin: auto;" class="btn btn-success">Kirim</button>
                </div>
            </div>
        </form>
    </div>

    <div class="penutup">
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
            spaceBetween: 30,
            slidesPerView: 1, // default HP
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false
            },
            breakpoints: {
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                }
            }
        });
    </script>
</body>

</html>