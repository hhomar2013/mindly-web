<?php
namespace App\Livewire\Admins\Tools;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{

    protected $listeners = ['navbarRefresh'=> '$refresh'];


    public function render()
    {
        return view('livewire.admins.tools.navbar');
    }
}
