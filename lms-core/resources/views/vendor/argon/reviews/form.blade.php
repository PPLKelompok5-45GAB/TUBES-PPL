@csrf
<div class="mb-3">
    <label for="rating" class="form-label">Rating</label>
    <select class="form-select" id="rating" name="rating" required>
        <option value="">Select Rating</option>
        @for($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
</div>
<div class="mb-3">
    <label for="comment" class="form-label">Comment</label>
    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
</div>
<input type="hidden" name="book_id" value="">
<input type="hidden" name="member_id" value="">
