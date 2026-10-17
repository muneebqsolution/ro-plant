<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('customer')
            ->when($request->customer_id, fn ($q, $id) => $q->where('customer_id', $id))
            ->when($request->month, fn ($q, $month) => $q->where('for_month', $month))
            ->orderByDesc('payment_date')
            ->paginate(25)
            ->withQueryString();

        $customers = Customer::orderBy('name')->get();

        return view('payments.index', compact('payments', 'customers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('payments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Payment::create($data);

        return redirect()->route('payments.index')->with('status', 'Payment recorded successfully.');
    }

    public function edit(Payment $payment)
    {
        $customers = Customer::orderBy('name')->get();

        return view('payments.edit', compact('payment', 'customers'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $this->validateData($request);

        $payment->update($data);

        return redirect()->route('payments.index')->with('status', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('status', 'Payment removed successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'for_month' => ['required', 'date_format:Y-m'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
