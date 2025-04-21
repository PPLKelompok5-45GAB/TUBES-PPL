@csrf
<div class="mb-3">
    <label for="book_id" class="form-label">Book</label>
    <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
        <option value="">Select Book</option>
        @foreach($books as $book)
            <option value="{{ $book->id }}" {{ old('book_id', $borrow->book_id ?? '') == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
        @endforeach
    </select>
    @error('book_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="member_id" class="form-label">Member</label>
    <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
        <option value="">Select Member</option>
        @foreach($members as $member)
            <option value="{{ $member->id }}" {{ old('member_id', $borrow->member_id ?? '') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
        @endforeach
    </select>
    @error('member_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="borrow_date" class="form-label">Borrow Date</label>
    <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" id="borrow_date" name="borrow_date" value="{{ old('borrow_date', isset($borrow->borrow_date) ? $borrow->borrow_date->format('Y-m-d') : '') }}" required>
    @error('borrow_date')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Save</button>
