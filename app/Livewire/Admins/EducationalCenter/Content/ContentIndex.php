<?php

namespace App\Livewire\Admins\EducationalCenter\Content;

use Livewire\Attributes\Layout;
use Livewire\Component;

class ContentIndex extends Component
{
    #[Layout('layouts.app')]        
    public function render()
    {
        return view('livewire.admins.educational-center.content.content-index');
    }
}
