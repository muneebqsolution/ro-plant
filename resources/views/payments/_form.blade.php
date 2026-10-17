@csrf

<div class="row mb-3">
    <label for="customer_id" class="col-md-4 col-form-label">{{ __('Customer') }}</label>
    <div class="col-md-8">
        <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required {{ isset($payment) ? 'disabled' : '' }}>
            <option value="">{{ __('Select customer') }}</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customer_id', $payment->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }} ({{ $customer->customer_code }})
                </option>
            @endforeach
        </select>
        @if (isset($payment))
            <input type="hidden" name="customer_id" value="{{ $payment->customer_id }}">
        @endif
        @error('customer_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="for_month" class="col-md-4 col-form-label">{{ __('For Month') }}</label>
    <div class="col-md-8">
        <input id="for_month" type="month" class="form-control @error('for_month') is-invalid @enderror" name="for_month" value="{{ old('for_month', $payment->for_month ?? now()->format('Y-m')) }}" required>
        @error('for_month')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="payment_date" class="col-md-4 col-form-label">{{ __('Payment Date') }}</label>
    <div class="col-md-8">
        <input id="payment_date" type="date" class="form-control @error('payment_date') is-invalid @enderror" name="payment_date" value="{{ old('payment_date', isset($payment) ? $payment->payment_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('payment_date')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="amount" class="col-md-4 col-form-label">{{ __('Amount (PKR)') }}</label>
    <div class="col-md-8">
        <input id="amount" type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $payment->amount ?? '') }}" required>
        @error('amount')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-3">
    <label for="notes" class="col-md-4 col-form-label">{{ __('Notes') }}</label>
    <div class="col-md-8">
        <input id="notes" type="text" class="form-control" name="notes" value="{{ old('notes', $payment->notes ?? '') }}">
    </div>
</div>

<div class="row mb-0">
    <div class="col-md-8 offset-md-4">
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('payments.index') }}" class="btn btn-link">{{ __('Cancel') }}</a>
    </div>
</div>
