<?php
session_start();
require_once 'koneksi.php'; // File koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['user_id']; // Ambil ID pengguna dari sesi

    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    try {
        // Periksa apakah barang sudah ada dalam keranjang pengguna
        $stmt_check = $pdo->prepare("SELECT id, jumlah FROM keranjang WHERE id_user = :id_user AND id_barang = :id_barang");
        $stmt_check->bindParam(':id_user', $id_user);
        $stmt_check->bindParam(':id_barang', $id_barang);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            // Jika barang sudah ada dalam keranjang, tambahkan jumlahnya
            $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
            $keranjang_id = $row['id'];
            // Update jumlah barang dalam keranjang
            $stmt_update = $pdo->prepare("UPDATE keranjang SET jumlah = :jumlah WHERE id = :keranjang_id");
            $stmt_update->bindParam(':jumlah', $jumlah);
            $stmt_update->bindParam(':keranjang_id', $keranjang_id);
            $stmt_update->execute();
            echo "Jumlah barang dalam keranjang berhasil diperbarui";
        } else {
            // Jika barang belum ada dalam keranjang, tambahkan baru
            $stmt_insert = $pdo->prepare("INSERT INTO keranjang (id_user, id_barang, jumlah) VALUES (:id_user, :id_barang, :jumlah)");
            $stmt_insert->bindParam(':id_user', $id_user);
            $stmt_insert->bindParam(':id_barang', $id_barang);
            $stmt_insert->bindParam(':jumlah', $jumlah);
            $stmt_insert->execute();
        }




        header("Location: belanja.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
