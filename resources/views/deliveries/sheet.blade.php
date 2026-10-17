@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Daily Delivery Sheet') }}</h4>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </div>
    </form>

    <form method="POST" action="{{ route('deliveries.sheet.store') }}">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th style="width: 110px;">{{ __('New Bottles') }}</th>
                            <th style="width: 110px;">{{ __('Returned Bottles') }}</th>
                            <th>{{ __('Price/Bottle') }}</th>
                            <th>{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $index => $customer)
                            @php $existing = $customer->deliveries->first(); @endphp
                            <tr>
                                <td>
                                    {{ $customer->name }} <span class="text-muted small">({{ $customer->customer_code }})</span>
                                    <input type="hidden" name="rows[{{ $index }}][customer_id]" value="{{ $customer->id }}">
                                </td>
                                <td class="text-muted small">{{ $customer->address }}</td>
                                <td>
                                    <input type="number" min="0" class="form-control form-control-sm" name="rows[{{ $index }}][new_bottles]" value="{{ $existing->new_bottles ?? 0 }}">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control form-control-sm" name="rows[{{ $index }}][returned_bottles]" value="{{ $existing->returned_bottles ?? 0 }}">
                                </td>
                                <td>Rs. {{ number_format($customer->price_per_bottle, 2) }}</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="rows[{{ $index }}][notes]" value="{{ $existing->notes ?? '' }}">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-3">{{ __('No active customers found.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($customers->isNotEmpty())
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">{{ __('Save All') }}</button>
            </div>
        @endif
    </form>
</div>
@endsection
