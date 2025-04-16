<!DOCTYPE html>
<html>
<head>
    <title>Koleksi Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
    <a class="navbar-brand" href="{{ route('books.index') }}">Koleksi Buku</a>
    @auth
    <div class="ms-auto">
        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
    @endauth
</nav>
<div class="container mt-4">
    @yield('content')
</div>
</body>
</html>
