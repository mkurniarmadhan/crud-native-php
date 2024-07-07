<?php
// Include file functions.php

session_start();
if (!isset($_SESSION['username'])) {
    // Pengguna belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

// ambil data barang
$sql = "SELECT * FROM barang";
$stmt = $pdo->query($sql);
$dataBarang = $stmt->fetchAll(PDO::FETCH_ASSOC);


$id_user = $_SESSION['user_id'];


// ambil data kerajang

try {
    // Query untuk mengambil data keranjang barang berdasarkan ID user
    $stmt = $pdo->prepare("SELECT k.id, k.id_barang, b.nama_barang,b.harga_barang, k.jumlah
                           FROM keranjang k
                           INNER JOIN barang b ON k.id_barang = b.id
                           WHERE k.id_user = :id_user");
    $stmt->bindParam(':id_user', $id_user);
    $stmt->execute();
    $keranjang = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
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
    <style>
        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }

        .card-img img {
            padding-top: 10px;
            width: inherit;
            height: 180px;
            object-fit: contain;
            display: block
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="pt-5 d-flex justify-content-between">
            <div class="col">
                <h1>Selamat Datang <?= $_SESSION['username'] ?></h1>
            </div>
            <div class="">
                <a href="./riwayat_pembelian.php" class="btn btn-primary">Riwayat</a>
                <a href="./logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <!-- create list of books -->
        <h2 class="mb-5 text-center">DAFTAR BARANG</h2>
        <div class="row ">



            <div class="col-md-7 col-lg-8">
                <div class="row">
                    <?php if (!empty($dataBarang)) : ?>
                        <?php foreach ($dataBarang as $barang) : ?>
                            <?php if ($barang['status_barang'] != 'habis') : ?>


                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm">
                                        <div class="card-img"> <img src="<?php echo $barang['foto_barang']; ?>" alt=""> </div>
                                        <div class="card-body">
                                            <div class="h3 text-center"><?= $barang['nama_barang'] ?></div>
                                            <div class="d-flex justify-content-between">
                                                <div class="h5 text-center">Rp.<?= $barang['harga_barang'] ?></div>
                                                <div class="h5 text-center">Stok :<?= $barang['stok_barang']; ?></div>

                                            </div>
                                            <?php
                                            // Array untuk menyimpan jumlah barang berdasarkan id_barang
                                            $jumlah_barang_per_id = [];

                                            // Loop melalui array keranjang untuk mengumpulkan jumlah barang berdasarkan id_barang
                                            foreach ($keranjang as $item) {
                                                $id_barang = $item['id_barang'];
                                                $jumlah = $item['jumlah'];

                                                // Jika id_barang belum ada dalam array $jumlah_barang_per_id, inisialisasi dengan nilai 0
                                                if (!isset($jumlah_barang_per_id[$id_barang])) {
                                                    $jumlah_barang_per_id[$id_barang] = 0;
                                                }

                                                // Tambahkan jumlah barang ke dalam array $jumlah_barang_per_id
                                                $jumlah_barang_per_id[$id_barang] += $jumlah;
                                            }

                                            $jumlah = $jumlah_barang_per_id[$barang['id']] ?? 1;

                                            ?>


                                            <form method="POST" action="./proses-tambah-keranjang.php">
                                                <input type="hidden" name="id_barang" value="<?= $barang['id']; ?>">
                                                <p>BELI BARNAG</p>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <label for="jumlah" class="visually-hidden">Password</label>
                                                        <input type="number" value="<?= $jumlah; ?>" class="form-control" id="jumlah" min="1" max="<?= $barang['stok_barang'] ?>" placeholder="Mauskan jumlah" name="jumlah">
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="submit" class="btn btn-primary mb-3">Beli</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div><?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col">
                            <p>Tidak ada data barang.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-5 col-lg-4 order-md-last">

                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Keranjang </span>

                </h4>
                <ul class="list-group mb-3">

                    <?php if (!empty($keranjang)) : ?>
                        <?php foreach ($keranjang as $item) : ?>

                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0"><?= $item['nama_barang'] ?></h6>
                                    <small class="text-body-secondary">Jumlah <?= $item['jumlah'] ?></small>
                                </div>

                                <?php
                                $total_harga = 0;

                                $total_per_item = $item['jumlah'] * $item['harga_barang'];
                                $total_harga += $total_per_item;
                                ?>
                                <span class="text-body-secondary">Rp. <?= number_format($total_per_item, 2, ',', '.') ?> </span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Bayar</span>
                            <strong>Rp. <?= number_format($total_harga, 2, ',', '.') ?> </strong>
                        </li>
                    <?php else : ?>
                        <div class="col card p-2">
                            <p>Tidak ada data barang.</p>
                        </div>
                    <?php endif; ?>

                </ul>
                <?php if (!empty($keranjang)) : ?>
                    <form class="card p-2" method="POST" action="./proses_pembelian.php">
                        <input type="hidden" name="total_bayar" id="total_bayar" value="<?= $total_harga ?? 0 ?>">
                        <div class="input-group">
                            <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                <option selected>Metode Bayar...</option>
                                <option value="QRIS">QRIS</option>
                                <option value="TUNAI">TUNAI</option>
                            </select>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Beli</button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>