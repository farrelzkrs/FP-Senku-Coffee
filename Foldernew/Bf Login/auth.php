<?php
// filepath: c:\laragon\www\FP Pemweb\Bf Login\auth.php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika belum login
    header("Location: masuk.php");
    exit;
}