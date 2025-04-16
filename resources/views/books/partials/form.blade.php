<div class="mb-3">
    <label>Judul</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
</div>

<div class="mb-3">
    <label>Pengarang</label>
    <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}" required>
</div>

<div class="mb-3">
    <label>Kategori</label>
    <input type="text" name="category" class="form-control" value="{{ old('category', $book->category) }}">
</div>

<div class="mb-3">
    <label>Tahun Terbit</label>
    <input type="number" name="year" class="form-control" value="{{ old('year', $book->year) }}">
</div>

<div class="mb-3">
    <label>Ringkasan</label>
    <textarea name="summary" class="form-control">{{ old('summary', $book->summary) }}</textarea>
</div>

<button class="btn btn-primary">{{ $book->exists ? 'Update' : 'Simpan' }}</button>
<a href="{{ route('books.index') }}" class="btn btn-secondary">Kembali</a>
