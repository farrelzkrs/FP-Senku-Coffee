<?php

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
        </script>
    <link rel="stylesheet" href="StyleCSS2/changepassstyle2.css">
    <title>Change Password - Senku Coffee</title>
    <style>

    </style>
</head>

<body>
    <div class="header">
        <header class="p-3">
            <button class="btn btn-outline-success" onclick="history.back()">
                &larr; Kembali
            </button>
        </header>
    </div>
    <section id="content" class="content-section">
        <div class="login-container">
            <img src="../Resource/Senku kafe.png" alt="Brand Logo" class="brand-logo">
            <h3 class="mb-0">Ganti Password</h3>
            <p class="mb-4">Masukkan data untuk mengganti password Anda.</p>
            <form action="proses_ganti_password.php" method="post">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
                <input type="password" name="new_password" class="form-control" placeholder="Password Baru" required>
                <input type="password" name="confirm_new_password" class="form-control" placeholder="Konfirmasi Password Baru" required>
                <button type="submit" class="btn btn-warning w-100 mb-2">Ganti Password</button>
            </form>
            <div class="register-section d-flex flex-column flex-sm-row justify-content-center align-items-center mt-3">
                <span class="me-2 mb-1 mb-sm-0">Sudah ingat password?</span>
                <a href="Bf Login/masuk.php" class="me-2 mb-2 mb-0 register-link text-decoration-none text-primary">Masuk di sini</a>
            </div>
        </div>
    </section>
</body>

</html>