@csrf
<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $member->name ?? '') }}" {{ isset($member) ? 'readonly' : '' }}>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $member->email ?? '') }}" {{ isset($member) ? 'readonly' : '' }}>
    @error('email')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $member->phone ?? '') }}">
    @error('phone')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $member->address ?? '') }}">
    @error('address')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
        <option value="active" {{ old('status', $member->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="suspended" {{ old('status', $member->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
        <option value="inactive" {{ old('status', $member->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    @error('status')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="membership_date" class="form-label">Membership Date</label>
    <input type="date" class="form-control @error('membership_date') is-invalid @enderror" id="membership_date" name="membership_date" value="{{ old('membership_date', isset($member) && $member->membership_date ? (is_string($member->membership_date) ? $member->membership_date : $member->membership_date->format('Y-m-d')) : date('Y-m-d')) }}">
    @error('membership_date')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<!-- Save button removed to avoid duplication with modal footer button -->
