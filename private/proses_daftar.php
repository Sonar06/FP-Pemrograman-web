<?php
require_once __DIR__ . "/../config/db.php";

// Tangkap data dari form
$username = $_POST['username'];
$password = $_POST['password'];
$confirm  = $_POST['password_confirm'];
$role     = $_POST['role'];

// Validasi Password
if ($password !== $confirm) {
    header("Location: ../public/daftar.php?pesan=gagal_pass");
    exit;
}

// Validasi Username
$checkQuery = $conn->prepare("SELECT username FROM users WHERE username = ?");
$checkQuery->bind_param("s", $username);
$checkQuery->execute();
$result = $checkQuery->get_result();

if ($result->num_rows > 0) {
    // Gagal jika username sudah dipakai
    header("Location: ../public/daftar.php?pesan=gagal_user");
    exit;
}

// Simpan ke Database
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    session_start();

    // set data user ke dalam sesi
    $_SESSION['username'] = $username;
    $_SESSION['role']     = $role;
    $_SESSION['status']   = "login";

    header("Location: ../public/index.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}

?>
