<?php

namespace App\Livewire\Admins\EducationalCenter\Wallet;

use App\Models\Center;
use App\Models\center_wallet_transactions;
use App\Models\center_wallets;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $action;
    public $center_wallet_id;
    public $center = [];
    public $amount, $notes = null;
    #[Layout('layouts.app')]

    public function mount()
    {
        $this->resetPage();
    }
    public function showWallet($id)
    {
        $this->center_wallet_id = $id;
        $this->action = 'show-wallet';
    }
    public function back()
    {
        $this->action = 'index';
    }
    public function addValueToWllet($id)
    {

        $this->action = 'add-value-to-wallet';
        $center = Center::query()->with('wallet')->find($id);
        $this->center_wallet_id = $center->wallet->id;
        $this->center = $center;
    }

    public function save()
    {
        $this->validate([
            'amount' => 'required|numeric',
        ]);
        $center_wallet = center_wallets::query()->find($this->center_wallet_id);
        $cwt = center_wallet_transactions::query()->create([
            'center_wallet_id' => $this->center_wallet_id,
            'source' => __('Add new balance'),
            'amount' => $this->amount,
            'balance_after' => $center_wallet->balance + $this->amount,
            'notes' => $this->notes ?? null,
            'created_by' => Auth::user()->id,
        ]);
        if ($cwt) {
            $center_wallet->update([
                'balance' => $center_wallet->balance + $this->amount,
            ]);
            if ($center_wallet) {
                $this->dispatch('message', message: __('Done add balance successfully.'));
                $this->reset(['amount', 'notes']);
                $this->showWallet($this->center_wallet_id);
            }
        }
    }

    public function render()
    {
        $transactions = center_wallet_transactions::query()
            ->where('center_wallet_id', '=', $this->center_wallet_id)
            ->with('center_wallet')->orderBy('id', 'desc')->paginate(10);
        $center_wallets = center_wallets::query()->with('center')->get();
        return view('livewire.admins.educational-center.wallet.index', compact('center_wallets', 'transactions'));
    }
}
