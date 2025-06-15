<?php
session_start();
include '../koneksi.php';
$user_id = $_SESSION['user_id'];

// Ambil pesan untuk user ini
$q = $conn->prepare("SELECT id_notif, pesan, tipe, status FROM notifpesan WHERE user_id=? ORDER BY id_notif DESC");
$q->bind_param("i", $user_id);
$q->execute();
$result = $q->get_result();
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
    <title>profil - Senku Coffee</title>
    <style>
        body {
            overflow-x: hidden;
            /* Hilangkan scroll horizontal */
        }

        .nav-link {
            color: rgb(0, 100, 0);
        }

        .nav-link:hover {
            color: rgb(0, 0, 0) !important;
        }

        .nav-link.active {
            color: rgb(0, 0, 0) !important;
            border: 2px solid #28a745;
            border-radius: 5px;
            background-color: beige;
            color: black;
        }

        .navbar {
            border-bottom: 10px solid #006400;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-family: 'Trebuchet MS', Arial, sans-serif;
            font-size: 17px;
            font-weight: bold;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .navbar-nav .nav-item {
            margin-right: 15px;
        }

        .btn-outline-success {
            width: 100px;
            height: 40px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            border: 2px solid #28a745;
            color: #000000;
            background-color: beige;
            transition: all 0.3s ease;
            margin-left: 20px;
        }

        .btn-outline-success:hover {
            background-color: #28a745;
            color: white;
            border-color: #218838;
        }

        .navbar-logo {
            height: 65px;
            /* Sesuaikan tinggi logo dengan tinggi tulisan di navbar */
            width: auto;
            /* Menjaga proporsi gambar */
        }
    </style>
</head>

<body>
    <div class="header">
        <!-- header -->
        <?php include 'navbarprofil.php'; ?>
    </div>

    <div class="container mt-4">
        <h4>Pesan & Notifikasi</h4>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= $row['pesan'] ?></span>
                    <?php if ($row['tipe'] === 'ganti_password' && $row['status'] === 'approved'): ?>
                        <a href="changepass2.php?id=<?= $row['id_notif'] ?>" class="btn btn-sm btn-warning">Edit Password</a>
                    <?php elseif ($row['tipe'] === 'hapus_akun' && $row['status'] === 'approved'): ?>
                        <a href="verifdelete.php?id=<?= $row['id_notif'] ?>" class="btn btn-sm btn-danger">Hapus Akun</a>
                    <?php elseif ($row['status'] === 'pending'): ?>
                        <span class="badge bg-secondary">Menunggu persetujuan admin</span>
                    <?php elseif ($row['status'] === 'rejected'): ?>
                        <span class="badge bg-danger">Ditolak</span>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="penutup">
        <footer class="text-center mb-2 mt-3">
            <hr>
            <small>
                &copy; 2025 Senku Coffee &middot;
                Jl. Kopi No. 123, Jakarta &middot;
                <a href="mailto:info@senkucoffee.com" class="text-decoration-none">info@senkucoffee.com</a>
            </small>
        </footer>
    </div>
</body>

</html>