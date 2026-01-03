<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();

            // Property for which rent is paid
            $table->foreignId('property_id')
                  ->constrained('properties')
                  ->cascadeOnDelete();

            // Tenant who paid rent
            $table->foreignId('tenant_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Owner who received rent
            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Rent amount
            $table->decimal('amount', 10, 2);

            // Payment method (virtual wallet)
            $table->enum('payment_method', ['wallet'])
                  ->default('wallet');

            // Status for future extensibility
            $table->enum('status', ['success', 'failed'])
                  ->default('success');

            // Paid timestamp
            $table->timestamp('paid_at')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};