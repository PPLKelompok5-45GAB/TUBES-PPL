<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" id="edit_announcement_title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement->title ?? '') }}" required>
    @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label">Content</label>
    <textarea id="edit_announcement_content" name="content" class="form-control @error('content') is-invalid @enderror" rows="4" required>{{ old('content', $announcement->content ?? '') }}</textarea>
    @error('content')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <select id="edit_announcement_status" name="status" class="form-control @error('status') is-invalid @enderror" required>
        <option value="draft" {{ old('status', $announcement->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ old('status', $announcement->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
    </select>
    @error('status')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Post Date</label>
    <input type="date" id="edit_announcement_post_date" name="post_date" class="form-control @error('post_date') is-invalid @enderror" value="{{ old('post_date', $announcement->post_date ?? '') }}" required>
    @error('post_date')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
