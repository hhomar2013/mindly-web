<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class teacher_wallet_transactions extends Model
{
    protected $fillable = [
        'teacher_wallet_id','type','amount','balance_after',
        'reference','source','notes','created_by'
    ];

    public function wallet()
    {
        return $this->belongsTo(teacher_wallets::class,'teacher_wallet_id');
    }
}
