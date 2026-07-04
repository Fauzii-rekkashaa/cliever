<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->foreignId('materi_id')->constrained('materi')->onDelete('cascade');
            $table->boolean('selesai')->default(false);
            $table->timestamps();

            $table->unique(['enrollment_id', 'materi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_progress');
    }
};
