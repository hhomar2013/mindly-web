<?php

namespace App\Livewire\Admins\Pdf\CodeList;

use App\Helpers\QrGeneration;
use App\Models\code_list_body;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CodeListPdf extends Component
{
    use QrGeneration;
    public $showCodesList = [];

    public function mount($id)
    {
        $this->showCodesList = code_list_body::query()->where('code_list_head_id', $id)->get();
        foreach ($this->showCodesList as $item) {
            $item->qr_code = $this->generateQrBase64($item->code);
        }
    }


    #[Layout('layouts.pdf')]
    public function render()
    {
        return view('livewire.admins.pdf.code-list.code-list-pdf');
    }
}
