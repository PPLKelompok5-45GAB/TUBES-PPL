@csrf
<div class="mb-3">
    <label for="category_name" class="form-label">Category Name</label>
    <input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_name" name="category_name" value="{{ old('category_name', $category->category_name ?? '') }}" required>
    @error('category_name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Save</button>
