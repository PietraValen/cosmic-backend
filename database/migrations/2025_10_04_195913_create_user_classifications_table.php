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
        Schema::create('user_classifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('glitch_id')->constrained('glitches')->onDelete('cascade');
            $table->foreignId('glitch_type_id')->nullable()->constrained('glitch_types')->onDelete('set null');
            $table->decimal('confidence', 5, 4)->nullable()->check('confidence >= 0 and confidence <= 1');
            $table->integer('time_spent_seconds')->nullable();
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('glitch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_classifications');
    }
};
