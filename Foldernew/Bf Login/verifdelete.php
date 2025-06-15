<?php
// filepath: c:\laragon\www\FP Pemweb\Bf Login\verifdelete.php
session_start();
include '../koneksi.php';

// Ambil id_notif dari GET (dari notifpesan.php)
$kode = '';
if (isset($_GET['id'])) {
    $id_notif = intval($_GET['id']);
    // Ambil kode verifikasi dari notifpesan
    $stmt = $conn->prepare("SELECT kode FROM notifpesan WHERE id_notif=? AND tipe='hapus_akun' AND status='approved' AND user_id=?");
    $stmt->bind_param("ii", $id_notif, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($kode);
    $stmt->fetch();
    $stmt->close();
}

// Jika user submit form hapus akun
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode'])) {
    $input_kode = trim($_POST['kode']);
    // Cek kode di notifpesan
    $stmt = $conn->prepare("SELECT id_notif FROM notifpesan WHERE user_id=? AND tipe='hapus_akun' AND status='approved' AND kode=?");
    $stmt->bind_param("is", $_SESSION['user_id'], $input_kode);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        // Hapus akun user
        $stmt->close();
        $del = $conn->prepare("DELETE FROM users WHERE id_users=?");
        $del->bind_param("i", $_SESSION['user_id']);
        if ($del->execute()) {
            session_destroy();
            header("Location: ../Bf Login/masuk.php?msg=akun_terhapus");
            exit;
        } else {
            $error = "Gagal menghapus akun. Coba lagi.";
        }
        $del->close();
    } else {
        $error = "Kode verifikasi salah atau sudah tidak berlaku.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Hapus Akun - Senku Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f7f7f7; }
        .verif-container { max-width: 400px; margin: 90px auto 0 auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 30px; }
    </style>
</head>
<body>
    <div class="verif-container">
        <h4 class="mb-3 text-danger">Verifikasi Hapus Akun</h4>
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="kode" class="form-label">Kode Verifikasi</label>
                <input type="text" name="kode" id="kode" class="form-control" placeholder="Masukkan kode verifikasi" required value="<?= htmlspecialchars($kode) ?>">
                <div class="form-text">Kode verifikasi didapat dari menu Pesan.</div>
            </div>
            <button type="submit" class="btn btn-danger w-100">Hapus Akun</button>
            <a href="profil.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>