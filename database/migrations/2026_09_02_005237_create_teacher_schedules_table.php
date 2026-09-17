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
        Schema::create('teacher_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rombel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_mapel_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('lesson_period_start_id');
            $table->unsignedBigInteger('lesson_period_end_id');
            $table->foreign('lesson_period_start_id')->references('id')->on('lesson_periods')->cascadeOnDelete();
            $table->foreign('lesson_period_end_id')->references('id')->on('lesson_periods')->cascadeOnDelete();
            $table->enum('hari', [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
            ]);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_schedules');
    }
};
