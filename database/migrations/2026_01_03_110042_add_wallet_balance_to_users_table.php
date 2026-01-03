<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Virtual wallet balance (TK) for tenants/owners
            // Unsigned to prevent negative balances
            $table->unsignedBigInteger('wallet_balance')
                  ->default(0)
                  ->after('role')
                  ->comment('Wallet balance in TK, tenant default 100000, owner 0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wallet_balance');
        });
    }
};