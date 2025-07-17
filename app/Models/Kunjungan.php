<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $fillable = [
        'user_id',
        'nama',
        'kelas',
        'lokasi',
        'kategori',
        'judul_buku',
        'jumlah_buku',
        'judul_materi',
        'mata_pelajaran',
        'jumlah_alat',
        'alat',
        'tanggal',
        'tanggal_pengembalian',
        'tipe',
        'role_tujuan',
        'status_verifikasi',
        'ada_kerusakan',
        'jenis_kerusakan',
        'deskripsi_kerusakan',
        'verifikasi_petugas',
        'catatan_petugas',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
