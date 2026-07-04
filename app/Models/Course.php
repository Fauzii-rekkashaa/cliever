<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'tanggal_dibuat',
        'thumbnail',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function pengajar()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
