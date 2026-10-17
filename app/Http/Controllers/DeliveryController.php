<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $deliveries = Delivery::with('customer')
            ->when($request->date, fn ($q, $date) => $q->whereDate('delivery_date', $date))
            ->when($request->customer_id, fn ($q, $id) => $q->where('customer_id', $id))
            ->orderByDesc('delivery_date')
            ->paginate(25)
            ->withQueryString();

        $customers = Customer::orderBy('name')->get();

        return view('deliveries.index', compact('deliveries', 'customers'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();

        return view('deliveries.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'delivery_date' => ['required', 'date'],
            'new_bottles' => ['required', 'integer', 'min:0'],
            'returned_bottles' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $customer = Customer::findOrFail($data['customer_id']);

        $this->applyDeliveryRow(
            $customer,
            $data['delivery_date'],
            (int) $data['new_bottles'],
            (int) $data['returned_bottles'],
            $data['notes'] ?? null
        );

        return redirect()->route('deliveries.index')->with('status', 'Delivery saved successfully.');
    }

    public function edit(Delivery $delivery)
    {
        $customers = Customer::orderBy('name')->get();

        return view('deliveries.edit', compact('delivery', 'customers'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $data = $request->validate([
            'delivery_date' => ['required', 'date'],
            'new_bottles' => ['required', 'integer', 'min:0'],
            'returned_bottles' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        // Reverse the old balance effect, then re-apply as if fresh (date may have changed).
        $customer = $delivery->customer;
        $customer->increment('bottle_balance', -($delivery->new_bottles - $delivery->returned_bottles));
        $delivery->delete();

        $this->applyDeliveryRow(
            $customer,
            $data['delivery_date'],
            (int) $data['new_bottles'],
            (int) $data['returned_bottles'],
            $data['notes'] ?? null
        );

        return redirect()->route('deliveries.index')->with('status', 'Delivery updated successfully.');
    }

    public function destroy(Delivery $delivery)
    {
        $delivery->customer->increment('bottle_balance', -($delivery->new_bottles - $delivery->returned_bottles));
        $delivery->delete();

        return redirect()->route('deliveries.index')->with('status', 'Delivery removed successfully.');
    }

    public function sheet(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $customers = Customer::where('status', 'active')
            ->orderBy('name')
            ->with(['deliveries' => fn ($q) => $q->whereDate('delivery_date', $date)])
            ->get();

        return view('deliveries.sheet', compact('customers', 'date'));
    }

    public function storeSheet(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'rows' => ['required', 'array'],
            'rows.*.customer_id' => ['required', 'exists:customers,id'],
            'rows.*.new_bottles' => ['nullable', 'integer', 'min:0'],
            'rows.*.returned_bottles' => ['nullable', 'integer', 'min:0'],
            'rows.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data['rows'] as $row) {
            $customer = Customer::find($row['customer_id']);

            if (! $customer) {
                continue;
            }

            $this->applyDeliveryRow(
                $customer,
                $data['date'],
                (int) ($row['new_bottles'] ?? 0),
                (int) ($row['returned_bottles'] ?? 0),
                $row['notes'] ?? null
            );
        }

        return redirect()->route('deliveries.sheet', ['date' => $data['date']])->with('status', 'Day sheet saved successfully.');
    }

    private function applyDeliveryRow(Customer $customer, string $date, int $newBottles, int $returnedBottles, ?string $notes): void
    {
        $existing = Delivery::where('customer_id', $customer->id)
            ->whereDate('delivery_date', $date)
            ->first();

        if ($newBottles === 0 && $returnedBottles === 0 && empty($notes)) {
            if ($existing) {
                $customer->increment('bottle_balance', -($existing->new_bottles - $existing->returned_bottles));
                $existing->delete();
            }

            return;
        }

        $price = $customer->price_per_bottle;
        $amount = $newBottles * $price;

        if ($existing) {
            $deltaNew = $newBottles - $existing->new_bottles;
            $deltaReturned = $returnedBottles - $existing->returned_bottles;

            $existing->update([
                'new_bottles' => $newBottles,
                'returned_bottles' => $returnedBottles,
                'price_per_bottle' => $price,
                'amount' => $amount,
                'notes' => $notes,
            ]);
        } else {
            $deltaNew = $newBottles;
            $deltaReturned = $returnedBottles;

            Delivery::create([
                'customer_id' => $customer->id,
                'delivery_date' => $date,
                'new_bottles' => $newBottles,
                'returned_bottles' => $returnedBottles,
                'price_per_bottle' => $price,
                'amount' => $amount,
                'notes' => $notes,
            ]);
        }

        $customer->increment('bottle_balance', $deltaNew - $deltaReturned);
    }
}
