<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->date('delivery_date');
            $table->integer('new_bottles')->default(0);
            $table->integer('returned_bottles')->default(0);
            $table->decimal('price_per_bottle', 8, 2);
            $table->decimal('amount', 10, 2);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['customer_id', 'delivery_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
