<?php
session_start();
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
    <link rel="stylesheet" href="StyleCSS/homestyle.css">
    <title>Home - Senku Coffee</title>
</head>
<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbarbf.php'; ?>    
    </div>
    <main class="flex-grow-1 d-flex align-items-center justify-content-center" style="min-height:300px;">
        <div class="text-center">
            <h1>Selamat Datang di Senku Coffee!</h1>
            <p>Nikmati kopi terbaik dari kami.</p>
        </div>
    </main>
    <footer class="text-center mt-4 mb-2 py-3"
        style="background:rgb(0, 0, 0); color: #fff; border-top: 10px solid #006400;">
        &copy; 2025 Senku Coffee &middot;
        Jl. Kopi No. 123, Jakarta &middot;
        <a href="mailto:info@senkucoffee.com"
            class="link-warning link-underline-opacity-25 link-underline-opacity-100-hover">info@senkucoffee.com</a>
    </footer>
    </div>    
</body>
</html>
