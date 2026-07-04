<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriProgress extends Model
{
    protected $table = 'materi_progress';

    protected $fillable = [
        'enrollment_id',
        'materi_id',
        'selesai',
    ];

    protected $casts = [
        'selesai' => 'boolean',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
