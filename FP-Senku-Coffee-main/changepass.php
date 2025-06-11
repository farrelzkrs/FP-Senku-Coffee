<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: masuk.php');
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['user']['id'];

    // Ambil password lama dari database
    $stmt = $conn->prepare("SELECT user_password FROM users WHERE id_users = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($old, $db_password)) {
        $error = 'Password lama salah!';
    } elseif (strlen($new) < 6) {
        $error = 'Password baru minimal 6 karakter!';
    } elseif ($new !== $confirm) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE id_users = ?");
        $stmt->bind_param("si", $hash, $user_id);
        if ($stmt->execute()) {
            $success = 'Password berhasil diubah!';
        } else {
            $error = 'Gagal mengubah password.';
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
    <title>Ganti Password - Senku Coffee</title>
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
    </style>
</head>

<body>
    <div class="logo-navbar d-flex justify-content-center align-items-center mb-4">
        <img src="Resource/Senku kafe.png" alt="Brand Logo" class="brand-logo">
    </div>
    <div class="center-wrapper">
        <div>
            <div class="login-container">
                <h3 class="mb-0">Ganti Password</h3>
                <p class="mb-4">Ubah password akun Anda</p>
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
                <form action="changepass.php" method="post" autocomplete="off">
                    <input type="password" name="old_password" class="form-control" placeholder="Password Lama"
                        required>
                    <input type="password" name="new_password" class="form-control" placeholder="Password Baru"
                        required>
                    <input type="password" name="confirm_password" class="form-control"
                        placeholder="Konfirmasi Password Baru" required>
                    <button type="submit" class="btn btn-success w-100 mb-2">Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>