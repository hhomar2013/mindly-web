<?php

namespace App\Livewire\Admins\Ads;

use App\Helpers\switchActions;
use App\Helpers\WithPreviewHelper;
use App\Models\ads;
use App\Models\Center;
use App\Models\City;
use App\Models\Country;
use App\Models\governor;
use App\Models\Students;
use App\Models\Teacher;
use App\Models\TeacherCourseOverview;
use App\Models\User;
use App\Notifications\AdsNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdsIndex extends Component
{
    use switchActions, WithFileUploads, WithPreviewHelper;

    public $country_id;

    public $governorate_id;

    public $city_id;

    public array $types = ['sliders', 'popup'];

    public array $from = ['in', 'out'];

    public array $typeOfAdPosting = ['ads', 'adsAndNotifications', 'notifications'];

    public $typeOfAdPostingSelected;

    public $id;

    public $image;

    public $old_image;

    public $type;

    public $comment;

    public $link;

    public $start_date;

    public $end_date;

    public $inOrOut;

    // Notification fields
    public $notification_title;

    public $notification_text;

    public $target_type;

    public $ad_to_id;

    public $ad_to_type;

    public $teachers = [];

    public $centers = [];

    public $courses = [];

    public $students = [];

    public $action = 'index';

    public $resets = ['image', 'old_image', 'type', 'comment', 'id', 'link', 'inOrOut', 'target_type', 'ad_to_id', 'start_date', 'end_date'];

    protected $listeners = ['refreshAds' => '$refresh', 'deleteAds' => 'delete'];

    public function updatedcountryId()
    {
        if ($this->country_id == 0) {
            $this->governorate_id = 0;
            $this->city_id = 0;
        }
    }

    public array $morph_map = [
        'teacher' => Teacher::class,
        'center' => Center::class,
        'course' => TeacherCourseOverview::class,
        'general' => null,
    ];

    public function sendNotify()
    {
        $user = User::find(1);
        $info = [
            'id' => 200,
            'name' => 'عمر محجوب',
        ];
        Notification::send($user, new AdsNotification($info));
        $this->dispatch('navbarRefresh');
    }

    public function index()
    {
        $this->reset($this->resets);
        $this->switchAction('index');
    }

    public function create()
    {
        $this->reset($this->resets);
        $this->switchAction('create');
    }

    public function edit(ads $ads)
    {
        $this->reset($this->resets);
        $this->switchAction('create');

        $this->id = $ads->id;
        $this->type = $ads->type;
        $this->inOrOut = $ads->from;
        $this->comment = $ads->comment;
        $this->link = $ads->link;
        $this->start_date = $ads->start_date;
        $this->end_date = $ads->end_date;
        $this->old_image = $ads->image;

        if ($ads->from == 'in') {
            // 1. تحديد النوع (teacher, center, course) من الـ morph type
            $this->target_type = array_search($ads->ad_to_type, $this->morph_map);

            // 2. شحن القائمة المناسبة فوراً بناءً على النوع
            $this->loadTargetData($this->target_type);

            // 3. تعيين الـ ID المختار
            $this->ad_to_id = $ads->ad_to_id;
        }
    }

    public function loadTargetData($type)
    {
        if ($type === 'teacher') {
            $this->teachers = Teacher::query()->where('state', 1)->get();
        } elseif ($type === 'center') {
            $this->centers = Center::query()->where('state', 1)->get();
        } elseif ($type === 'course') {
            $this->courses = TeacherCourseOverview::query()->get();
        }
    }

    public function updatedTargetType($value)
    {
        $this->ad_to_id = null;
        $this->loadTargetData($value);
    }

    public function getStudentsToSendNotification()
    {
        if ($this->country_id && $this->governorate_id && $this->city_id) {
            $students = Students::query()->where('city_id', $this->city_id)->where('status', 1)->get();
        } elseif ($this->country_id && $this->governorate_id) {
            $students = Students::query()->where('governorate_id', $this->governorate_id)->where('status', 1)->get();
        }

        return $students;
    }

    public function saveAds()
    {
        $this->students = $this->getStudentsToSendNotification();

        $rules = [
            'typeOfAdPostingSelected' => 'required',
            'country_id' => 'required',
            'governorate_id' => 'required',
            'city_id' => 'nullable',
            'inOrOut' => 'required|in:out,in',

        ];

        switch ($this->typeOfAdPostingSelected) {
            case 'ads':
                $rules = array_merge($rules, [
                    'type' => 'required',
                    'image' => 'required|image|max:2048',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                ]);
                break;

            case 'notifications':
                $rules = array_merge($rules, [
                    'notification_title' => 'required',
                    'notification_text' => 'required',
                ]);
                break;

            case 'adsAndNotifications':
                $rules = array_merge($rules, [
                    'type' => 'required',
                    'image' => 'required|image|max:2048',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'notification_title' => 'required',
                    'notification_text' => 'required',
                ]);
                break;
        }

        if ($this->inOrOut == 'out') {
            $rules['link'] = 'required|url';
        } elseif ($this->inOrOut == 'in' && $this->target_type != 'general') {
            $rules['target_type'] = 'required';
            $rules['ad_to_id'] = 'required';
        } elseif ($this->inOrOut == 'in' && $this->target_type == 'general') {
            $rules['target_type'] = 'nullable';
            $rules['ad_to_id'] = 'nullable';
        }
        $this->validate($rules);

        $imagePath = $this->image ? $this->image->store('ads', 'public') : $this->old_image;

        $finalLink = $this->link;
        if ($this->inOrOut == 'in') {
            if ($this->target_type == 'teacher') {
                $finalLink = '/teacher-profile/'.$this->ad_to_id;
            } elseif ($this->target_type == 'center') {
                $finalLink = '/center-profile/'.$this->ad_to_id;
            } elseif ($this->target_type == 'course') {
                $finalLink = '/course/'.$this->ad_to_id;
            } elseif ($this->target_type == 'general') {
                $finalLink = '/';
            }
        }
        $data = [];
        $data = [
            'type' => $this->type,
            'from' => $this->inOrOut,
            'link' => $finalLink,
            'ad_to_type' => $this->inOrOut == 'in' ? $this->morph_map[$this->target_type] : null,
            'ad_to_id' => $this->inOrOut == 'in' ? $this->ad_to_id : null,
            'model_name' => $this->inOrOut == 'in' ? $this->target_type : 'external',
            'aop' => $this->typeOfAdPostingSelected,
            'country_id' => $this->country_id,
            'governorate_id' => $this->governorate_id,
            'city_id' => $this->city_id,
        ];
        $notificationBody = [
            'title' => $this->notification_title,
            'message' => $this->notification_text,
            'url' => $finalLink ?? '/',
        ];
        if ($this->typeOfAdPostingSelected == 'ads') {
            $data['comment'] = $this->comment ?? '';
            $data['image'] = $imagePath;
            $data['start_date'] = $this->start_date;
            $data['end_date'] = $this->end_date;
            ads::updateOrCreate(['id' => $this->id], $data);
        } elseif ($this->typeOfAdPostingSelected == 'notifications') {
            Notification::send($this->getStudentsToSendNotification(), new AdsNotification($notificationBody));
        } elseif ($this->typeOfAdPostingSelected == 'adsAndNotifications') {
            $data['image'] = $imagePath;
            $data['start_date'] = $this->start_date;
            $data['end_date'] = $this->end_date;
            ads::updateOrCreate(['id' => $this->id], $data);
            Notification::send($this->getStudentsToSendNotification(), new AdsNotification($notificationBody));
        }
        $this->dispatch('message', message: $this->id ? __('Ads updated!') : __('Ads created!'));
        $this->index();
    }

    public function delete($id)
    {
        ads::find($id)->delete();
        $this->dispatch('message', message: __('Ads deleted!'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $countries = Country::query()->where('status', 1)->get();
        $governorates = governor::query()->where('country_id', $this->country_id)->where('status', 1)->get();
        $cities = City::query()->where('governors_id', $this->governorate_id)->where('status', 1)->get();

        return view('livewire.admins.ads.ads-index', [
            'ads' => ads::latest('created_at')->get(),
            'countries' => $countries,
            'governorates' => $governorates,
            'cities' => $cities,
        ]);
    }
}
