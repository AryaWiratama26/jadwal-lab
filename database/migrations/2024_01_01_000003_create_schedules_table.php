<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('angkatan', 10);       // 2020, 2021, etc
            $table->string('program', 30);          // Reguler, Ekstensi
            $table->string('kelas', 30);            // TI.20.A.3
            $table->string('hari', 10);             // Senin, Selasa, etc
            $table->string('waktu_mulai', 10);      // 09.30
            $table->string('waktu_selesai', 10);    // 11.30
            $table->string('dosen');                // Nama dosen
            $table->string('ruangan', 30)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
