<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'address',
        'phone',
        'email',
        'price_per_bottle',
        'bottle_balance',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (empty($customer->customer_code)) {
                $lastId = (int) (static::max('id') ?? 0);
                $customer->customer_code = sprintf('CUST-%04d', $lastId + 1);
            }
        });
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
