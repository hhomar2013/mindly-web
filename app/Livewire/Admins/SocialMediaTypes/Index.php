<?php

namespace App\Livewire\Admins\SocialMediaTypes;

use App\Models\SocialMediaType;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteSocialMediaType' => 'delete'];
    public $action = 'index';
    public $isEdit;
    public $socialMediaTypeId, $name_ar, $name_en, $icon, $old_icon;
    #[Layout('layouts.app')]

    public function createSMT()
    {
        $this->reset();
        $this->action = 'create';
    } //createSMT

    public function save()
    {
        if ($this->isEdit) {
            $this->update();
        } else {
            $this->store();
        }
    } //save include stote and update methods

    public function store()
    {
        $this->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'icon' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $image = $this->icon->store('social-media-types', 'public');
        $save = SocialMediaType::create([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'icon' => $image,
        ]);
        if ($save) {
            $this->dispatch('message', message: __('Social Media Type created successfully.'));
            $this->reset();
            $this->action = 'index';
            $this->isEdit = false;
        }
    } //store method
    public function update()
    {
        $this->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            // 'icon' => 'mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $image = $this->icon ? $this->icon->store('social-media-types', 'public') : $this->old_icon;
        $update = SocialMediaType::find($this->socialMediaTypeId)->update([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'icon' => $image,
        ]);
        if ($update) {
            $this->dispatch('message', message: __('Social Media Type updated successfully.'));
            $this->reset();
            $this->action = 'index';
            $this->isEdit = false;
        }
    } //update method

    public function delete($id)
    {
        SocialMediaType::find($id)->delete();
        $this->dispatch('message', message: __('Social Media Type deleted successfully.'));
        $this->reset();
        $this->action = 'index';
        $this->isEdit = false;
    } //Delete method

    public function edit($id)
    {
        $this->socialMediaTypeId = $id;
        $this->isEdit = true;
        $this->action = 'create';
        $SMT = SocialMediaType::find($id);
        $this->name_ar =  $SMT->getTranslation('name', 'ar');
        $this->name_en = $SMT->getTranslation('name', 'en');
        $this->old_icon = $SMT->icon;
    }//Edit Method

    public function back(){
        $this->reset();
    }

    public function render()
    {
        $socialMediaTypes = SocialMediaType::all();
        return view('livewire.admins.social-media-types.index', ['socialMediaTypes' => $socialMediaTypes]);
    }
}
