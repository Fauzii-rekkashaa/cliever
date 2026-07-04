<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'course_id',
        'judul',
        'tipe',
        'file_materi',
        'durasi',
        'konten',
        'tanggal_diunggah',
        'urutan',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
