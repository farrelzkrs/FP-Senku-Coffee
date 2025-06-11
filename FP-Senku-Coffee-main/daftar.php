<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm'] ?? '';
    $photo_profil = '';

    // Validasi sederhana
    if ($username === '' || $email === '' || $password === '' || $confirm_password === '') {
        $error = 'Lengkapi semua data!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid!';
    } elseif (!preg_match('/@gmail\.com$/', $email)) {
        $error = 'Email harus menggunakan domain @gmail.com!';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        // Cek username/email sudah ada
        $stmt = $conn->prepare("SELECT id_users FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Username atau email sudah terdaftar!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            // Jika tabel users punya kolom photo_profil, gunakan query berikut:
            $stmt = $conn->prepare("INSERT INTO users (username, email, user_password, photo_profil) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hash, $photo_profil);
            if ($stmt->execute()) {
                $success = 'Pendaftaran berhasil! Silakan masuk.';
            } else {
                $error = 'Terjadi kesalahan saat mendaftar.';
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <title>Daftar - Senku Coffee</title>
    <style>
    body {
        background: #f7f7f7;
        min-height: 100vh;
    }

    .logo-navbar {
        width: 100vw;
        background: burlywood;
        padding: 16px 0 12px 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 0;
        left: 0;
    }

    .brand-logo {
        width: 150px;
        height: auto;
        border-radius: 12px;
    }

    .center-wrapper {
        min-height: calc(100vh - 90px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        max-width: 400px;
        width: 100%;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        padding: 32px 30px 24px 30px;
        text-align: center;
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
    <!-- Logo memanjang di atas, seperti navbar -->
    <div class="logo-navbar d-flex justify-content-center align-items-center mb-4">
        <img src="Resource/Senku kafe.png" alt="Brand Logo" class="brand-logo">
    </div>
    <div class="center-wrapper">
        <div>
            <div class="login-container">
                <h3 class="mb-0">Sign Up</h3>
                <p class="mb-4">Buat Akun Baru Anda!</p>
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
                <form action="daftar.php" method="post" autocomplete="off">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <input type="email" name="email" class="form-control" placeholder="Email (@gmail.com)" required>
                    <input type="password" name="password" class="form-control" placeholder="Password (min 6 karakter)"
                        required>
                    <input type="password" name="confirm" class="form-control" placeholder="Konfirmasi Password"
                        required>
                    <button type="submit" class="btn btn-success w-100 mb-2">Daftar</button>
                </form>
                <div
                    class="register-section d-flex flex-column flex-sm-row justify-content-center align-items-center mt-3">
                    <span class="me-2 mb-1 mb-sm-0">Sudah punya akun?</span>
                    <a href="masuk.php" class="me-2 mb-2 mb-0 register-link text-decoration-none text-primary">Masuk di
                        sini</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>