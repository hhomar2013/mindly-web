<?php
namespace App\Livewire\Admins\TermsConditions;

use App\Models\TermAndCondition;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TermsConditionsIndex extends Component
{
    public $action = 'index';
    public $content;
    public $termId;
    public $term;
    public $type = 'terms';
    public $termVersion;

    public function mount()
    {
        $this->loadContent();
    }

    public function loadContent()
    {

        $data          = TermAndCondition::current($this->type);
        $this->content = $data ? $data->content : '';

        $this->dispatch('contentChanged', content: $this->content);
    }

    public function updatedType()
    {
        $this->loadContent();
    }

    public function save()
    {
        $this->validate(['content' => 'required|min:10']);

        TermAndCondition::where('type', $this->type)->update(['is_active' => false]);

        TermAndCondition::create([
            'type'      => $this->type,
            'version'   => $this->termVersion,
            'content'   => $this->content,
            'is_active' => true,
        ]);

        $this->termVersion = '';
        $this->content     = '';
        $this->dispatch('clearEditor');
        $this->dispatch('message', message: __('Terms and Conditions saved successfully'));
        $this->loadContent();
    }

    public function activate($id)
    {
        $term = TermAndCondition::find($id);
        if ($term) {
            TermAndCondition::where('type', $term->type)->update(['is_active' => false]);
            $term->is_active = true;
            $term->save();
        }

        $this->dispatch('message', message: __($term->type . ' Version ' . $term->version));
        $this->loadContent();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // $terms = TermAndCondition::all();

        return view('livewire.admins.terms-conditions.terms-conditions-index',
            ['termsList'  => TermAndCondition::where('type', 'terms')->latest()->get(),
                'privacyList' => TermAndCondition::where('type', 'privacy')->latest()->get()]);
    }
}
