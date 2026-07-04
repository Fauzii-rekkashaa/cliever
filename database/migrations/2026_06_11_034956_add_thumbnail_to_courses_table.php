<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel enrollments — sesuai ERD entitas "enroll"
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // relasi user mendaftar
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // relasi kursus diikuti
            $table->date('tanggal_daftar')->nullable();                         // dari ERD
            $table->enum('status_penyelesaian', ['belum_selesai', 'selesai'])   // dari ERD
                  ->default('belum_selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
