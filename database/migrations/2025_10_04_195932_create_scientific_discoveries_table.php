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
        Schema::create('scientific_discoveries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('discovery_date');
            $table->foreignId('related_event_id')->nullable()->constrained('gravitational_wave_events')->onDelete('set null');
            $table->json('researchers')->nullable();
            $table->string('publication_url')->nullable();
            $table->string('significance')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scientific_discoveries');
    }
};
