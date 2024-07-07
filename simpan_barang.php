<?php
session_start();
require_once 'koneksi.php'; // File koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $stok_barang = $_POST['stok_barang'];
    $status_barang = $_POST['status_barang'];

    // Mengambil informasi file foto barang
    $foto_barang = null;

    if ($_FILES['foto_barang']['size'] > 0) {
        $foto_name = $_FILES['foto_barang']['name'];
        $foto_tmp_name = $_FILES['foto_barang']['tmp_name'];
        $foto_size = $_FILES['foto_barang']['size'];
        $foto_type = $_FILES['foto_barang']['type'];

        // Memeriksa tipe file, misalnya hanya menerima jpeg atau png
        $allowed_types = array('image/jpeg', 'image/png');
        if (in_array($_FILES['foto_barang']['type'], $allowed_types)) {
            // Menyimpan file foto ke direktori yang diinginkan
            $foto_directory = './foto-barang/';
            $foto_path = $foto_directory . $foto_name;
            move_uploaded_file($foto_tmp_name, $foto_path);
            $foto_barang = $foto_path;
        } else {
            echo "Tipe file foto tidak valid. Harap unggah file dengan tipe JPEG atau PNG.";
            exit();
        }
    }

    try {
        // Insert data barang ke dalam database
        $sql = "INSERT INTO barang (nama_barang, harga_barang, stok_barang, foto_barang, status_barang) 
                VALUES (:nama_barang, :harga_barang, :stok_barang, :foto_barang, :status_barang)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama_barang' => $nama_barang,
            'harga_barang' => $harga_barang,
            'stok_barang' => $stok_barang,
            'foto_barang' => $foto_barang,
            'status_barang' => $status_barang
        ]);

        // Redirect ke halaman sukses atau beri pesan sukses
        $_SESSION['success_message'] = "Data barang berhasil disimpan.";
        header("Location: index.php"); // Ganti dengan halaman daftar barang atau halaman lainnya
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
