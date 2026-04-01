<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->decimal('kilos', 10, 2);
            $table->decimal('price_per_kilo', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->index('laundry_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_order_items');
    }
};
