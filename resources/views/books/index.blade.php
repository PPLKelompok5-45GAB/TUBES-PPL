@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Buku</h3>
    <a href="{{ route('books.create') }}" class="btn btn-success">+ Tambah Buku</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Kategori</th>
            <th>Tahun</th>
            <th>Ringkasan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($books as $book)
        <tr>
            <td>{{ $book->title }}</td>
            <td>{{ $book->author }}</td>
            <td>{{ $book->category }}</td>
            <td>{{ $book->year }}</td>
            <td>{{ $book->summary }}</td>
            <td>
                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Yakin ingin hapus?')" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">Tidak ada buku</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
