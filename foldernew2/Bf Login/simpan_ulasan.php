<?php
include 'auth.php';
$conn = new mysqli("localhost", "root", "", "senku_coffee", "3307");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah user login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}

$user_id = $_SESSION['user_id'];

// Ambil username dari tabel users
$query_user = $conn->prepare("SELECT username FROM users WHERE id_users = ?");
if (!$query_user) {
    die("Prepare failed: " . $conn->error); // tampilkan error query-nya
}
$query_user->bind_param("i", $user_id);
$query_user->execute();
$query_user->bind_result($username);
$query_user->fetch();
$query_user->close();

// Data dari form
$ulasan = $_POST['ulasan'];
$rating = $_POST['rating'];

// Simpan ke tabel ulasan
$sql = "INSERT INTO ulasan (user_id, deskripsi, rating) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query prepare() gagal: " . $conn->error);
}
$stmt->bind_param("isi", $user_id, $ulasan, $rating);



if ($stmt->execute()) {
    echo "<script>
            alert('Ulasan berhasil dikirim!');
            window.location.href = 'ulasan.php';
        </script>";
} else {
    echo "Gagal menyimpan ulasan: " . $conn->error;
}

$stmt->close();
$conn->close();
?>

simpan_ulasan.php