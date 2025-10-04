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
        Schema::create('observatories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('observatory_type', ['optical', 'radio', 'xray', 'gamma', 'infrared']);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('location');
            $table->string('country');
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('color')->default('#10b981');
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observatories');
    }
};
