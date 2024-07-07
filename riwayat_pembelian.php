<?php


// Include file functions.php

session_start();
if (!isset($_SESSION['username'])) {
    // Pengguna belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';
// Ambil user_id dari sesi
$id_user = $_SESSION['user_id'];
try {

    // Query untuk mengambil data pembelian berdasarkan ID user
    $stmt = $pdo->prepare("SELECT p.id, p.id_user, p.tanggal_pembelian, p.total_harga, p.status, p.metode_pembayaran,
                                 dp.id_barang, b.nama_barang, dp.jumlah, dp.harga_satuan
                          FROM pembelian p
                          INNER JOIN detail_pembelian dp ON p.id = dp.id_pembelian
                          INNER JOIN barang b ON dp.id_barang = b.id
                          WHERE p.id_user = :id_user
                          ORDER BY p.id");

    // Bind parameter ID user ke dalam query
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

    // Eksekusi query
    $stmt->execute();

    // Inisialisasi array untuk menyimpan data pembelian
    $pembelians = array();

    // Loop untuk membaca setiap baris hasil query
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_pembelian = $row['id'];
        // Jika pembelian belum ada dalam array, tambahkan
        if (!isset($pembelians[$id_pembelian])) {
            $pembelians[$id_pembelian] = array(
                'id' => $id_pembelian,
                'id_user' => $row['id_user'],
                'tanggal_pembelian' => $row['tanggal_pembelian'],
                'total_harga' => $row['total_harga'],
                'status' => $row['status'],
                'metode_pembayaran' => $row['metode_pembayaran'],
                'detail_pembelian' => array()
            );
        }

        // Tambahkan detail pembelian ke dalam array pembelian
        $pembelians[$id_pembelian]['detail_pembelian'][] = array(
            'id_barang' => $row['id_barang'],
            'nama_barang' => $row['nama_barang'],
            'jumlah' => $row['jumlah'],
            'harga_satuan' => $row['harga_satuan']
        );
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <div class="pt-5 d-flex justify-content-between">
            <div class="col">
                <h1>Selamat Datang <?= $_SESSION['username'] ?></h1>

            </div>
            <div class="">
                <a href="./tambah-barang.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <!-- create list of books -->
        <div class="row">
            <div class="col-12">
                <h2>riwayat pembelian </h2>
                <a class="btn btn-success" href="./belanja.php">Belanja lagi </a>
            </div>


            <div class="container mt-5">
                <h2 class="mb-4">Data Pembelian User <?php echo $id_user; ?></h2>

                <!-- Loop untuk menampilkan setiap pembelian -->
                <?php foreach ($pembelians as $pembelian) : ?>
                    <div class="accordion mb-4" id="accordion_<?php echo $pembelian['id']; ?>">
                        <div class="card">
                            <div class="card-header" id="heading_<?php echo $pembelian['id']; ?>">
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $pembelian['id']; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $pembelian['id']; ?>">
                                    <h5 class="card-title mb-0">Pembelian ID: <?php echo $pembelian['id']; ?></h5>
                                </button>
                            </div>

                            <div id="collapse_<?php echo $pembelian['id']; ?>" class="collapse" aria-labelledby="heading_<?php echo $pembelian['id']; ?>" data-bs-parent="#accordion_<?php echo $pembelian['id']; ?>">
                                <div class="card-body">
                                    <p><strong>ID User:</strong> <?php echo $pembelian['id_user']; ?></p>
                                    <p><strong>Tanggal Pembelian:</strong> <?php echo $pembelian['tanggal_pembelian']; ?></p>
                                    <p><strong>Total Harga:</strong> Rp <?php echo number_format($pembelian['total_harga'], 0, ',', '.'); ?></p>
                                    <p><strong>Metode Pembayaran:</strong> <?php echo $pembelian['metode_pembayaran']; ?></p>

                                    <!-- Tabel detail pembelian -->
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th>ID Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pembelian['detail_pembelian'] as $detail) : ?>
                                                <tr>
                                                    <td><?php echo $detail['id_barang']; ?></td>
                                                    <td><?php echo $detail['nama_barang']; ?></td>
                                                    <td><?php echo $detail['jumlah']; ?></td>
                                                    <td>Rp <?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Tampilkan pesan jika tidak ada pembelian -->
                <?php if (empty($pembelians)) : ?>
                    <div class="alert alert-info" role="alert">
                        Tidak ada data pembelian untuk user dengan ID <?php echo $id_user; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>