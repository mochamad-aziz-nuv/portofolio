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
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained('ponds')->cascadeOnDelete();
            $table->string('cycle_code')->unique();
            $table->string('species')->default('Udang Vaname');
            $table->date('start_date');
            $table->date('target_harvest_date')->nullable();
            $table->date('actual_harvest_date')->nullable();
            $table->unsignedInteger('initial_population');
            $table->decimal('target_fcr', 4, 2)->default(1.20);
            $table->unsignedInteger('target_size')->default(40);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
