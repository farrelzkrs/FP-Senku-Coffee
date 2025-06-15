<?php
include '../koneksi.php';
$username_error = $_GET['username_error'] ?? '';
$email_error = $_GET['email_error'] ?? '';
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
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
    <title>Daftar - Senku Coffee</title>
    <style>
body {
    background: #f7f7f7;
}

.header .p-3 {
    background: burlywood;
    color: #fff;
    text-align: left;
    font-size: 1.5rem;
    font-weight: bold;
    border-bottom: 10px solid #006400;
}

.header .btn-outline-success {
    background-color: beige;
    border: 2px solid #28a745;
}

.btn-outline-success:hover {
    background-color: #28a745;
    color: white;
    border-color: #218838;
}

.login-container {
    max-width: 400px;
    margin: 20px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    padding: 20px 30px 20px 30px;
    text-align: center;
}

.brand-logo {
    width: 100px;
    margin-bottom: 18px;
}

.form-control {
    margin-bottom: 18px;
}

.forgot-link,
.register-link {
    display: block;
    margin-top: 10px;
    font-size: 0.97rem;
}

.register-section {
    margin-top: 30px;
    border-top: 1px solid #eee;
    padding-top: 18px;
}
    </style>
</head>

<body>
<div class="header">
        <header class="p-3">
            <button type="button" class="btn btn-outline-success" onclick="if(document.referrer){history.back();}else{window.location.href='../Bf Login/home.php';}">
                &larr; Kembali
            </button>
        </header>
    </div>
    <section id="content" class="content-section">
        <div class="login-container">
            <img src="../Resource/Senku kafe.png" alt="Brand Logo" class="brand-logo">
            <h3 class="mb-0">Sign Up</h3>
            <p class="mb-4">Daftar Akun Baru Anda!</p>
            <?php if ($error): ?>
            <div class="alert alert-danger py-2 text-center" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            <?php if ($success): ?>
            <div class="alert alert-success py-2 text-center" role="alert">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            <form action="./Proses BF Login/proses_daftar.php" method="post" autocomplete="off">
                <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
                <?php if ($username_error): ?>
                <div class="text-danger mb-2" style="font-size:0.95em;">
                    <?= htmlspecialchars($username_error) ?>
                </div>
                <?php endif; ?>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email"
                    pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$"
                    title="Email harus menggunakan domain @gmail.com" required>
                <?php if ($email_error): ?>
                <div class="text-danger mb-2" style="font-size:0.95em;">
                    <?= htmlspecialchars($email_error) ?>
                </div>
                <?php endif; ?>
                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required minlength="6">
                <input type="password" name="confirm_password" class="form-control mb-3" placeholder="Konfirmasi Password" required minlength="6">
                <button type="submit" class="btn btn-success w-100 mb-2">Daftar</button>
            </form>
            <div class="register-section d-flex flex-column flex-sm-row justify-content-center align-items-center mt-3">
                <span class="me-2 mb-1 mb-sm-0">Sudah punya akun?</span>
                <a href="masuk.php" class="me-2 mb-2 mb-0 register-link text-decoration-none text-primary">Masuk di sini</a>
            </div>
        </div>
    </section>
</body>

</html>