<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->enum('tipe', ['video', 'file', 'teks'])->default('teks')->after('judul');
            $table->string('durasi')->nullable()->after('file_materi'); // contoh: "15 min" atau "1.6 MB"
        });
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'durasi']);
        });
    }
};
