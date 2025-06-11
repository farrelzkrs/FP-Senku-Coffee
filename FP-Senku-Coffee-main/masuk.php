<?php
session_start();
include 'koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Login admin statis
    if ($username_or_email === 'admin' && $password === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: Admin/dashboard.php');
        exit;
    }

    // Login user dari database (bisa pakai username atau email)
    $stmt = $conn->prepare("SELECT id_users, username, user_password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id_users, $db_username, $db_password);
        $stmt->fetch();
        if (password_verify($password, $db_password)) {
            $_SESSION['user'] = [
                'id' => $id_users,
                'username' => $db_username
            ];
            $stmt->close();
            header('Location: home.php');
            exit;
        } else {
            $error = 'Username/email atau password salah!';
        }
    } else {
        $error = 'Username/email atau password salah!';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <title>Masuk - Senku Coffee</title>
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
                <h3 class="mb-0">Sign In</h3>
                <p class="mb-4">Masuk Ke Akun Anda!</p>
                <?php if ($error): ?>
                <div class="alert alert-danger py-2 text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                <form action="masuk.php" method="post" autocomplete="off">
                    <input type="text" name="username" class="form-control" placeholder="Username atau Email" required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <button type="submit" class="btn btn-success w-100 mb-2">Masuk</button>
                    <a href="changepass.php" class="forgot-link text-decoration-none">Lupa Password?</a>
                </form>
                <div
                    class="register-section d-flex flex-column flex-sm-row justify-content-center align-items-center mt-3">
                    <span class="me-2 mb-1 mb-sm-0">Belum punya akun?</span>
                    <a href="daftar.php" class="me-2 mb-2 mb-0 register-link text-decoration-none text-primary">Daftar
                        di sini</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>