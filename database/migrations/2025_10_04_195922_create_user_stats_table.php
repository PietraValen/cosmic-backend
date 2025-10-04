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
        Schema::create('user_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->integer('total_classifications')->default(0);
            $table->integer('correct_classifications')->default(0);
            $table->decimal('accuracy', 5, 4)->default(0);
            $table->integer('points')->default(0);
            $table->integer('level')->default(1);
            $table->json('badges')->nullable();
            $table->integer('streak_days')->default(0);
            $table->timestampTz('last_classification_at')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stats');
    }
};
