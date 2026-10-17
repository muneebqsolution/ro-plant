<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $month = now()->format('Y-m');

        $todayDeliveries = Delivery::whereDate('delivery_date', $today)->get();
        $todayNewBottles = $todayDeliveries->sum('new_bottles');
        $todayReturnedBottles = $todayDeliveries->sum('returned_bottles');
        $todayRevenue = $todayDeliveries->sum('amount');

        $monthRevenue = Delivery::whereRaw("DATE_FORMAT(delivery_date, '%Y-%m') = ?", [$month])->sum('amount');

        $billed = Delivery::selectRaw('customer_id, SUM(amount) as total')
            ->whereRaw("DATE_FORMAT(delivery_date, '%Y-%m') = ?", [$month])
            ->groupBy('customer_id')
            ->pluck('total', 'customer_id');

        $paid = Payment::selectRaw('customer_id, SUM(amount) as total')
            ->where('for_month', $month)
            ->groupBy('customer_id')
            ->pluck('total', 'customer_id');

        $dueCustomers = Customer::whereIn('id', $billed->keys())
            ->get()
            ->map(function ($customer) use ($billed, $paid) {
                $customer->due = ($billed[$customer->id] ?? 0) - ($paid[$customer->id] ?? 0);

                return $customer;
            })
            ->filter(fn ($customer) => $customer->due > 0)
            ->sortByDesc('due')
            ->values();

        return view('dashboard', compact(
            'todayNewBottles', 'todayReturnedBottles', 'todayRevenue', 'monthRevenue', 'dueCustomers', 'month'
        ));
    }
}
