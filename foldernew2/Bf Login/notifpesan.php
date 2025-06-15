<?php
session_start();
include '../koneksi.php';
$user_id = $_SESSION['user_id'];

$q = $conn->prepare("SELECT id_notif, pesan, tipe, status FROM notifpesan WHERE user_id=? ORDER BY id_notif DESC");
$q->bind_param("i", $user_id);
$q->execute();
$result = $q->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Senku Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            overflow-x: hidden;
            background-color: #f9f9f9;
        }

        .card-notif {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 1.2rem;
            margin-bottom: 1rem;
            background: #ffffff;
        }

        .card-notif .icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }

        .badge-status {
            font-size: 0.85rem;
        }

        .footer {
            margin-top: 3rem;
        }

        /* --- Navbar style tetap --- */
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
            width: auto;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include 'navbarprofil.php'; ?>

    <div class="container mt-5 pt-4">
        <h4 class="mb-4">== Notifikasi & Pesan Anda ==</h4>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card-notif d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i
                            class="bi <?= $row['tipe'] === 'ganti_password' ? 'bi-shield-lock' : 'bi-person-x' ?> icon text-<?= $row['status'] === 'approved' ? 'success' : ($row['status'] === 'rejected' ? 'danger' : 'secondary') ?>"></i>
                        <div>
                            <div><?= htmlspecialchars($row['pesan']) ?></div>
                            <div class="mt-1">
                                <?php if ($row['tipe'] === 'ganti_password' && $row['status'] === 'approved'): ?>
                                    <a href="changepass2.php?id=<?= $row['id_notif'] ?>" class="btn btn-sm btn-warning">Edit
                                        Password</a>
                                <?php elseif ($row['tipe'] === 'hapus_akun' && $row['status'] === 'approved'): ?>
                                    <a href="verifdelete.php?id=<?= $row['id_notif'] ?>" class="btn btn-sm btn-danger">Hapus
                                        Akun</a>
                                <?php else: ?>
                                    <span class="badge badge-status bg-<?=
                                        $row['status'] === 'pending' ? 'secondary' :
                                        ($row['status'] === 'rejected' ? 'danger' : 'success') ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">Belum ada notifikasi.</div>
        <?php endif; ?>
    </div>

    <!-- <footer class="text-center footer">
        <hr>
        <small>&copy; 2025 Senku Coffee &middot; Jl. Kopi No. 123, Jakarta &middot;
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
</body>

</html>