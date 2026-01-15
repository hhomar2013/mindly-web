<?php
namespace App\Livewire\Admins\Countries;

use App\Helpers\WithPreviewHelper;
use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithPreviewHelper;
    use WithFileUploads;
    #[Layout('layouts.app')]
    public $country;
    public $action = 'index', $update = false;
    public $name_ar, $name_en;
    public $image, $old_image;
    public $code;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteCountry' => 'delete'];

    public function updatingAction()
    {
        $this->resetPage();
    }

    public function back()
    {
        $this->reset(['name_ar', 'name_en', 'image', 'country', 'code']);
        $this->action = 'index';
    }

    public function edit($id)
    {
        $this->country = Country::find($id);
        $this->name_ar = $this->country->getTranslation('name', 'ar');
        $this->name_en = $this->country->getTranslation('name', 'en');
        $this->code    = $this->country->code;
        $this->action  = 'create';
        $this->update  = true;
        if ($this->country->image) {
            $this->old_image = $this->country->image;
        }
    }

    public function store()
    {
        $this->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code'    => 'required|string|max:10',
        ]);

        $imageName = $this->image ? $this->image->store('countries', 'public') : null;
        Country::create([
            'name'  => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'code'  => $this->code,
            'image' => $imageName,
        ]);
        $this->reset(['name_ar', 'name_en', 'image', 'country', 'code']);
        $this->action = 'index';
        $this->dispatch('message', message: __('Country created successfully.'));
    }

    public function updateCountry()
    {
        $this->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code'    => 'required|string|max:10',
        ]);

        if ($this->image) {
            $imageName = $this->image->store('countries', 'public');
        } elseif ($this->old_image) {
            $imageName = $this->old_image;
        } else {
            $imageName = null;
        }
        $this->country->update([
            'name'  => ['ar' => $this->name_ar, 'en' => $this->name_en],
            'code'  => $this->code,
            'image' => $imageName,
        ]);
        $this->reset(['name_ar', 'name_en', 'image', 'old_image', 'country', 'code']);
        $this->action = 'index';
        $this->dispatch('message', message: __('Country updated successfully.'));
    }

    public function delete($id)
    {
        $country = Country::find($id);
        if ($country) {
            $country->delete();
            $this->dispatch('message', message: __('Country deleted successfully.'));
        }
    }
    public function render()
    {
        $countries = Country::paginate(10);
        return view('livewire.admins.countries.index', compact('countries'));
    }
}
