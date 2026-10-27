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
        Schema::create('ponds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('pond_type', ['hdpe', 'concrete', 'earthen'])->default('hdpe');
            $table->decimal('length_m', 8, 2)->nullable();
            $table->decimal('width_m', 8, 2)->nullable();
            $table->decimal('depth_m', 8, 2)->default(1.20);
            $table->decimal('area_sqm', 10, 2);
            $table->decimal('volume_m3', 10, 2)->nullable();
            $table->enum('status', ['active', 'preparation', 'maintenance', 'harvested'])->default('active');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ponds');
    }
};
