<?php
include '../koneksi.php';

$success = '';
$error = '';

$user_data = ['username' => '', 'email' => ''];
if (isset($_GET['id'])) {
    $id_notif = intval($_GET['id']);
    $q = $conn->prepare("SELECT u.username, u.email FROM notifpesan n JOIN users u ON n.user_id = u.id_users WHERE n.id_notif = ?");
    $q->bind_param("i", $id_notif);
    $q->execute();
    $q->bind_result($uname, $umail);
    if ($q->fetch()) {
        $user_data['username'] = $uname;
        $user_data['email'] = $umail;
    }
    $q->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    if ($username === '' || $email === '' || $new_password === '' || $confirm_new_password === '') {
        $error = 'Semua kolom harus diisi!';
    } elseif ($new_password !== $confirm_new_password) {
        $error = 'Konfirmasi password tidak sama!';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        // Cek user
        $stmt = $conn->prepare("SELECT id_users FROM users WHERE username = ? AND email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bind_result($id_users);
            $stmt->fetch();
            $stmt->close();
            $update = $conn->prepare("UPDATE users SET user_password = ? WHERE id_users = ?");
            $update->bind_param("si", $hash, $id_users);
            if ($update->execute()) {
                $success = 'Password berhasil diganti. Silakan login kembali.';
            } else {
                $error = 'Gagal mengganti password. Coba lagi.';
            }
            $update->close();
        } else {
            $error = 'Username dan email tidak cocok!';
            $stmt->close();
        }
    }
}
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
    <title>Change Password - Senku Coffee</title>
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

.login-container {
    max-width: 400px;
    margin: 20px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    padding: 20px 30px 20px 30px;
    text-align: center;
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
            <h3 class="mb-0">Ganti Password</h3>
            <p class="mb-4">Masukkan data untuk mengganti password Anda.</p>
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
            <form action="" method="post">
                <input type="text" name="username" class="form-control" placeholder="Username" required
                    value="<?= htmlspecialchars($user_data['username']) ?>">
                <input type="email" name="email" class="form-control" placeholder="Email" required
                    value="<?= htmlspecialchars($user_data['email']) ?>" <?= $user_data['email'] ? 'readonly' : '' ?>>
                <input type="password" name="new_password" class="form-control" placeholder="Password Baru" required>
                <input type="password" name="confirm_new_password" class="form-control" placeholder="Konfirmasi Password Baru" required>
                <button type="submit" class="btn btn-warning w-100 mb-2">Ganti Password</button>
            </form>
            <?php if (!isset($_GET['id'])): ?>
            <div class="register-section d-flex flex-column flex-sm-row justify-content-center align-items-center mt-3">
                <span class="me-2 mb-1 mb-sm-0">Sudah ingat password?</span>
                <a href="../Bf Login/masuk.php" class="me-2 mb-2 mb-0 register-link text-decoration-none text-primary">Masuk di sini</a>
            </div>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>