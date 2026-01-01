<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class center_wallets extends Model
{
    protected $fillable = ['center_id', 'balance'];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function transactions()
    {
        return $this->hasMany(center_wallet_transactions::class);
    }
}
