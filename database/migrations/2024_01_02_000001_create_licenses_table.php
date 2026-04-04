<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('license_key')->unique();
            $table->enum('subscription_type', ['1_month', '6_months', '1_year', 'lifetime'])->default('1_month');
            $table->date('expiration_date')->nullable(); // null for lifetime
            $table->enum('status', ['active', 'expired', 'pending'])->default('pending');
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'status']);
            $table->index('license_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
