<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // relasi ke courses
            $table->string('judul');                      // = judul_materi di ERD
            $table->string('file_materi')->nullable();    // dari ERD (path file)
            $table->text('konten')->nullable();           // dipakai PengajarController (konten teks)
            $table->date('tanggal_diunggah')->nullable(); // dari ERD
            $table->integer('urutan')->default(0);        // dipakai PengajarController
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
