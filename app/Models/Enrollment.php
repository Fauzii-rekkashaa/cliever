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

    // ─── Relationships ────────────────────────────────────────
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

    public function review()
{
    return $this->hasOne(\App\Models\Review::class);
}

    // ─── Recalculate progress ─────────────────────────────────
    // Cukup update kolom 'progress' saja dari PHP
    // Trigger BEFORE UPDATE di MySQL yang otomatis:
    // - set status_penyelesaian = 'selesai' kalau progress >= 100
    // - set status_penyelesaian = 'belum_selesai' kalau progress < 100
    // Trigger AFTER UPDATE di MySQL yang otomatis:
    // - generate sertifikat kalau status berubah jadi 'selesai'
    public function recalculateProgress(): void
    {
        $totalMateri = $this->course->materi()->count();

        if ($totalMateri === 0) {
            $this->update(['progress' => 0]);
            return;
        }

        $selesaiCount = $this->materiProgress()
            ->where('selesai', true)
            ->count();

        $percent = (int) round(($selesaiCount / $totalMateri) * 100);

        // Hanya update progress — trigger MySQL yang handle status_penyelesaian
        // dan generate sertifikat otomatis
        $this->update(['progress' => $percent]);
    }
}
