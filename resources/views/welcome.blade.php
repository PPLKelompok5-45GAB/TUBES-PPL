<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Selamat Datang di Koleksi Buku</h1>
        <p class="lead">Platform sederhana untuk melihat dan mengelola koleksi buku.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Daftar</a>
    </div>
</body>
</html>
