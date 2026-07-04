<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke users (pengajar membuat)
            $table->string('judul');                                           // = judul_kursus di ERD
            $table->text('deskripsi')->nullable();                             // = deskripsi_kursus di ERD
            $table->date('tanggal_dibuat')->nullable();                        // dari ERD
            $table->string('thumbnail')->nullable();                           // dipakai PengajarController
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu'); // dipakai AdminController
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
