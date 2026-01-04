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
    public function up()
{
    Schema::create('wishlists', function (Blueprint $table) {
        $table->id();

        // tenant who saved the property
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // property that was wishlisted
        $table->foreignId('property_id')->constrained()->cascadeOnDelete();

        $table->timestamps();

        // prevent duplicate wishlist entries
        $table->unique(['user_id', 'property_id']);
    });
}

};
