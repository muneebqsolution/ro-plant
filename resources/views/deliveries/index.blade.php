@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('All Deliveries') }}</h4>
        <div>
            <a href="{{ route('deliveries.sheet') }}" class="btn btn-outline-primary">{{ __('Day Sheet') }}</a>
            <a href="{{ route('deliveries.create') }}" class="btn btn-primary">{{ __('Add Entry') }}</a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-4">
                    <select name="customer_id" class="form-select">
                        <option value="">{{ __('All Customers') }}</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->customer_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('New Bottles') }}</th>
                        <th>{{ __('Returned Bottles') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Notes') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->delivery_date->format('d-M-Y') }}</td>
                            <td>{{ $delivery->customer->name }} <span class="text-muted small">({{ $delivery->customer->customer_code }})</span></td>
                            <td>{{ $delivery->new_bottles }}</td>
                            <td>{{ $delivery->returned_bottles }}</td>
                            <td>Rs. {{ number_format($delivery->price_per_bottle, 2) }}</td>
                            <td>Rs. {{ number_format($delivery->amount, 2) }}</td>
                            <td>{{ $delivery->notes }}</td>
                            <td class="text-end">
                                <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                                <form action="{{ route('deliveries.destroy', $delivery) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this delivery entry?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-3">{{ __('No deliveries found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $deliveries->links() }}
    </div>
</div>
@endsection
