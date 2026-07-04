<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'jenis_kelamin',
        'deskripsi_pengajar',
        'status',
        'sertifikat',
        'keahlian',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ─── Role Helpers ─────────────────────────────────────────
    public function isPelajar(): bool  { return $this->role === 'pelajar'; }
    public function isPengajar(): bool { return $this->role === 'pengajar'; }
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isAktif(): bool    { return $this->status === 'aktif'; }
    public function isMenunggu(): bool { return $this->status === 'menunggu'; }

    // ─── Relationships ────────────────────────────────────────
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
