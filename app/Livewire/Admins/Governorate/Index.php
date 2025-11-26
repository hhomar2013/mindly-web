<?php
namespace App\Livewire\Admins\Governorate;

use App\Models\Country;
use App\Models\governor;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Index extends Component 
{
    use \Livewire\WithPagination;
    use WithFileUploads;

    public $name, $country_id, $image, $governorate_id;
    public $update = false;
    public $action = 'table';
    public $governorate;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshGovernorateComponent' => '$refresh', 'deleteGovernorate'=>'delete'];
    #[Layout('layouts.app')]

    public function switchAction($action, $update = false)
    {
        $this->action = $action;
        $this->update = $update;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->name       = '';
        $this->country_id = '';
        $this->image      = '';
    }

    public function edit($id)
    {
        $this->governorate    = governor::findOrFail($id);
        $this->governorate_id = $this->governorate->id;
        $this->name           = $this->governorate->name;
        $this->country_id     = $this->governorate->country_id;
        $this->update         = true;
        $this->action         = 'create';
    }

    public function store()
    {
        $this->validate([
            'name'       => 'required|string',
            'country_id' => 'required|integer',
        ]);

        governor::updateOrCreate(['id' => $this->governorate_id], [
            'name'       => $this->name,
            'country_id' => $this->country_id,
        ]);
        $this->dispatch('message', message: $this->governorate_id ? __('Governorate updated successfully.') : __('Governorate created successfully.'));
        $this->switchAction('table');
    }

    public function delete($id)
    {
        $gover = governor::find($id);
        if ($gover) {
            $gover->delete();
            $this->dispatch('refreshGovernorateComponent');
            $this->dispatch('message', message: __('Governorate deleted successfully.'));
        }

    }

    public function render()
    {
        $countries = Country::where('status', 1)->get();
        $governors = governor::orderBy('id', 'DESC')->paginate(10);
        return view('livewire.admins.governorate.index', compact(['governors', 'countries']));
    }
}
