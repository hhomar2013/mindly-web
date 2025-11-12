<?php

namespace App\Livewire\Admins\Subjects;

use App\Models\subjects;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class SubjectIndex extends Component
{
    use WithFileUploads;
    #[Layout('layouts.app')]
    protected $listeners = ['deletesubject' => 'deleteSubject'];
    public $action = 'index';
    public $subject;
    public $name_ar, $name_en, $image, $image_old, $status;
    public $Isedit = false;
    public function render()
    {
        $subjects = subjects::all();
        return view('livewire.admins.subjects.subject-index', compact('subjects'));
    }
    public function create()
    {
        $this->reset(['name_ar', 'name_en', 'image', 'image_old', 'status']);
        $this->action = 'create';
    }
    public function edit($id)
    {
        $this->Isedit = true;
        $this->reset(['name_ar', 'name_en', 'image', 'image_old', 'status']);
        $this->action = 'edit';
        $this->subject = subjects::find($id);
        $this->image_old = $this->subject->image;
        $this->name_ar = $this->subject->getTranslation('name', 'ar');
        $this->name_en = $this->subject->getTranslation('name', 'en');
        $this->status = $this->subject->status;
        
    }
    public function save()
    {

        $this->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);

        if ($this->action == 'create') {
            try {
                if ($this->image) {
                    $image = $this->image->store('subjects');
                } else {
                    $image = null;
                }
                $subject = subjects::create([
                    'name' => [
                        'ar' => $this->name_ar,
                        'en' => $this->name_en,
                    ],
                    'image' => $image ?? null,
                    'status' => $this->status ?? 1,
                ]);
                $this->dispatch('message', message: __('Subject saved successfully'), type: 'success');
            } catch (\Exception $e) {
                $this->dispatch('message', message: __('Subject saved failed'), type: 'error');
            }
        } else {
            if ($this->image) {
                $image = $this->image->store('subjects');
            } else {
                $image = $this->image_old;
            }
            $subject = subjects::find($this->subject->id);
            $subject->update([
                'name' => [
                    'ar' => $this->name_ar,
                    'en' => $this->name_en,
                ],
                'image' => $image ?? null,
                'status' => $this->status ?? 1,
            ]);
        }
        $this->action = 'index';
        $this->dispatch('message', message: __('Subject saved successfully'), type: 'success');
        $this->reset(['name_ar', 'name_en', 'image', 'image_old', 'status']);
    }

    public function deleteSubject($id)
    {
        $subject = subjects::find($id);
        $subject->delete();
        $this->dispatch('message', message: __('Subject deleted successfully'), type: 'success');
    }

    public function back()
    {
        $this->action = 'index';
        $this->reset(['name_ar', 'name_en', 'image', 'image_old', 'status']);
    }
}
