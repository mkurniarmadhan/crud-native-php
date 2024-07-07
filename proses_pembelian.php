<?php
session_start();
require_once 'koneksi.php'; // File koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['user_id']; // Ambil ID pengguna dari sesi

    $tanggal_pembelian = date('Y-m-d'); // Tanggal pembelian (contoh: tanggal hari ini)
    $total_harga = $_POST['total_bayar']; // Nilai total harga, bisa dihitung dari keranjang atau diinput secara manual
    $status = "Pending"; // Status pembelian
    $metode_pembayaran = $_POST['metode_pembayaran']; // Metode pembayaran: QRIS atau Tunai (ganti sesuai kebutuhan)
    try {
        // Mulai transaksi PDO
        $pdo->beginTransaction();

        // Insert ke tabel pembelian
        $stmt_pembelian = $pdo->prepare("INSERT INTO pembelian (id_user, tanggal_pembelian, total_harga, status, metode_pembayaran) VALUES (:id_user, :tanggal_pembelian, :total_harga, :status, :metode_pembayaran)");
        $stmt_pembelian->bindParam(':id_user', $id_user);
        $stmt_pembelian->bindParam(':tanggal_pembelian', $tanggal_pembelian);
        $stmt_pembelian->bindParam(':total_harga', $total_harga);
        $stmt_pembelian->bindParam(':status', $status);
        $stmt_pembelian->bindParam(':metode_pembayaran', $metode_pembayaran);
        $stmt_pembelian->execute();
        $id_pembelian = $pdo->lastInsertId(); // Mendapatkan ID pembelian yang baru saja dibuat

        // Ambil barang dari keranjang pengguna
        $stmt_keranjang = $pdo->prepare("SELECT id_barang, jumlah FROM keranjang WHERE id_user = :id_user");
        $stmt_keranjang->bindParam(':id_user', $id_user);
        $stmt_keranjang->execute();

        // Loop untuk setiap barang dalam keranjang
        while ($row = $stmt_keranjang->fetch(PDO::FETCH_ASSOC)) {
            $id_barang = $row['id_barang'];
            $jumlah = $row['jumlah'];

            // Periksa stok barang sekarang
            $stmt_stok = $pdo->prepare("SELECT stok_barang FROM barang WHERE id = :id_barang");
            $stmt_stok->bindParam(':id_barang', $id_barang);
            $stmt_stok->execute();
            $result_stok = $stmt_stok->fetch(PDO::FETCH_ASSOC);
            $stok_sekarang = $result_stok['stok_barang'];

            // Jika stok cukup untuk memenuhi pembelian
            if ($stok_sekarang >= $jumlah) {
                // Ambil harga satuan barang dari tabel barang (asumsi ada tabel barang dengan struktur yang sesuai)
                $stmt_harga_barang = $pdo->prepare("SELECT harga_barang FROM barang WHERE id = :id_barang");
                $stmt_harga_barang->bindParam(':id_barang', $id_barang);
                $stmt_harga_barang->execute();
                $harga_satuan = $stmt_harga_barang->fetchColumn();

                // Insert ke tabel detail_pembelian
                $stmt_detail = $pdo->prepare("INSERT INTO detail_pembelian (id_pembelian, id_barang, jumlah, harga_satuan) VALUES (:id_pembelian, :id_barang, :jumlah, :harga_satuan)");
                $stmt_detail->bindParam(':id_pembelian', $id_pembelian);
                $stmt_detail->bindParam(':id_barang', $id_barang);
                $stmt_detail->bindParam(':jumlah', $jumlah);
                $stmt_detail->bindParam(':harga_satuan', $harga_satuan);
                $stmt_detail->execute();

                // Kurangi stok barang dalam tabel barang
                $stok_baru = $stok_sekarang - $jumlah;
                $habis = 'habis';

                if ($stok_baru <= 0) {
                    $stmt_status_stok = $pdo->prepare("UPDATE barang SET status_barang = :status_barang WHERE id = :id_barang");
                    $stmt_status_stok->bindParam(':status_barang', $habis);
                    $stmt_status_stok->bindParam(':id_barang', $id_barang);
                    $stmt_status_stok->execute();
                }

                $stmt_update_stok = $pdo->prepare("UPDATE barang SET stok_barang = :stok_baru WHERE id = :id_barang");
                $stmt_update_stok->bindParam(':stok_baru', $stok_baru);
                $stmt_update_stok->bindParam(':id_barang', $id_barang);
                $stmt_update_stok->execute();
            } else {
                // Rollback transaksi jika stok tidak mencukupi
                $pdo->rollback();
                die("Stok barang tidak mencukupi untuk barang dengan ID $id_barang");
            }
        }



        // Hapus semua data keranjang setelah pembelian selesai
        $stmt_hapus_keranjang = $pdo->prepare("DELETE FROM keranjang WHERE id_user = :id_user");
        $stmt_hapus_keranjang->bindParam(':id_user', $id_user);
        $stmt_hapus_keranjang->execute();

        // Commit transaksi jika semuanya sukses
        $pdo->commit();

        // riwayat_pembelian
        header("Location: riwayat_pembelian.php");
        exit();
    } catch (PDOException $e) {
        // Rollback transaksi jika terjadi error
        $pdo->rollback();
        die("Error: " . $e->getMessage());
    }
}
