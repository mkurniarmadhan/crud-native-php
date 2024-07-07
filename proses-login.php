<?php
session_start();
require_once 'koneksi.php'; // File koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Ambil data pengguna berdasarkan username dari database
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Login berhasil, simpan informasi pengguna ke dalam sesi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect sesuai role
                if ($user['role'] == 'admin') {
                    header("Location: index.php");
                } elseif ($user['role'] == 'pelanggan') {
                    header("Location: belanja.php");
                }
                exit();
            } else {
                echo "Password salah.";
            }
        } else {
            echo "Username tidak ditemukan.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
