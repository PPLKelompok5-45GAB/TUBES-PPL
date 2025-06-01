@csrf
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            {{-- Only show book and member fields for Admins --}}
            @if(auth()->user()->role === 'Admin')
            <div class="mb-3">
                <label for="book_id" class="form-label">Book</label>
                <div class="d-flex align-items-center">
                    <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required {{ isset($borrow) ? 'disabled' : '' }}>
                        <option value="">Select Book</option>
                        @foreach($books as $bookItem)
                            <option value="{{ $bookItem->book_id }}" {{ old('book_id', isset($book) ? $book->book_id : (isset($borrow) ? $borrow->book_id : '')) == $bookItem->book_id ? 'selected' : '' }}>{{ $bookItem->title }}</option>
                        @endforeach
                    </select>
                    @if(isset($borrow))
                        <input type="hidden" name="book_id" value="{{ $borrow->book_id }}">
                    @endif
                </div>
                @error('book_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="member_id" class="form-label">Member</label>
                <div class="d-flex align-items-center">
                    <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required {{ isset($borrow) ? 'disabled' : '' }}>
                        <option value="">Select Member</option>
                        @foreach($members as $memberItem)
                            <option value="{{ $memberItem->member_id }}" {{ old('member_id', isset($member) ? $member->member_id : (isset($borrow) ? $borrow->member_id : '')) == $memberItem->member_id ? 'selected' : '' }}>{{ $memberItem->name }}</option>
                        @endforeach
                    </select>
                    @if(isset($borrow))
                        <input type="hidden" name="member_id" value="{{ $borrow->member_id }}">
                    @endif
                </div>
                @error('member_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif
            {{-- Always show editable Borrow Date and Due Date for members --}}
            <div class="mb-3">
                <label for="borrow_date" class="form-label">Borrow Date</label>
                <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" id="borrow_date" name="borrow_date" value="{{ old('borrow_date', isset($borrow->borrow_date) ? (is_string($borrow->borrow_date) ? $borrow->borrow_date : $borrow->borrow_date->format('Y-m-d')) : '') }}" required>
                @error('borrow_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', isset($borrow->due_date) ? (is_string($borrow->due_date) ? $borrow->due_date : optional($borrow->due_date)->format('Y-m-d')) : '') }}" required>
                @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            {{-- Status field logic --}}
            @if(auth()->user()->role === 'Admin')
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="pending" {{ old('status', $borrow->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ old('status', $borrow->status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="borrowed" {{ old('status', $borrow->status ?? '') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                    <option value="returned" {{ old('status', $borrow->status ?? '') == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ old('status', $borrow->status ?? '') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="rejected" {{ old('status', $borrow->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @elseif(($borrow->status ?? '') === 'pending')
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="pending" {{ old('status', $borrow->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ old('status', $borrow->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif
        </div>
    </div>
</div>
