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
        Schema::create('glitches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detector_id')->constrained('detectors')->onDelete('cascade');
            $table->foreignId('glitch_type_id')->nullable()->constrained('glitch_types')->onDelete('set null');
            $table->timestampTz('timestamp');
            $table->decimal('peak_frequency', 10, 2)->nullable();
            $table->decimal('snr', 10, 4)->nullable();
            $table->decimal('duration', 10, 4)->nullable();
            $table->decimal('confidence', 5, 4)->nullable()->check('confidence >= 0 and confidence <= 1');
            $table->enum('classification_method', ['ai', 'human', 'hybrid'])->default('ai');
            $table->string('spectrogram_url')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('validated')->default(false);
            $table->unsignedBigInteger('validated_by')->nullable(); // Se quiser, pode referenciar users padrão
            $table->timestampTz('validated_at')->nullable();
            $table->timestampsTz();

            $table->index('detector_id');
            $table->index('glitch_type_id');
            $table->index(['timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glitches');
    }
};
