<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('customer_code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Customer::create($data);

        return redirect()->route('customers.index')->with('status', 'Customer added successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $this->validateData($request);

        $customer->update($data);

        return redirect()->route('customers.index')->with('status', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('status', 'Customer deleted successfully.');
    }

    public function show(Request $request, Customer $customer)
    {
        return $this->statement($request, $customer);
    }

    public function statement(Request $request, Customer $customer)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $deliveries = $customer->deliveries()
            ->whereRaw("DATE_FORMAT(delivery_date, '%Y-%m') = ?", [$month])
            ->orderBy('delivery_date')
            ->get();

        $payments = $customer->payments()
            ->where('for_month', $month)
            ->orderBy('payment_date')
            ->get();

        $totalBottles = $deliveries->sum('new_bottles');
        $totalReturned = $deliveries->sum('returned_bottles');
        $totalAmount = $deliveries->sum('amount');
        $totalPaid = $payments->sum('amount');
        $due = $totalAmount - $totalPaid;

        return view('customers.statement', compact(
            'customer', 'deliveries', 'payments', 'month',
            'totalBottles', 'totalReturned', 'totalAmount', 'totalPaid', 'due'
        ));
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'price_per_bottle' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
