<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';

    protected $fillable = [
        'enrollment_id',
        'tanggal_terbit',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
