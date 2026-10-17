@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Outstanding Dues (All Time)') }}</h4>
        <button onclick="window.print()" class="btn btn-outline-secondary">{{ __('Print') }}</button>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Address') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Outstanding Due') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row->customer->name }} <span class="text-muted small">({{ $row->customer->customer_code }})</span></td>
                            <td>{{ $row->customer->address }}</td>
                            <td>{{ $row->customer->phone }}</td>
                            <td class="text-danger">Rs. {{ number_format($row->due, 2) }}</td>
                            <td class="text-end">
                                <a href="{{ route('customers.show', $row->customer) }}" class="btn btn-sm btn-outline-info">{{ __('View Ledger') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-3">{{ __('No outstanding dues.') }}</td></tr>
                    @endforelse
                </tbody>
                @if ($rows->isNotEmpty())
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3">{{ __('Total Outstanding') }}</td>
                            <td>Rs. {{ number_format($totalDue, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
