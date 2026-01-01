<?php

namespace App\Livewire\Admins\TypeOfSubscription;

use App\Models\type_of_subscriptions;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TosIndex extends Component
{

    public $action = 'index';
    public $name_ar, $name_en, $duration = 1;
    public $typeOfSubscriptions;
    public $id, $IsEdit = false;

    protected $listeners = ['deletetypeOfSubscription' => 'delete'];


    public function delete($id)
    {
        $delete = type_of_subscriptions::query()->where('id', $id)->delete();
        if ($delete) {
            $this->dispatch('message', message: __('Subscription Type deleted successfully!'));
            $this->back();
        }
    }

    public function edit($id)
    {
        $this->action = 'create';
        $this->id = $id;
        $this->IsEdit = true;
        $this->mount();
        $tos = type_of_subscriptions::query()->where('id', $id)->first();
        $this->name_ar = $tos->getTranslation('name', 'ar');
        $this->name_en = $tos->getTranslation('name', 'en');
        $this->duration = $tos->duration;
    }

    public function back()
    {
        $this->action = 'index';
        $this->IsEdit = false;
        $this->reset(['name_ar', 'name_en', 'duration']);
        $this->mount();
    }

    public function createTos()
    {
        $this->action = 'create';
        $this->reset(['name_ar', 'name_en', 'duration']);
    }

    public function save()
    {
        $this->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'duration' => 'required',
        ]);
        $save = type_of_subscriptions::query()->updateOrCreate([
            'id' => $this->id,
        ], [
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'duration' => $this->duration,
        ]);
        if ($save) {
            $this->dispatch('message', message: __('Subscription Type created successfully!'));
            $this->back();
        }
    }

    public function mount()
    {
        $this->typeOfSubscriptions = type_of_subscriptions::query()->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {

        return view('livewire.admins.type-of-subscription.tos-index');
    }
}
