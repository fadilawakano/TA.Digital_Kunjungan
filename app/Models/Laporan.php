<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_petugas',
        'periode',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
