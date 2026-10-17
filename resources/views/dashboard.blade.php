@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">{{ __('Dashboard') }}</h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">{{ __("Today's New Bottles") }}</div>
                    <div class="fs-3">{{ $todayNewBottles }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">{{ __("Today's Returned Bottles") }}</div>
                    <div class="fs-3">{{ $todayReturnedBottles }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">{{ __("Today's Revenue") }}</div>
                    <div class="fs-3">Rs. {{ number_format($todayRevenue, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">{{ __('This Month Revenue') }}</div>
                    <div class="fs-3">Rs. {{ number_format($monthRevenue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('deliveries.sheet') }}" class="btn btn-primary">{{ __('Open Today\'s Day Sheet') }}</a>
        <a href="{{ route('customers.create') }}" class="btn btn-outline-primary">{{ __('Add Customer') }}</a>
        <a href="{{ route('payments.create') }}" class="btn btn-outline-primary">{{ __('Record Payment') }}</a>
    </div>

    <div class="card">
        <div class="card-header">{{ __('Customers with Outstanding Dues') }} - {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Due Amount') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dueCustomers as $customer)
                        <tr>
                            <td>{{ $customer->name }} <span class="text-muted small">({{ $customer->customer_code }})</span></td>
                            <td>{{ $customer->phone }}</td>
                            <td class="text-danger">Rs. {{ number_format($customer->due, 2) }}</td>
                            <td class="text-end">
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info">{{ __('View Ledger') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-3">{{ __('No outstanding dues this month.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
