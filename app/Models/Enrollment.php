<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'tanggal_daftar',
        'status_penyelesaian',
        'progress',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class);
    }

    public function materiProgress()
    {
        return $this->hasMany(MateriProgress::class);
    }

    // ─── Helper: hitung ulang progress berdasarkan materi selesai ──
    public function recalculateProgress(): void
    {
        $totalMateri = $this->course->materi()->count();

        if ($totalMateri === 0) {
            $this->update(['progress' => 0]);
            return;
        }

        $selesaiCount = $this->materiProgress()->where('selesai', true)->count();
        $percent = (int) round(($selesaiCount / $totalMateri) * 100);

        $this->update([
            'progress' => $percent,
            'status_penyelesaian' => $percent === 100 ? 'selesai' : 'belum_selesai',
        ]);
    }
}
