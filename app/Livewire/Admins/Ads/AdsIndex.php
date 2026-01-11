<?php

// namespace App\Livewire\Admins\Ads;

// use App\Helpers\switchActions;
// use App\Models\ads;
// use App\Models\Center;
// use App\Models\Teacher;
// use App\Models\TeacherCourseOverview;
// use Livewire\Attributes\Layout;
// use Livewire\Attributes\On;
// use Livewire\Component;
// use Livewire\WithFileUploads;

// class AdsIndex extends Component
// {
//     use switchActions, WithFileUploads;
//     public array $types = ['sliders', 'popup'];
//     public array $from = ['in', 'out'];
//     public array $ads_to = ['teacher', 'center', 'course'];
//     protected $listeners = ['refreshAds' => '$refresh', 'deleteAds' => 'delete'];
//     public $image;
//     public $type;
//     public ?string $ads_to_id = null;
//     public $comment = '';
//     public $action = 'index';
//     public $old_image, $id;
//     public $link;
//     public $start_date;
//     public $end_date;
//     public $resets = ['image', 'old_image', 'type', 'comment', 'id'];
//     public $teachers;
//     public $centers;
//     public $courses;
//     public $teacher_id;
//     public $center_id;
//     public $course_id;
//     public $inOrOut;

//     public function index()
//     {
//         $this->reset($this->resets);
//         $this->switchAction('index');
//     }

//     public function create()
//     {
//         $this->reset($this->resets);
//         $this->switchAction('create');
//     }

//     public function edit(ads $ads)
//     {
//         $this->reset($this->resets);
//         $this->switchAction('create');
//         $this->id = $ads->id;
//         $this->type = $ads->type;
//         $this->comment = $ads->comment;
//         $this->old_image = $ads->image;
//     }

//     public function saveAds()
//     {
//         $this->validate([
//             'type' => 'required',
//             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//         ]);
//         if ($this->image) {
//             $imagePath = $this->image->store('ads', 'public');
//         } else {
//             $imagePath = $this->old_image;
//         }

//         $saveAds = ads::query()->updateOrCreate([
//             'id' => $this->id,
//         ], [
//             'type' => $this->type,
//             'image' => $imagePath,
//             'comment' => $this->comment,
//         ]);

//         if ($saveAds) {
//             $this->dispatch('message', message: $this->id ? __('Ads updated successfully!') : __('Ads created successfully!'));
//             $this->index();
//         }
//     }

//     public function delete($id)
//     {
//         ads::query()->where('id', $id)->delete();
//         $this->dispatch('message', message: __('Ads deleted successfully!'));
//     }

//     public function getTeachers()
//     {

//         return Teacher::query()->where('status', 1)->get();
//     }
//     public function getCenters()
//     {
//         return Center::query()->where('status', 1)->get();
//     }
//     public function getCourses()
//     {
//         return TeacherCourseOverview::query()->where('status', 1)->get();
//     }

//     public function mount()
//     {
//         $this->ads_to = ['teacher', 'center', 'course'];
//     }

//     public function updatedAdsToId($value)
//     {
//         $this->teachers = [];
//         $this->teacher_id = null;
//         if ($value === 'teacher') {
//             $this->teachers = Teacher::select('id', 'name')->get();
//         } else if ($value === 'center') {
//             $this->centers = Center::select('id', 'name')->get();
//         } else if ($value === 'course') {
//             $this->courses = TeacherCourseOverview::select('id', 'name')->get();
//         }
//     }

//     #[Layout('layouts.app')]
//     public function render()
//     {
//         $ads = ads::all();
//         return view('livewire.admins.ads.ads-index', compact('ads'));
//     }
// }

namespace App\Livewire\Admins\Ads;

