<?php

namespace App\Services;

use App\Models\teacher_wallet_transactions;
use App\Models\teacher_wallets;
use Illuminate\Support\Facades\DB;
use App\Models\TeacherWallet;
use App\Models\TeacherWalletTransaction;

class TeacherWalletService
{
    /**
     * شحن رصيد (إضافة فلوس)
     */
    public static function credit(teacher_wallets $wallet, float $amount, array $meta = [])
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Amount must be positive");
        }

        return DB::transaction(function () use ($wallet, $amount, $meta) {
            // نقفل الـ row لحد ما العملية تخلص
            $wallet = teacher_wallets::where('id', $wallet->id)->lockForUpdate()->first();

            $newBalance = bcadd($wallet->balance, $amount, 2);

            $tx = teacher_wallet_transactions::create([
                'teacher_wallet_id' => $wallet->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'reference' => $meta['reference'] ?? null,
                'source' => $meta['source'] ?? 'admin_topup',
                'notes' => $meta['notes'] ?? null,
                'created_by' => $meta['created_by'] ?? null,
            ]);

            $wallet->balance = $newBalance;
            $wallet->save();

            return $tx;
        });
    }

    /**
     * خصم رصيد (سحب فلوس)
     */
    public static function debit(teacher_wallets $wallet, float $amount, array $meta = [])
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Amount must be positive");
        }

        return DB::transaction(function () use ($wallet, $amount, $meta) {
            $wallet = teacher_wallets::where('id', $wallet->id)->lockForUpdate()->first();

            if (bccomp($wallet->balance, $amount, 2) < 0) {
                throw new \Exception("Insufficient wallet balance");
            }

            $newBalance = bcsub($wallet->balance, $amount, 2);

            $tx = teacher_wallet_transactions::create([
                'teacher_wallet_id' => $wallet->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'reference' => $meta['reference'] ?? null,
                'source' => $meta['source'] ?? 'service_charge',
                'notes' => $meta['notes'] ?? null,
                'created_by' => $meta['created_by'] ?? null,
            ]);

            $wallet->balance = $newBalance;
            $wallet->save();

            return $tx;
        });
    }
}
