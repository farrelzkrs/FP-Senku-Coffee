<?php
session_start();
include '../koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Cek user di database (bisa pakai username atau email)
    $stmt = $conn->prepare("SELECT id_users, user_password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id_users, $db_password);
        $stmt->fetch();
        if (password_verify($password, $db_password)) {
            // Hapus user
            $del = $conn->prepare("DELETE FROM users WHERE id_users = ?");
            $del->bind_param("i", $id_users);
            if ($del->execute()) {
                $del->close();
                $stmt->close();
                // Redirect ke halaman login setelah akun terhapus
                header("Location: ../Bf Login/masuk.php?success=Akun berhasil dihapus, silakan login kembali.");
                exit;
            } else {
                $error = "Gagal menghapus akun.";
                $del->close();
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username/email tidak ditemukan!";
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
    <title>Hapus Akun - Senku Coffee</title>
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
            <button class="btn btn-outline-success" onclick="history.back()">
                &larr; Kembali
            </button>
        </header>
    </div>
    <section id="content" class="content-section">
        <div class="login-container">
            <img src="../Resource/Senku kafe.png" alt="Brand Logo" class="brand-logo">
            <h3 class="mb-0">Hapus Akun</h3>
            <p class="mb-4">Apakah Anda Yakin Ingin Hapus Akun Anda?</p>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="hapusakun.php" method="post" autocomplete="off">
                <input type="text" name="username_or_email" class="form-control mb-2" placeholder="Username atau Email" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <button type="submit" class="btn btn-danger w-100 mb-2">Hapus Akun</button>
            </form>
        </div>
    </section>
</body>
</html>