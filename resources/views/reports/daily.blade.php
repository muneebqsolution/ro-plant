@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Daily Delivery Report') }}</h4>
        <button onclick="window.print()" class="btn btn-outline-secondary">{{ __('Print') }}</button>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </div>
    </form>

    <div class="card">
        <div class="card-header">{{ \Carbon\Carbon::parse($date)->format('d-M-Y') }}</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Address') }}</th>
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
                            <td>{{ $delivery->customer->name }} <span class="text-muted small">({{ $delivery->customer->customer_code }})</span></td>
                            <td>{{ $delivery->customer->address }}</td>
                            <td>{{ $delivery->new_bottles }}</td>
                            <td>{{ $delivery->returned_bottles }}</td>
                            <td>Rs. {{ number_format($delivery->price_per_bottle, 2) }}</td>
                            <td>Rs. {{ number_format($delivery->amount, 2) }}</td>
                            <td>{{ $delivery->notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-3">{{ __('No deliveries recorded for this date.') }}</td></tr>
                    @endforelse
                </tbody>
                @if ($deliveries->isNotEmpty())
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2">{{ __('Total') }}</td>
                            <td>{{ $totalNewBottles }}</td>
                            <td>{{ $totalReturnedBottles }}</td>
                            <td></td>
                            <td>Rs. {{ number_format($totalAmount, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
