<?php
session_start();
include '../koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: masuk.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Ambil data user
$stmt = $conn->prepare("SELECT username, email, photo_profil FROM users WHERE id_users=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $foto);
$stmt->fetch();
$stmt->close();

$success = '';
$error = '';
$edit_mode = isset($_GET['edit']) && $_GET['edit'] === '1';

// Proses edit profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_profil'])) {
    $new_username = trim($_POST['username']);
    $foto_name = $foto; // default

    // Handle upload foto profil jika ada
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $foto_name = uniqid('profil_') . '.' . $ext;
            move_uploaded_file($_FILES['photo_profil']['tmp_name'], "../uploads/" . $foto_name);
            // Hapus foto lama jika ada dan bukan default
            if ($foto && file_exists("../uploads/" . $foto)) {
                unlink("../uploads/" . $foto);
            }
        } else {
            $error = "Format foto harus jpg, jpeg, png, atau gif!";
        }
    }

    if ($new_username && !$error) {
        $stmt = $conn->prepare("UPDATE users SET username=?, photo_profil=? WHERE id_users=?");
        $stmt->bind_param("ssi", $new_username, $foto_name, $user_id);
        if ($stmt->execute()) {
            $success = "Profil berhasil diupdate.";
            $_SESSION['username'] = $new_username;
            $_SESSION['foto_profil'] = $foto_name; // Tambahkan baris ini!
            $username = $new_username;
            $foto = $foto_name;
        } else {
            $error = "Gagal update profil.";
        }
        $stmt->close();
    } elseif (!$error) {
        $error = "Username tidak boleh kosong.";
    }
}

// Proses request ganti password (kirim pesan ke admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_pass'])) {
    $pesan = "User <b>$username</b> ($email) mengajukan permintaan ganti password.";
    $kode = strtoupper(bin2hex(random_bytes(3))); // kode 6 karakter
    $stmt = $conn->prepare("INSERT INTO notifpesan (user_id, pesan, kode, tipe, status) VALUES (?, ?, ?, 'ganti_password', 'pending')");
    $stmt->bind_param("iss", $user_id, $pesan, $kode);
    if ($stmt->execute()) {
        $success = "Permintaan ganti password dikirim ke admin. Silakan tunggu persetujuan admin di menu Pesan.";
    } else {
        $error = "Gagal mengirim permintaan.";
    }
    $stmt->close();
}

// Proses request hapus akun (kirim kode ke notifpesan)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_delete'])) {
    $pesan = "User <b>$username</b> ($email) mengajukan permintaan hapus akun.";
    $kode = strtoupper(bin2hex(random_bytes(3))); // kode 6 karakter
    $stmt = $conn->prepare("INSERT INTO notifpesan (user_id, pesan, kode, tipe, status) VALUES (?, ?, ?, 'hapus_akun', 'pending')");
    $stmt->bind_param("iss", $user_id, $pesan, $kode);
    if ($stmt->execute()) {
        $success = "Kode verifikasi hapus akun: <b>$kode</b> (cek menu Pesan)";
    } else {
        $error = "Gagal mengirim permintaan.";
    }
    $stmt->close();
}

// Ambil foto profil
$foto_profil = isset($_SESSION['foto_profil']) && $_SESSION['foto_profil'] ? '../uploads/' . $_SESSION['foto_profil'] : '../Resource/fotoprofil2.jpg';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <title>profil - Senku Coffee</title>
    <style>
body {
            background: #f7f7f7;
        }
        .profil-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 90px;
            margin-bottom: 30px;
        }
        .profil-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #28a745;
        }
        .profil-main {
            max-width: 700px;
            margin: 0 auto 40px auto;
            background: none;
            border-radius: 0;
            box-shadow: none;
            padding: 0 10px;
        }
        .profil-section {
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .profil-section:last-child {
            border-bottom: none;
        }
        .profil-label {
            font-weight: bold;
        }
        .profil-btn {
            margin-top: 10px;
        }
        .alert {
            max-width: 700px;
            margin: 0 auto 20px auto;
        }
    </style>
</head>

<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbarprofil.php'; ?>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success py-2 text-center"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger py-2 text-center"><?= $error ?></div>
    <?php endif; ?>

    <div class="profil-main">
        <div class="profil-header">
            <img src="<?= htmlspecialchars($foto_profil) . '?v=' . time() ?>" alt="Foto Profil" class="profil-img">
            <div>
                <h3 class="mb-1"><?= htmlspecialchars($username) ?></h3>
                <div class="text-muted"><?= htmlspecialchars($email) ?></div>
            </div>
            <?php if (!$edit_mode): ?>
        <a href="?edit=1" class="btn btn-outline-primary ms-auto">Edit Profil</a>
    <?php endif; ?>
        </div>

        <!-- Edit Profil -->
        <?php if ($edit_mode): ?>
<div class="profil-section">
    <h5>Edit Profil</h5>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-2">
            <label class="profil-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
        </div>
        <div class="mb-2">
            <label class="profil-label">Foto Profil</label>
            <input type="file" name="photo_profil" class="form-control" accept="image/*">
        </div>
        <button type="submit" name="edit_profil" class="btn btn-success profil-btn">Simpan Perubahan</button>
        <a href="profil.php" class="btn btn-secondary profil-btn ms-2">Batal</a>
    </form>
</div>
<?php endif; ?>

        <!-- Ganti Password -->
        <div class="profil-section">
            <h5>Ganti Password</h5>
            <form method="post">
                <div class="mb-2 text-muted" style="font-size: 0.95em;">
                    Untuk ganti password, silakan ajukan permintaan ke admin. Jika admin menyetujui, tombol <b>Edit Password</b> akan muncul di menu <b>Pesan</b>.
                </div>
                <button type="submit" name="request_pass" class="btn btn-warning profil-btn">Ajukan Ganti Password</button>
            </form>
        </div>

        <!-- Hapus Akun -->
        <div class="profil-section">
            <h5 class="text-danger">Hapus Akun</h5>
            <form method="post">
                <div class="mb-2 text-muted" style="font-size: 0.95em;">
                    Untuk menghapus akun, ajukan permintaan dan masukkan kode verifikasi yang dikirim ke menu <b>Pesan</b>.
                </div>
                <button type="submit" name="request_delete" class="btn btn-danger profil-btn">Ajukan Hapus Akun</button>
            </form>
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
            style="background:#343a40; color: #fff; border-top: 10px solid #006400;">
            &copy; 2025 Senku Coffee &middot;
            Jl. Kopi No. 123, Jakarta &middot;
            <a href="mailto:info@senkucoffee.com"
                class="link-warning link-underline-opacity-25 link-underline-opacity-100-hover">info@senkucoffee.com</a>
        </footer>
    </div>

    <!-- Modal untuk verifikasi hapus akun -->
    <div class="modal fade" id="verifDeleteModal" tabindex="-1" aria-labelledby="verifDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
                        <iframe src="verifdelete.php" style="border:0;width:400px;height:350px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>