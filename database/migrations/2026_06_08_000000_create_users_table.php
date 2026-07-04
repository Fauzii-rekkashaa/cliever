<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();               // dari ERD
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'pengajar', 'pelajar'])->default('pelajar');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable(); // dari ERD
            $table->text('deskripsi_pengajar')->nullable();        // dari ERD (menggantikan bio)
            $table->enum('status', ['aktif', 'menunggu', 'ditolak'])->default('aktif'); // dipakai controller
            $table->string('sertifikat')->nullable();              // dipakai AuthController
            $table->string('keahlian')->nullable();                // dipakai AdminController
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
