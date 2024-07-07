<?php
session_start();
require_once 'koneksi.php'; // File koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'pelanggan';

    // Hash password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert user data into database
        $sql = "INSERT INTO users (nama, alamat, phone, username, password, role) 
                VALUES (:nama, :alamat, :phone, :username, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama' => $nama,
            'alamat' => $alamat,
            'phone' => $phone,
            'username' => $username,
            'password' => $hashed_password,
            'role' => $role
        ]);

        // Redirect ke halaman login atau beri pesan sukses
        $_SESSION['success_message'] = "Registrasi berhasil! Silakan login.";
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
