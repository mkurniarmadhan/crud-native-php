<?php
// update_barang.php

// Pastikan koneksi sudah dibuat
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $stok_barang = $_POST['stok_barang'];
    $status_barang = $_POST['status_barang'];

    // Proses file foto barang jika ada yang diunggah
    $foto_name = $_FILES['foto_barang']['name'];
    $foto_tmp_name = $_FILES['foto_barang']['tmp_name'];
    $foto_directory = './foto-barang/';
    $foto_path = $foto_directory . $foto_name;

    // Jika pengguna mengunggah foto baru, simpan dan update path foto di database
    if ($foto_name) {
        move_uploaded_file($foto_tmp_name, $foto_path);
        $foto_barang = $foto_path;
        // Buat query untuk update data barang termasuk foto barang
        $sql = "UPDATE barang SET nama_barang = :nama_barang, harga_barang = :harga_barang, stok_barang = :stok_barang, status_barang = :status_barang, foto_barang = :foto_barang WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nama_barang' => $nama_barang, 'harga_barang' => $harga_barang, 'stok_barang' => $stok_barang, 'status_barang' => $status_barang, 'foto_barang' => $foto_barang, 'id' => $id]);
    } else {
        // Jika tidak ada foto baru diunggah, update data barang tanpa mengubah foto
        $sql = "UPDATE barang SET nama_barang = :nama_barang, harga_barang = :harga_barang, stok_barang = :stok_barang, status_barang = :status_barang WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nama_barang' => $nama_barang, 'harga_barang' => $harga_barang, 'stok_barang' => $stok_barang, 'status_barang' => $status_barang, 'id' => $id]);
    }
    $_SESSION['success_message'] = "Data barang berhasil disimpan.";
    header("Location: index.php"); // Ganti dengan halaman daftar barang atau halaman lainnya
    exit();
}
