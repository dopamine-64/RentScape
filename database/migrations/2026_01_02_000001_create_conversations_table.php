<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('tenant_id');
            $table->boolean('pinned_by_owner')->default(false);
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete();
            $table->foreign('owner_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('tenant_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unique(['property_id', 'tenant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};