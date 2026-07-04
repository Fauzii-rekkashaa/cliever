<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel sertifikat — dari ERD, dihasilkan dari enroll
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade');
            $table->date('tanggal_terbit'); // dari ERD
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
