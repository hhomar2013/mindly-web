<?php

namespace App\Livewire\Admins\ContentTypes;

use App\Models\ContentType;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;


class Index extends Component
{
    use WithFileUploads;
    public $action = 'index';
    public $types  = ['video', 'image', 'document', 'link', 'quiz'];
    public $type_selected = '';
    public $isEdit = false;
    public $name_ar, $name_en, $icon, $contentTypeId, $old_icon;
    protected $listeners = ['deleteContentType' => 'delete'];
    #[Layout('layouts.app')]

    public function updated($value)
    {

        switch ($value) {
            case 'type_selected':
                $this->type_selected = $this->type_selected;
                break;
        }
    }

    public function back()
    {
        $this->reset();
        $this->action = 'index';
    }
    public function createCT()
    {
        $this->reset();
        $this->isEdit = false;
        $this->action = 'create';
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $this->action = 'create';
        $this->contentTypeId = $id;
        $contentType = ContentType::find($id);
        $this->name_ar = $contentType->getTranslation('name', 'ar');
        $this->name_en = $contentType->getTranslation('name', 'en');
        $this->old_icon = $contentType->icon;
        $this->type_selected = $contentType->type;
    }
    public function save()
    {
        if ($this->isEdit) {
            $this->updateContentType();
        } else {
            $this->createContentType();
        }
    }
    public function createContentType()
    {
        $this->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type_selected' => 'required|in:video,image,document,link,quiz',
        ]);
        $icon = $this->old_icon ? $this->old_icon : $this->icon->store('content-types', 'public');
        ContentType::create([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'icon' => $icon,
            'type' => $this->type_selected,
        ]);
        $this->reset();
        $this->dispatch('message', message: __('Content Type created successfully.'));
    }
    public function updateContentType()
    {
        $this->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type_selected' => 'required|in:video,image,document,link',
        ]);

        if ($this->icon) {
            $icon = $this->icon->store('content-types', 'public');
        } else {
            $icon = $this->old_icon;
        }

        ContentType::where('id', $this->contentTypeId)->update([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'icon' => $icon,
            'type' => $this->type_selected,
        ]);
        $this->reset();
        $this->dispatch('message', message: __('Content Type updated successfully.'));
    }

    public function delete($id)
    {
        $contentType = ContentType::find($id);
        $contentType->delete();
        $this->dispatch('message', message: __('Content Type deleted successfully.'));
    }
    public function render()
    {
        $contentTypes = ContentType::all();
        return view('livewire.admins.content-types.index', ['contentTypes' => $contentTypes]);
    }
}
