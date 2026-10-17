@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ $customer->name }} ({{ $customer->customer_code }})</h4>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">{{ __('Back to Customers') }}</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>{{ __('Address') }}:</strong> {{ $customer->address }}</div>
                <div class="col-md-3"><strong>{{ __('Phone') }}:</strong> {{ $customer->phone }}</div>
                <div class="col-md-3"><strong>{{ __('Price/Bottle') }}:</strong> Rs. {{ number_format($customer->price_per_bottle, 2) }}</div>
                <div class="col-md-3"><strong>{{ __('Bottle Balance') }}:</strong> {{ $customer->bottle_balance }}</div>
            </div>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
        </div>
    </form>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card text-center"><div class="card-body"><div class="text-muted small">{{ __('Bottles Delivered') }}</div><div class="fs-4">{{ $totalBottles }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-center"><div class="card-body"><div class="text-muted small">{{ __('Bottles Returned') }}</div><div class="fs-4">{{ $totalReturned }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-center"><div class="card-body"><div class="text-muted small">{{ __('Total Billed') }}</div><div class="fs-4">Rs. {{ number_format($totalAmount, 2) }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-center"><div class="card-body"><div class="text-muted small">{{ __('Due') }}</div><div class="fs-4 {{ $due > 0 ? 'text-danger' : 'text-success' }}">Rs. {{ number_format($due, 2) }}</div></div></div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">{{ __('Deliveries') }} - {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('New Bottles') }}</th>
                        <th>{{ __('Returned Bottles') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->delivery_date->format('d-M-Y') }}</td>
                            <td>{{ $delivery->new_bottles }}</td>
                            <td>{{ $delivery->returned_bottles }}</td>
                            <td>Rs. {{ number_format($delivery->price_per_bottle, 2) }}</td>
                            <td>Rs. {{ number_format($delivery->amount, 2) }}</td>
                            <td>{{ $delivery->notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3">{{ __('No deliveries for this month.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">{{ __('Payments') }} - {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d-M-Y') }}</td>
                            <td>Rs. {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-3">{{ __('No payments recorded for this month.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
