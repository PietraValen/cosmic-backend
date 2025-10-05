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
        Schema::create('detectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location')->nullable();
            $table->string('country')->nullable();
            $table->decimal('arm_length_km', 5, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->date('operational_since')->nullable();
            $table->text('description')->nullable();
            $table->string('color')->default('#22d3ee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detectors');
    }
};
