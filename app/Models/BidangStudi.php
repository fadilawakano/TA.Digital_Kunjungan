<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidangStudi extends Model
{
    protected $table = 'bidang_studi'; // ✅ nama tabel sebenarnya
    protected $fillable = ['nama'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'bidang_studi_user', 'bidang_studi_id', 'user_id');
    }
}
