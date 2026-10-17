<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $deliveries = Delivery::with('customer')
            ->whereDate('delivery_date', $date)
            ->orderBy('customer_id')
            ->get();

        $totalNewBottles = $deliveries->sum('new_bottles');
        $totalReturnedBottles = $deliveries->sum('returned_bottles');
        $totalAmount = $deliveries->sum('amount');

        return view('reports.daily', compact('deliveries', 'date', 'totalNewBottles', 'totalReturnedBottles', 'totalAmount'));
    }

    public function monthly(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $billed = Delivery::selectRaw('customer_id, SUM(new_bottles) as bottles, SUM(returned_bottles) as returned, SUM(amount) as billed')
            ->whereRaw("DATE_FORMAT(delivery_date, '%Y-%m') = ?", [$month])
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');

        $paid = Payment::selectRaw('customer_id, SUM(amount) as paid')
            ->where('for_month', $month)
            ->groupBy('customer_id')
            ->pluck('paid', 'customer_id');

        $rows = Customer::whereIn('id', $billed->keys())
            ->orderBy('name')
            ->get()
            ->map(function ($customer) use ($billed, $paid) {
                $b = $billed[$customer->id];

                return (object) [
                    'customer' => $customer,
                    'bottles' => $b->bottles,
                    'returned' => $b->returned,
                    'billed' => $b->billed,
                    'paid' => $paid[$customer->id] ?? 0,
                    'due' => $b->billed - ($paid[$customer->id] ?? 0),
                ];
            });

        $totals = (object) [
            'bottles' => $rows->sum('bottles'),
            'returned' => $rows->sum('returned'),
            'billed' => $rows->sum('billed'),
            'paid' => $rows->sum('paid'),
            'due' => $rows->sum('due'),
        ];

        return view('reports.monthly', compact('rows', 'month', 'totals'));
    }

    public function outstanding()
    {
        $billed = Delivery::selectRaw('customer_id, SUM(amount) as billed')
            ->groupBy('customer_id')
            ->pluck('billed', 'customer_id');

        $paid = Payment::selectRaw('customer_id, SUM(amount) as paid')
            ->groupBy('customer_id')
            ->pluck('paid', 'customer_id');

        $rows = Customer::whereIn('id', $billed->keys())
            ->orderBy('name')
            ->get()
            ->map(function ($customer) use ($billed, $paid) {
                $due = ($billed[$customer->id] ?? 0) - ($paid[$customer->id] ?? 0);

                return (object) ['customer' => $customer, 'due' => $due];
            })
            ->filter(fn ($row) => $row->due > 0)
            ->sortByDesc('due')
            ->values();

        $totalDue = $rows->sum('due');

        return view('reports.outstanding', compact('rows', 'totalDue'));
    }

    public function priceList()
    {
        $customers = Customer::orderBy('price_per_bottle')->orderBy('name')->get();

        return view('reports.price-list', compact('customers'));
    }
}
