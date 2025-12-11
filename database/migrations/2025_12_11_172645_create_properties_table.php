<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {   
        // database/migrations/xxxx_xx_xx_create_properties_table.php

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // <-- Add this line if missing
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('property_type')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area')->nullable();
            $table->boolean('parking')->default(0);
            $table->boolean('furnished')->default(0);
            $table->string('laundry')->nullable();
            $table->string('pet_policy')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
