<?php

namespace App\Livewire\Admins\Teachers\Wallet;

use App\Models\Teacher;
use App\Models\teacher_wallet_transactions;
use App\Models\teacher_wallets;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $action;
    public $teacher_wallet_id;
    public $teacher = [];
    public $amount, $notes = null;
    #[Layout('layouts.app')]


    public function mount()
    {
        $this->resetPage();
    }

    public function showWallet($id)
    {
        $this->teacher_wallet_id = $id;
        $this->action = 'show-wallet';
    }
    public function back()
    {
        $this->action = 'index';
    }
    public function addValueToWllet($id)
    {


        $this->action = 'add-value-to-wallet';
        $teacher = Teacher::query()->with('wallet')->find($id);
        $this->teacher_wallet_id = $teacher->wallet->id;
        $this->teacher = $teacher;
        // dd($teacher);
    }

    public function save()
    {
        $this->validate([
            'amount' => 'required|numeric',
        ]);
        $teacher_wallet = teacher_wallets::query()->find($this->teacher_wallet_id);
        $twt = teacher_wallet_transactions::query()->create([
            'teacher_wallet_id' => $this->teacher_wallet_id,
            'source' => __('Add new balance'),
            'amount' => $this->amount,
            'balance_after' => $teacher_wallet->balance + $this->amount,
            'notes' => $this->notes ?? null,
            'created_by' => Auth::user()->id,
        ]);
        if ($twt) {
            $teacher_wallet->update([
                'balance' => $teacher_wallet->balance + $this->amount,
            ]);
            if ($teacher_wallet) {
                $this->dispatch('message', message: __('Done add balance successfully.'));
                $this->reset(['amount', 'notes']);
                $this->showWallet($this->teacher_wallet_id);
            }
        }
    }

    public function render()
    {
        $transactions = teacher_wallet_transactions::query()
            ->where('teacher_wallet_id', '=', $this->teacher_wallet_id)->orderBy('id', 'desc')->paginate(10);
        $teacher_wallets = teacher_wallets::query()->with('teacher')->get();
        return view('livewire.admins.teachers.wallet.index', compact('teacher_wallets', 'transactions'));
    }
}
