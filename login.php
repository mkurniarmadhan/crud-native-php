<?php
session_start();

// Jika sudah ada session user_login, arahkan ke halaman lain
if (isset($_SESSION['username'])) {

    // Redirect sesuai role
    if ($_SESSION['role'] == 'admin') {
        header("Location: index.php");
    } elseif ($_SESSION['role'] == 'pelanggan') {
        header("Location: belanja.php");
    }
    exit();
}

// Proses login jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proses validasi username dan password
    // Contoh: Anda dapat menggunakan proses login yang telah Anda buat sebelumnya
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- You can also include your custom CSS file here -->
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Login</h2>
                <form id="loginForm" action="./proses-login.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <p>Belum punya akun ? <a href="./daftar.php">daftar</a></p>
                    <input type="submit" class="btn btn-primary" name="login" value="Login"></input>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>