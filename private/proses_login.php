<?php
session_start();

require_once __DIR__ . "/../config/db.php";

// tangkap data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// cek data user di tabel
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
--
    // set data user ke sesi
    $_SESSION['username'] = $row['username'];
    $_SESSION['role']     = $row['role']; 
    $_SESSION['status']   = "login";

    header("Location: ../public/index.php");
} else {
    // -- GAGAL LOGIN --
    // Alihkan kembali ke halaman login dengan membawa pesan error
    header("Location: ../public/login.php?pesan=gagal");
}

?>
