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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sekolah', 8)->unique();
            $table->string('nama')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->foreignId('province_id')->nullable()->constrained('indonesia_provinces')->cascadeOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('indonesia_cities')->cascadeOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('indonesia_districts')->cascadeOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('indonesia_villages')->cascadeOnDelete();
            $table->string('kode_pos')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('link_website')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
