@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Monthly Customer Statement') }}</h4>
        <button onclick="window.print()" class="btn btn-outline-secondary">{{ __('Print') }}</button>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
        </div>
    </form>

    <div class="card">
        <div class="card-header">{{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Bottles Delivered') }}</th>
                        <th>{{ __('Bottles Returned') }}</th>
                        <th>{{ __('Billed') }}</th>
                        <th>{{ __('Paid') }}</th>
                        <th>{{ __('Due') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row->customer->name }} <span class="text-muted small">({{ $row->customer->customer_code }})</span></td>
                            <td>{{ $row->bottles }}</td>
                            <td>{{ $row->returned }}</td>
                            <td>Rs. {{ number_format($row->billed, 2) }}</td>
                            <td>Rs. {{ number_format($row->paid, 2) }}</td>
                            <td class="{{ $row->due > 0 ? 'text-danger' : 'text-success' }}">Rs. {{ number_format($row->due, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-3">{{ __('No activity for this month.') }}</td></tr>
                    @endforelse
                </tbody>
                @if ($rows->isNotEmpty())
                    <tfoot>
                        <tr class="fw-bold">
                            <td>{{ __('Total') }}</td>
                            <td>{{ $totals->bottles }}</td>
                            <td>{{ $totals->returned }}</td>
                            <td>Rs. {{ number_format($totals->billed, 2) }}</td>
                            <td>Rs. {{ number_format($totals->paid, 2) }}</td>
                            <td>Rs. {{ number_format($totals->due, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
