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
        Schema::create('feeding_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('cycles')->cascadeOnDelete();
            $table->string('feed_brand')->default('Megami #2');
            $table->date('feeding_date');
            $table->string('feeding_time'); // e.g. '07:00', '11:00', '15:00', '20:00'
            $table->unsignedInteger('doc'); // Day of Culture (e.g. DOC 24)
            $table->decimal('amount_kg', 6, 2);
            $table->enum('appetite_rate', ['optimal', 'normal', 'low', 'remaining_feed'])->default('optimal');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['cycle_id', 'feeding_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_logs');
    }
};
