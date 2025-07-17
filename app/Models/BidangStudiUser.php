<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BidangStudiUser extends Pivot
{
    protected $table = 'bidang_studi_user'; 
    protected $fillable = ['user_id', 'bidang_studi_id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bidangStudi()
    {
        return $this->belongsTo(BidangStudi::class);
    }
}
