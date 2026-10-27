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
        Schema::create('water_quality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained('ponds')->cascadeOnDelete();
            $table->foreignId('cycle_id')->nullable()->constrained('cycles')->nullOnDelete();
            $table->dateTime('recorded_at');
            $table->decimal('temperature', 4, 2); // °C, misal 29.50
            $table->decimal('ph', 4, 2); // misal 7.80
            $table->decimal('dissolved_oxygen', 4, 2); // DO mg/L, misal 5.80
            $table->decimal('salinity', 4, 2); // ppt, misal 22.00
            $table->decimal('turbidity', 5, 2)->nullable(); // cm secchi disc
            $table->decimal('ammonia', 4, 3)->nullable(); // ppm / mg/L
            $table->decimal('alkalinity', 6, 2)->nullable(); // ppm
            $table->enum('condition_status', ['optimal', 'warning', 'critical'])->default('optimal');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['pond_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_quality_logs');
    }
};
