<?php
// filepath: c:\laragon\www\FP Pemweb\logout.php
session_start();
session_unset();
session_destroy();
header("Location: home.php");
exit;