<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'user_id', 'judul', 'deskripsi',
        'tanggal_dibuat', 'thumbnail', 'status',
    ];

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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper: rata-rata rating course ini
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // Helper: jumlah review
    public function totalReviews()
    {
        return $this->reviews()->count();
    }
}
