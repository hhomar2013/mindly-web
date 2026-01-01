<?php

namespace App\Livewire\Admins\Ads;

use App\Helpers\switchActions;
use App\Models\ads;
use App\Models\Center;
use App\Models\Teacher;
use App\Models\TeacherCourseOverview;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdsIndex extends Component
{
    use switchActions, WithFileUploads;
    public array $types = ['sliders', 'popup'];
    public array $from = ['in', 'out'];
    public array $ads_to = ['teacher', 'center', 'course'];
    protected $listeners = ['refreshAds' => '$refresh', 'deleteAds' => 'delete'];
    public $image;
    public $type;
    public ?string $ads_to_id = null;
    public $comment = '';
    public $action = 'index';
    public $old_image, $id;
    public $link;
    public $start_date;
    public $end_date;
    public $resets = ['image', 'old_image', 'type', 'comment', 'id'];
    public $teachers;
    public $centers;
    public $courses;
    public $teacher_id;
    public $center_id;
    public $course_id;




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
        $this->comment = $ads->comment;
        $this->old_image = $ads->image;
    }

    public function saveAds()
    {
        $this->validate([
            'type' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($this->image) {
            $imagePath = $this->image->store('ads', 'public');
        } else {
            $imagePath = $this->old_image;
        }

        $saveAds = ads::query()->updateOrCreate([
            'id' => $this->id,
        ], [
            'type' => $this->type,
            'image' => $imagePath,
            'comment' => $this->comment,
        ]);

        if ($saveAds) {
            $this->dispatch('message', message: $this->id ? __('Ads updated successfully!') : __('Ads created successfully!'));
            $this->index();
        }
    }

    public function delete($id)
    {
        ads::query()->where('id', $id)->delete();
        $this->dispatch('message', message: __('Ads deleted successfully!'));
    }

    public function getTeachers()
    {

        return Teacher::query()->where('status', 1)->get();
    }
    public function getCenters()
    {
        return Center::query()->where('status', 1)->get();
    }
    public function getCourses()
    {
        return TeacherCourseOverview::query()->where('status', 1)->get();
    }

    public function mount()
    {
        $this->ads_to = ['teacher', 'center', 'course'];
    }


    public function updatedAdsToId($value)
    {
        $this->teachers = [];
        $this->teacher_id = null;
        if ($value === 'teacher') {
            $this->teachers = Teacher::select('id', 'name')->get();
        } else if ($value === 'center') {
            $this->centers = Center::select('id', 'name')->get();
        } else if ($value === 'course') {
            $this->courses = TeacherCourseOverview::select('id', 'name')->get();
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $ads = ads::all();
        return view('livewire.admins.ads.ads-index', compact('ads'));
    }
}
