<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Content</label>
    <textarea name="content" class="form-control" rows="4" required>{{ old('content', $announcement->content ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-control" required>
        <option value="draft" @if(old('status', $announcement->status ?? '')=='draft')selected @endif>Draft</option>
        <option value="published" @if(old('status', $announcement->status ?? '')=='published')selected @endif>Published</option>
    </select>
</div>
