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
        Schema::create('gravitational_wave_events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestampTz('event_date');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('event_type', ['BBH', 'BNS', 'NSBH', 'MassGap', 'Unknown']);
            $table->decimal('mass_1', 10, 2)->nullable();
            $table->decimal('mass_2', 10, 2)->nullable();
            $table->decimal('distance_mpc', 10, 2)->nullable();
            $table->decimal('false_alarm_rate', 20, 10)->nullable();
            $table->text('description')->nullable();
            $table->text('significance')->nullable();
            $table->json('detectors')->nullable();
            $table->string('color')->default('#f59e0b');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gravitational_wave_events');
    }
};
