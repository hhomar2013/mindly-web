<?php

namespace App\Livewire\Admins\Teachers;

use App\Models\City;
use App\Models\Country;
use App\Models\governor;
use App\Models\SocialMediaType;
use App\Models\Teacher;
use App\Models\teacher_wallet_transactions;
use App\Models\teacher_wallets;
use App\Models\TeacherSocialMedia;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $action = 'index';
    public $isEdit = false;
    public $teacher_id, $name_ar, $name_en, $address, $phone, $image, $old_image, $description, $in_out = FALSE;
    public $country_id, $governor_id, $city_id;
    public $teacherSMList = [];
    protected $listeners = ['refreshTeachers' => 'render', 'deleteTeacher' => 'delete', 'addTeacherSM' => 'addTeacherSM', 'rateTeacher' => 'saveRating'];

    #[Layout('layouts.app')]

    public function clearRating($id)
    {
        $teacher = Teacher::find($id);
        $teacher->update([
            'rating_system' => 0
        ]);
        $this->dispatch('message', message: __('Rating is deleted successfully!'));
        $this->dispatch('refreshTeachers');
    }

    public function rate($teacherId, $value)
    {
        $teacher = Teacher::find($teacherId);
        $teacher->rating_system = $value;
        $teacher->save();

        $this->dispatch('refreshTeachers');
    }

    #[On('rateTeacher')]
    public function saveRating($id, $rating)
    {
        $teacher = Teacher::find($id);

        $teacher->update([
            'rating_system' => $rating
        ]);

        $this->dispatch('notification', message: 'تم حفظ التقييم');
        $this->dispatch('refreshTeachers');
    }
    // public function addTeacherSM($smt_id)
    // {
    //     $this->teacherSMList = [
    //         'smt_id' => $smt_id,
    //         'link' => ''
    //     ];
    //     $this->dispatch('addTeacherSM', $this->teacherSMList);
    // }
    public function resetForm()
    {
        $this->reset(['name_ar', 'name_en', 'address', 'phone', 'image', 'description', 'country_id', 'governor_id', 'city_id']);
    } //reset form
    public function createTeacher()
    {
        $this->action = 'create';
        $this->isEdit = false;
        $this->resetPage();
        $this->resetForm();
    } //create teacher

    public function save()
    {
        $this->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'address' => 'required',
            'phone' => 'required',
            // 'image' => 'required',
            'country_id' => 'required',
            'governor_id' => 'required',
            'city_id' => 'required',
            // 'description' => 'required',
        ]);

        $imageName = $this->image ? $this->image->store('teachers', 'public') : $this->old_image;
        if ($this->isEdit) {
            $this->update($this->teacher_id, $imageName);
        } else {
            $this->store($imageName);
        }
    } //save method

    public function store($imageName)
    {
        $teacher =  Teacher::create([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en
            ],
            'address' => $this->address,
            'phone' => $this->phone,
            'image' => $imageName,
            'description' => $this->description,
            'city_id' => $this->city_id,
            'user_id' => Auth::id(),
        ]);
        if ($teacher) {
            if ($this->teacherSMList) {
                foreach ($this->teacherSMList as $smt_id => $link) {
                    if (!empty($link)) {
                        TeacherSocialMedia::create([
                            'teacher_id' => $teacher->id,
                            'smt_id'     => $smt_id,
                            'link'       => $link,
                        ]);
                    }
                }
            }

            $save_wallet = teacher_wallets::create([
                'teacher_id' => $teacher->id,
                'balance'   => 0,
            ]);
            if ($save_wallet) {

                $save_wallet_transaction = teacher_wallet_transactions::create([
                    'teacher_wallet_id' => $save_wallet->id,
                    'teacher_id' => $teacher->id,
                    'created_by'   => Auth::id(),
                    'amount'    => 0,
                    'type'      => 'credit',
                    'source'      => 'الرصيد الافتتاحي للمحفظه',
                    'balance_after' => 0,
                ]);
                if ($save_wallet_transaction) {
                    $this->dispatch('message', message: __('Teacher created successfully.'));
                    $this->resetForm();
                    $this->action = 'index';
                }
            }
        }
    } //store method

    public function update($id, $imageName)
    {
        $teacher = Teacher::query()->find($id);
        if ($teacher) {
            $teacher->update([
                'name' => [
                    'ar' => $this->name_ar,
                    'en' => $this->name_en
                ],
                'address' => $this->address,
                'phone' => $this->phone,
                'image' => $imageName,
                'description' => $this->description,
                'city_id' => $this->city_id,
                'user_id' => Auth::id(),

            ]);
            if ($teacher) {


                TeacherSocialMedia::where('teacher_id', $teacher->id)->delete();
                foreach ($this->teacherSMList as $smt_id => $link) {
                    if (!empty($link)) {
                        TeacherSocialMedia::create([
                            'teacher_id' => $teacher->id,
                            'smt_id'     => $smt_id,
                            'link'       => $link,
                        ]);
                    }
                }
                $this->dispatch('message', message: __('Teacher updated successfully.'));
                $this->resetForm();
                $this->action = 'index';
            }
        }
    } //update method

    public function edit($id)
    {
        $this->action = 'create';
        $this->isEdit = true;
        $teacher = Teacher::findOrFail($id);
        $this->teacher_id = $teacher->id;
        $this->name_ar = $teacher->getTranslation('name', 'ar');
        $this->name_en = $teacher->getTranslation('name', 'en');
        $this->address = $teacher->address;
        $this->phone = $teacher->phone;
        $this->old_image = $teacher->image;
        $this->description = $teacher->description;
        $this->city_id = $teacher->city_id;

        // Set location data
        $city              = City::find($teacher->city_id);
        $this->governor_id = $city->governors_id;
        $governor          = governor::find($city->governors_id);
        $this->country_id  = $governor->country_id;

        // $this->teacherSMList = TeacherSocialMedia::where('teacher_id', $teacher->id)->get();
        // هنا نعمل الماب للـ teacherSMList
        $teacherSMs = TeacherSocialMedia::where('teacher_id', $teacher->id)->get();
        $this->teacherSMList = [];
        foreach ($teacherSMs as $sm) {
            $this->teacherSMList[$sm->smt_id] = $sm->link;
        }
    } //edit method

    public function back()
    {
        $this->resetForm();
        $this->action = 'index';
        $this->isEdit = false;
    }

    public function delete($id)
    {
        $teacher = Teacher::query()->find($id);
        if ($teacher) {
            $teacher->delete();
            $this->resetForm();
            $this->dispatch('message', message: __('Teacher deleted successfully.'));
        }
    }

    public function render()
    {
        $countries    = Country::query()->get();
        $governorates = governor::query()->where('country_id', $this->country_id)->get();
        $cities       = City::query()->where('governors_id', $this->governor_id)->get();
        $teachers = Teacher::query()->paginate(10);
        $socialMediaTypes = SocialMediaType::query()->where('status', true)->get();
        return view('livewire.admins.teachers.index', ['teachers' => $teachers, 'countries' => $countries, 'governorates' => $governorates, 'cities' => $cities, 'socialMediaTypes' => $socialMediaTypes]);
    }
}
