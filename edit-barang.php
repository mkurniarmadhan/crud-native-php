<?php


session_start();
if (!isset($_SESSION['username'])) {
    // Pengguna belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';
// Ambil data barang berdasarkan id
$id = $_GET['id']; // Ambil id barang dari parameter URL atau form sebelumnya

$sql = "SELECT * FROM barang WHERE id = :id"; // Ganti barang dengan nama tabel yang sesuai
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    die("Data barang tidak ditemukan.");
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
    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-6">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Barang</li>
                    </ol>
                </nav>
                <form action="./update_barang.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="<?= $barang['id'] ?>" name="id">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= $barang['nama_barang'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga_barang" class="form-label">Harga Barang</label>
                        <input type="number" class="form-control" id="harga_barang" name="harga_barang" value="<?= $barang['harga_barang'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok_barang" class="form-label">Stok Barang</label>
                        <input type="number" class="form-control" id="stok_barang" name="stok_barang" value="<?= $barang['stok_barang'] ?>" required>
                    </div>
                    <div class="mb-3">

                        <label for="foto_barang">Foto Barang</label>
                        <div class="mb-3">
                            <img src="<?= $barang['foto_barang'] ?>" id="previewFoto" class="img-thumbnail" alt="Preview Foto">
                        </div>
                        <input type="file" class="form-control" id="foto_barang" name="foto_barang" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="status_barang" class="form-label">Status Barang</label>
                        <select class="form-select" id="status_barang" name="status_barang" required>
                            <option value="">Pilih status</option>
                            <option value="tersedia" <?= $barang['status_barang'] === 'tersedia' ? 'selected' : ''  ?>>Tersedia</option>
                            <option value="habis" <?= $barang['status_barang'] === 'habis' ? 'selected' : ''  ?>>Habis</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
        </script>

        <script>
            document.getElementById('foto_barang').addEventListener('change', function(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewFoto').setAttribute('src', e.target.result);
                };
                reader.readAsDataURL(file);
            });
        </script>
</body>

</html>