use App\Helpers\switchActions;
use App\Models\ads;
use App\Models\Center;
use App\Models\Teacher;
use App\Models\TeacherCourseOverview;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdsIndex extends Component
{
    use switchActions, WithFileUploads;

    public array $types = ['sliders', 'popup'];
    public array $from  = ['in', 'out'];

    // بيانات النموذج
    public $id, $image, $old_image, $type, $comment, $link;
    public $start_date, $end_date, $inOrOut = 'in';

                         // حقول الـ Morph بناءً على تسمية علاقتك adTo()
    public $target_type; // سنستخدمه لتحديد (teacher, center, course)
    public $ad_to_id;    // الـ ID الفعلي للسجل المختار
    public $ad_to_type;  // المسار الكامل للموديل (يتم ضبطه تلقائياً قبل الحفظ)

    public $teachers = [], $centers = [], $courses = [];
    public $action   = 'index';

    // تحديث المصفوفة لتشمل الحقول الجديدة
    public $resets = ['image', 'old_image', 'type', 'comment', 'id', 'link', 'inOrOut', 'target_type', 'ad_to_id', 'start_date', 'end_date'];

    protected $listeners = ['refreshAds' => '$refresh', 'deleteAds' => 'delete'];

    // خريطة الربط
    public array $morph_map = [
        'teacher' => Teacher::class,
        'center'  => Center::class,
        'course'  => TeacherCourseOverview::class,
    ];

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

        $this->id         = $ads->id;
        $this->type       = $ads->type;
        $this->inOrOut    = $ads->from;
        $this->comment    = $ads->comment;
        $this->link       = $ads->link;
        $this->start_date = $ads->start_date;
        $this->end_date   = $ads->end_date;
        $this->old_image  = $ads->image;

        if ($ads->from == 'in') {
            // 1. تحديد النوع (teacher, center, course) من الـ morph type
            $this->target_type = array_search($ads->ad_to_type, $this->morph_map);

            // 2. شحن القائمة المناسبة فوراً بناءً على النوع
            $this->loadTargetData($this->target_type);

            // 3. تعيين الـ ID المختار
            $this->ad_to_id = $ads->ad_to_id;
        }
    }

// دالة مساعدة لشحن البيانات نستخدمها في الـ Edit وفي الـ Update
    public function loadTargetData($type)
    {
        if ($type === 'teacher') {
            $this->teachers = Teacher::where('state', 1)->get();
        } elseif ($type === 'center') {
            $this->centers = Center::where('state', 1)->get();
        } elseif ($type === 'course') {
            $this->courses = TeacherCourseOverview::query()->get();
        }
    }

// تحديث القائمة عند التغيير اليدوي من الـ Blade
    public function updatedTargetType($value)
    {
        $this->ad_to_id = null; // تصفير الـ ID عند تغيير النوع
        $this->loadTargetData($value);
    }

    // // يتم استدعاؤها عند تغيير نوع الوجهة (مدرس، سنتر، كورس)
    // public function updatedTargetType($value)
    // {
    //     $this->ad_to_id = null;
    //     if ($value === 'teacher') {
    //         $this->teachers = Teacher::where('state', 1)->get();
    //     } elseif ($value === 'center') {
    //         $this->centers = Center::where('state', 1)->get();
    //     } elseif ($value === 'course') {
    //         $this->courses = TeacherCourseOverview::query()->get();
    //     }
    // }

    public function saveAds()
    {
        $rules = [
            'type'       => 'required',
            'inOrOut'    => 'required|in:in,out',
            'image'      => $this->id ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ];

        if ($this->inOrOut == 'out') {
            $rules['link'] = 'required|url';
        } else {
            $rules['target_type'] = 'required';
            $rules['ad_to_id']    = 'required';
        }

        $this->validate($rules);

        $imagePath = $this->image ? $this->image->store('ads', 'public') : $this->old_image;

        // تجهيز الرابط بناءً على النوع إذا كان داخلياً
        $finalLink = $this->link;
        if ($this->inOrOut == 'in') {
            if ($this->target_type == 'teacher') {
                $finalLink = "/teacher-profile/" . $this->ad_to_id;
            } elseif ($this->target_type == 'center') {
                $finalLink = "/center-profile/" . $this->ad_to_id;
            } elseif ($this->target_type == 'course') {
                $finalLink = "/course/" . $this->ad_to_id;
            }
        }

        $data = [
            'type'       => $this->type,
            'from'       => $this->inOrOut,
            'image'      => $imagePath,
            'comment'    => $this->comment,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'link'       => $finalLink, // الرابط المولد تلقائياً أو اليدوي
            'ad_to_type' => $this->inOrOut == 'in' ? $this->morph_map[$this->target_type] : null,
            'ad_to_id'   => $this->inOrOut == 'in' ? $this->ad_to_id : null,
            'model_name' => $this->inOrOut == 'in' ? $this->target_type : 'external',
        ];

        ads::updateOrCreate(['id' => $this->id], $data);

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
        return view('livewire.admins.ads.ads-index', [
            'ads' => ads::latest()->get(),
        ]);
    }
}
