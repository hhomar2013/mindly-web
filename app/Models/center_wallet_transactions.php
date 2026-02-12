<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class center_wallet_transactions extends Model
{
    protected $fillable = [
        'center_wallet_id',
        'type',
        'amount',
        'balance_after',
        'reference',
        'source',
        'notes',
        'created_by'
    ];

    public function center_wallet()
    {
        return $this->belongsTo(center_wallets::class, 'center_wallet_id');
    }
}
