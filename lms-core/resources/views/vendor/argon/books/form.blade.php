@csrf
<div class="container-fluid py-4">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-2">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title ?? '') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $book->author ?? '') }}" required>
                @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id', $book->category_id ?? '') == $category->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}">
                @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" class="form-control @error('publisher') is-invalid @enderror" id="publisher" name="publisher" value="{{ old('publisher', $book->publisher ?? '') }}">
                @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="publication_year" class="form-label">Publication Year</label>
                <input type="number" class="form-control @error('publication_year') is-invalid @enderror" id="publication_year" name="publication_year" value="{{ old('publication_year', $book->publication_year ?? '') }}">
                @error('publication_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="total_stock" class="form-label">Total Stock</label>
                <input type="number" class="form-control @error('total_stock') is-invalid @enderror" id="total_stock" name="total_stock" value="{{ old('total_stock', $book->total_stock ?? '') }}" required>
                @error('total_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="image" class="form-label">Book Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-2">
                <label for="synopsis" class="form-label">Synopsis</label>
                <textarea class="form-control @error('synopsis') is-invalid @enderror" id="synopsis" name="synopsis" rows="3">{{ old('synopsis', $book->synopsis ?? '') }}</textarea>
                @error('synopsis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        @if(isset($book))
        <input type="hidden" name="buku_id" value="{{ $book->buku_id }}">
        @endif
    </div>
</div>
