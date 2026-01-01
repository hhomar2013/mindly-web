<?php

namespace App\Livewire\Admins\Cities;

use Livewire\Component;
use App\Models\City;
use App\Models\Country;
use App\Models\governor;
use App\Models\Governorate;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use function Livewire\str;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $name = '';
    public $country_id;
    public $governores = [];
    public $citites = [];
    public $city_id;
    public $governors_id;
    public $actions;
    public $governorate_selected;
    public $name_ar, $name_en, $gov_name;
    public $isEdit;
    protected $listeners = ['refreshCities' => '$refresh', 'deleteCity' => 'delete'];

    #[Layout('layouts.app')]
    public function mount()
    {
        $this->resetPage();
    }

    public function createCity()
    {
        $this->isEdit = false;
        $this->actions = true;
        $this->reset(['name_ar', 'name_en']);
        $this->dispatch('refreshCities');
    }

    public function back()
    {
        $this->actions = false;
        $this->reset(['name_ar', 'name_en']);
    }

    public function updating($name, $value)
    {
        $this->resetPage();
    }

    public function getCountry($id)
    {
        $this->country_id = $id;
        $this->getGovernores($id);
        $this->governorate_selected = null;
        $this->resetPage();
    }

    public function getGovernorate($id, $name)
    {
        $this->governorate_selected = $id;
        $this->gov_name = $name;
        $this->getCities($id);
        $this->resetPage();
    }

    public function getCities($id)
    {
        // $city = City::query()->where('governors_id','=',$id)->get();
        $this->governors_id = $id;
        $this->resetPage();
    }

    public function getGovernores($id)
    {
        $gov = governor::query()->where('country_id', $id)->get();
        $this->governores = $gov;
    }

    public function edit($id)
    {
        $this->actions = true;

        $city = City::query()->find($id);
        $this->name_ar = $city->getTranslation('name', 'ar');
        $this->name_en = $city->getTranslation('name', 'en');
        // $this->country_id = $city->country_id;
        // $this->governorate_selected = $city->governors_id;
        $this->governors_id = $city->governors_id;
        $this->isEdit =  $city->id;
    }

    public function save()
    {
        $this->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);
        if ($this->isEdit) {
            $update =  City::query()->find($this->isEdit)->update([
                'name' => [
                    'ar' => $this->name_ar,
                    'en' => $this->name_en,
                ],
            ]);
            if ($update) {
                $this->actions = false;
                $this->reset(['name_ar', 'name_en']);
                $this->dispatch('message', message: __('City created successfully.'));
            }
        } else {
            $city = City::query()->create([
                'name' => [
                    'ar' => $this->name_ar,
                    'en' => $this->name_en,
                ],
                'governors_id' => $this->governors_id,
            ]);
            if ($city) {
                $this->actions = false;
                $this->reset(['name_ar', 'name_en']);
                $this->dispatch('message', message: __('City created successfully.'));
            }
        }
    }

    public function delete($id)
    {
        $delete = City::query()->find($id)->delete();
        if ($delete) {
            $this->reset(['name_ar', 'name_en']);
            $this->dispatch('message', message: __('City deleted successfully.'));
        }
    }

    public function render()
    {
        $countries = Country::all();
        $cities_table = City::query()->where('governors_id', $this->governors_id)->paginate(10);
        // $governores = governor::all();
        return view('livewire.admins.cities.index', compact('countries'), [
            'cities_table' => $cities_table
        ]);
    }
}
