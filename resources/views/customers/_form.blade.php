@csrf

<div class="row mb-3">
    <label for="name" class="col-md-3 col-form-label">{{ __('Customer Name') }}</label>
    <div class="col-md-9">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $customer->name ?? '') }}" required autofocus>
        @error('name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="address" class="col-md-3 col-form-label">{{ __('Address') }}</label>
    <div class="col-md-9">
        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $customer->address ?? '') }}" required>
        @error('address')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="phone" class="col-md-3 col-form-label">{{ __('Phone Number') }}</label>
    <div class="col-md-9">
        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" required>
        @error('phone')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="email" class="col-md-3 col-form-label">{{ __('Email (optional)') }}</label>
    <div class="col-md-9">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $customer->email ?? '') }}">
        @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="price_per_bottle" class="col-md-3 col-form-label">{{ __('Price per Bottle (PKR)') }}</label>
    <div class="col-md-9">
        <input id="price_per_bottle" type="number" step="0.01" min="0" class="form-control @error('price_per_bottle') is-invalid @enderror" name="price_per_bottle" value="{{ old('price_per_bottle', $customer->price_per_bottle ?? '') }}" required>
        @error('price_per_bottle')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="status" class="col-md-3 col-form-label">{{ __('Status') }}</label>
    <div class="col-md-9">
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $customer->status ?? 'active') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
            <option value="inactive" {{ old('status', $customer->status ?? '') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
        </select>
        @error('status')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-0">
    <div class="col-md-9 offset-md-3">
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('customers.index') }}" class="btn btn-link">{{ __('Cancel') }}</a>
    </div>
</div>
