<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'kelas', 
        'role',
        'foto',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function kunjungan()
    {
        return $this->hasMany(\App\Models\Kunjungan::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function bidangStudis()
    {
        return $this->belongsToMany(BidangStudi::class, 'bidang_studi_user', 'user_id', 'bidang_studi_id');
    }

    
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }


}
