@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Single Delivery Entry') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('deliveries.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="customer_id" class="col-md-4 col-form-label">{{ __('Customer') }}</label>
                            <div class="col-md-8">
                                <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Select customer') }}</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->customer_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="delivery_date" class="col-md-4 col-form-label">{{ __('Date') }}</label>
                            <div class="col-md-8">
                                <input id="delivery_date" type="date" class="form-control @error('delivery_date') is-invalid @enderror" name="delivery_date" value="{{ old('delivery_date', now()->format('Y-m-d')) }}" required>
                                @error('delivery_date')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_bottles" class="col-md-4 col-form-label">{{ __('New Bottles') }}</label>
                            <div class="col-md-8">
                                <input id="new_bottles" type="number" min="0" class="form-control @error('new_bottles') is-invalid @enderror" name="new_bottles" value="{{ old('new_bottles', 0) }}" required>
                                @error('new_bottles')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="returned_bottles" class="col-md-4 col-form-label">{{ __('Returned Bottles') }}</label>
                            <div class="col-md-8">
                                <input id="returned_bottles" type="number" min="0" class="form-control @error('returned_bottles') is-invalid @enderror" name="returned_bottles" value="{{ old('returned_bottles', 0) }}" required>
                                @error('returned_bottles')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="notes" class="col-md-4 col-form-label">{{ __('Notes') }}</label>
                            <div class="col-md-8">
                                <input id="notes" type="text" class="form-control" name="notes" value="{{ old('notes') }}">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                <a href="{{ route('deliveries.index') }}" class="btn btn-link">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
