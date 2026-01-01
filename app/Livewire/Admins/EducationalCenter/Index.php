<?php

namespace App\Livewire\Admins\EducationalCenter;

use App\Models\Center;
use App\Models\center_wallet_transactions;
use App\Models\center_wallets;
use App\Models\centerTeacher;
use App\Models\City;
use App\Models\Country;
use App\Models\governor;
use App\Models\Teacher;
use App\Models\teacher_wallets;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $action             = 'index';
    public $isEdit             = false;
    public $name_ar, $name_en, $address, $phone, $logo, $old_logo, $panner, $welcome_message, $main_info;
    public $country_id, $governor_id, $city_id, $center_id, $teachers_in_center, $selected_center, $teacher_id = [];
    protected $listeners = ['deleteCenter' => 'delete', 'deleteTecherInCenter' => 'deleteTecherInCenter'];
    #[Layout('layouts.app')]

    public function mount()
    {
        $this->resetPage();
    }

    public function deleteTecherInCenter($id)
    {
        $delete_techer_in_center = centerTeacher::find($id);
        if ($delete_techer_in_center) {
            $delete_techer_in_center->delete();
            $this->dispatch('message', message: __('Teacher deleted successfully.'));
            $this->showTeachers($this->center_id);
        }
    } //Delete teacher in center
    public function showTeachers($id)
    {
        $this->center_id = $id;
        $this->selected_center = Center::find($this->center_id);
        $query = centerTeacher::query()
            ->where('center_id', $this->center_id)
            ->with([
                'teachers' => function ($query) {
                    $query->whereNotNull('teachers.id');
                },
                'centers' => function ($query) {
                    $query->whereNotNull('centers.id');
                },
            ]);
        $this->teachers_in_center = $query->get();
        $this->action = 'teachers';
    } //Show teachers in center

    public function addTecherToCenter()
    {
        $this->action = 'addTeacher';
        $this->reset('teacher_id');
    } //Add teacher to center

    public function SaveTeacherToCenter()
    {
        $this->validate([
            'teacher_id' => 'required|array',
            'teacher_id.*' => 'exists:teachers,id',
        ]);

        foreach ($this->teacher_id as $tid) {
            centerTeacher::create([
                'center_id' => $this->center_id,
                'teacher_id' => $tid,
            ]);
        }
        $this->reset('teacher_id');
        $this->action = 'teachers';
        $this->dispatch('message', message: __('Teacher added successfully.'));
        $this->showTeachers($this->center_id);
    }


    public function createCenter()
    {
        $this->reset();
        $this->action = 'create';
    } //Create center

    public function save()
    {
        $this->validate([
            'name_ar'         => 'required|string|min:3|max:255',
            'name_en'         => 'required|string|min:3|max:255',
            'address'         => 'required|string|min:5',
            'phone'           => 'required|string|min:8|max:20',
            // 'logo'            => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'city_id'         => 'required|exists:cities,id',
            'welcome_message' => 'nullable|string',
            'main_info'       => 'nullable|string',
        ]);
        $imageName = $this->logo ? $this->logo->store('centers', 'public') : null;
        if ($this->isEdit) {
            $center = Center::find($this->center_id);
            $center->update([
                'name'            => [
                    'ar' => $this->name_ar,
                    'en' => $this->name_en,
                ],
                'address'         => $this->address,
                'phone'           => $this->phone,
                'logo'            => $imageName,
                'panner'          => $this->panner,
                'welcome_message' => $this->welcome_message,
                'main_info'       => $this->main_info,
                'city_id'         => $this->city_id,
                'user_id'         => Auth::id(),
            ]);
            $this->dispatch('message', message: __('Educational Center updated successfully.'));
            $this->reset();
        } else {

            $center =   Center::create([
                'name'            => [
                    'ar' => $this->name_ar,
                    'en' => $this->name_en,
                ],
                'address'         => $this->address,
                'phone'           => $this->phone,
                'logo'            => $imageName,
                'panner'          => $this->panner,
                'welcome_message' => $this->welcome_message,
                'main_info'       => $this->main_info,
                'city_id'         => $this->city_id,
                'user_id'         => Auth::id(),
            ]);
            if ($center) {
                $save_wallet = center_wallets::create([
                    'center_id' => $center->id,
                    'balance'   => 0,
                ]);
                if ($save_wallet) {

                    $save_wallet_transaction = center_wallet_transactions::create([
                        'center_wallet_id' => $save_wallet->id,
                        'center_id' => $center->id,
                        'created_by'   => Auth::id(),
                        'amount'    => 0,
                        'type'      => 'credit',
                        'source'      => 'الرصيد الافتتاحي للمحفظه',
                        'balance_after' => 0,
                    ]);
                    if ($save_wallet_transaction) {
                        $this->dispatch('message', message: __('Educational Center created successfully.'));
                        $this->reset();
                    }
                }
            }
        }

        // Reset form and return to index
        $this->reset();
        $this->action = 'index';
    } //Save center

    public function edit($id)
    {
        $center                = Center::find($id);
        $this->center_id       = $center->id;
        $this->name_ar         = $center->getTranslation('name', 'ar');
        $this->name_en         = $center->getTranslation('name', 'en');
        $this->address         = $center->address;
        $this->phone           = $center->phone;
        $this->old_logo        = $center->logo;
        $this->panner          = $center->panner;
        $this->welcome_message = $center->welcome_message;
        $this->main_info       = $center->main_info;
        $this->city_id         = $center->city_id;

        // Set location data
        $city              = City::find($center->city_id);
        $this->governor_id = $city->governors_id;
        $governor          = governor::find($city->governors_id);
        $this->country_id  = $governor->country_id;

        // Switch to edit mode
        $this->isEdit = true;
        $this->action = 'create';
    }

    public function back()
    {
        $this->isEdit = false;
        $this->action = 'index';
        $this->reset();
    } //Back to index

    public function delete($id)
    {
        $delete_center = Center::find($id);
        if ($delete_center) {
            $delete_center->delete();
            $this->dispatch('message', message: __('Educational Center deleted successfully.'));
        }
    } //Delete center

    public function render()
    {
        $countries    = Country::query()->get();
        $governorates = governor::query()->where('country_id', $this->country_id)->get();
        $cities       = City::query()->where('governors_id', $this->governor_id)->get();
        $centers      = Center::query()->paginate(12);
        $teachers     = Teacher::query()->get();
        return view('livewire.admins.educational-center.index', ['centers' => $centers, 'countries' => $countries, 'governorates' => $governorates, 'cities' => $cities, 'teachers' => $teachers]);
    } //Render
}
