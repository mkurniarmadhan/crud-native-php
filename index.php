<?php


session_start();
if (!isset($_SESSION['username'])) {
    // Pengguna belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}


require_once 'koneksi.php';

$sql = "SELECT * FROM barang";
$stmt = $pdo->query($sql);
$dataBarang = $stmt->fetchAll(PDO::FETCH_ASSOC);



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];

    try {
        // Buat query untuk menghapus data berdasarkan id
        $sql = "DELETE FROM barang WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        // Redirect ke halaman atau beri pesan sukses
        echo "Data berhasil dihapus.";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        // Tangkap kesalahan jika query gagal
        echo "Error: " . $e->getMessage();
    }
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
                <h1>Selamat Datang </h1>
            </div>
            <div class="">
                <a href="./logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <!-- create list of books -->
        <div class="row mt-5">
            <div class="col-12 d-flex">
                <h2>Daftar Barang</h2>
                <a class="btn btn-success ms-auto" href="./tambah-barang.php">Tambah Data</a>
            </div>

            <div class="col-12 mt-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga Barang</th>
                            <th>Stok Barang</th>
                            <th>Status </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($dataBarang)) : ?>
                            <?php foreach ($dataBarang as $barang) : ?>

                                <tr>
                                    <td>
                                        <?php if ($barang['foto_barang']) : ?>
                                            <img src="<?php echo $barang['foto_barang']; ?>" width="100" height="100" alt="Foto Barang">
                                        <?php else : ?>
                                            Tidak ada foto
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $barang['nama_barang'] ?></td>
                                    <td><?= $barang['harga_barang'] ?></td>
                                    <td><?= $barang['stok_barang'] ?></td>
                                    <td><?= $barang['status_barang'] ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="./edit-barang.php?id=<?= $barang['id']; ?>" class="btn btn-sm btn-warning me-2">Edit</a>

                                            <form method="post">

                                                <input type="hidden" name="id" value="<?= $barang['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr><?php endforeach; ?>
                        <?php else : ?>
                            <div class="col">
                                <p>Tidak ada data barang.</p>
                            </div>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>