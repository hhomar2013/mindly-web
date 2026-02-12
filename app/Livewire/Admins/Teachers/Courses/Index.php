<?php
namespace App\Livewire\Admins\Teachers\Courses;

use App\Helpers\switchActions;
use App\Models\ContentType;
use App\Models\EducationStage;
use App\Models\EducationSystem;
use App\Models\exam;
use App\Models\SecondaryBranch;
use App\Models\SecondaryGrade;
use App\Models\SecondarySpecialization;
use App\Models\SecondarySubBranch;
use App\Models\SecondaryTrack;
use App\Models\StageGrade;
use App\Models\subjects;
use App\Models\Teacher;
use App\Models\TeacherCourseLesson;
use App\Models\TeacherCourseLessonContent;
use App\Models\TeacherCourseOverview;
use App\Models\teacher_secondary_details;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use switchActions;
    public $IsEdit = false;
    public $course_id;
    public ?int $teacher_id = null;
    public $courses         = [];
    public $teacher         = [];
    public $search          = '';
    #[Url]
    public string $action;
    public $lesson_content_document;
    public $education_system_id;
    public $education_stages = [];
    public $education_stages_id;
    public $stage_grades = [];
    public $stage_grades_id;
    public $secondary_tracks = [];
    public $secondary_tracks_id;
    public $secondary_branch = [];
    public $secondary_branch_id;
    public $secondary_sub_branch = [];
    public $secondary_sub_branch_id;
    public $secondary_specializations = [];
    public $secondary_specializations_id;
    public $price;
    public $course_name;
    public $subject_id;
    public $subjects = [];
    public $subjectShow = [];
    public $lessons = [];
    public $description;
    public $price_note;
    public $optional_link;
    public $lesson_en_name, $lesson_ar_name;
    public $lesson_id;
    public $lessons_content = [];
    public $contentTypes = [];
    public $content_type_id, $selectedContentType;
    public $lesson_content = [];
    public $lesson_content_id;
    public $lesson_content_ar_name;
    public $lesson_content_en_name;
    public $image, $old_image, $lesson_content_link;
    public $teacherQuiz = [];
    public $quiz_id;

    protected $listeners = ['refreshCourse' => 'render', 'deleteCourse' => 'delete', 'deleteLesson' => 'deleteLesson', 'deleteLessonContent' => 'deleteLessonContent'];
    public ?TeacherCourseOverview $editingCourseOverview = null;
    use WithFileUploads, switchActions;

    public function back($action, $session, $update = false, $array = [])
    {
        if (session()->has($session)) {
            session()->forget($session);
        }
        $this->switchAction($action, $update, $array);
        // $this->dispatch('message', message: session($session));
    }
    public function deleteLessonContent($id)
    {
        $q = TeacherCourseLessonContent::query()->find($id);
        if ($q) {
            $q->delete();
            $this->dispatch('message', message: __('Lesson content deleted successfully.'));
            $this->addLessonContent($this->lesson_id);
        }
    }

    public function showLessonContent($id)
    {
        $q = TeacherCourseLessonContent::query()->with('contentType')->find($id);
        if ($q) {
            $this->lesson_content         = $q;
            $this->lesson_content_ar_name = $q->getTranslation('name', 'ar');
            $this->lesson_content_en_name = $q->getTranslation('name', 'en');
            $this->lesson_content_link    = $q->link;
            $this->content_type_id        = $q->contentType->id;
            $this->lesson_id              = $q->tcl_id;
            $this->selectedContentType    = $q->contentType->type;
            $this->IsEdit                 = true;
        }
    }

    public function saveLessonsContent()
    {
        // dd($this->lesson_content_ar_name);
        $this->validate([
            'lesson_content_ar_name' => 'required',
            'lesson_content_en_name' => 'required',
            'content_type_id'        => 'required',
        ]);
        $document      = $this->lesson_content_document ? $this->lesson_content_document->store('teachers/pdf', 'public') : '';
        $quiz          = asset('api/v1/students/join-quiz/' . $this->quiz_id);
        $lessonContent = TeacherCourseLessonContent::query()->create([
            'tcl_id' => $this->lesson_id,
            'ct_id'  => $this->content_type_id,
            'name'   => [
                'ar' => $this->lesson_content_ar_name,
                'en' => $this->lesson_content_en_name,
            ],
            'link'   => $this->lesson_content_document ? $document : $this->lesson_content_link,
        ]);

        // dd( $lessonContent);

        if ($lessonContent) {
            $this->dispatch('message', message: __('Lesson content created successfully.'));
            $this->addLessonContent($this->lesson_id);
            $this->reset([
                'lesson_content_ar_name',
                'lesson_content_en_name',
                'content_type_id',
                'lesson_content_link',
                'lesson_content_document'
            ]);
            $this->IsEdit = false;
        }
    }
    private function getTecherQuiz()
    {
        return exam::query()->where('teacher_id', $this->teacher_id)->where('state', 1)->get();
    }

    public function getSelectedContentType()
    {
        $q = ContentType::query()->where('id', $this->content_type_id)->first();
        if ($q) {
            $this->selectedContentType = $q->type;
        } else {
            $this->selectedContentType = null;
        }

        if ($this->selectedContentType == 'quiz') {
            $this->teacherQuiz = $this->getTecherQuiz();
        }
    }

    public function setActionData(string $actionName)
    {
        $this->action         = $actionName;
        $this->lesson_ar_name = '';
        $this->lesson_id      = null;
        $this->lesson_en_name = '';
    }
    public function delete($id)
    {
        $course = TeacherCourseOverview::query()->find($id);
        if ($course) {
            $course->delete();
            $this->resetForm();
            $this->dispatch('message', message: __('Course deleted successfully.'));
        }
    }
    private function getSelectedAcademicYearModel()
    {
        $modelId    = null;
        $modelClass = null;
        if ($this->secondary_specializations_id) {
            $modelId    = $this->secondary_specializations_id;
            $modelClass = SecondarySpecialization::class;
        } elseif ($this->stage_grades_id) {
            $modelId = $this->stage_grades_id;
            if ($this->secondary_tracks_id) {
                $modelClass = SecondaryGrade::class;
            } else {
                $modelClass = StageGrade::class;
            }
        } else {
            return null;
        }
        return $modelClass::find($modelId);
    }
    public function getScondaryGrades()
    {
        $this->secondary_branch_id          = null;
        $this->secondary_specializations_id = null;
        $this->secondary_sub_branch_id      = null;
        $this->stage_grades                 = [];
        $this->secondary_sub_branch         = [];
        $this->secondary_specializations    = [];
        $this->secondary_branch             = [];
        if ($this->secondary_tracks_id) {
            $q = SecondaryGrade::query()->where('secondary_track_id', $this->secondary_tracks_id)->get();
            if ($q->count() > 0) {
                $this->stage_grades = $q;
            } else {
                $this->stage_grades = false;
            }
        }
    }

    public function getSecondarySpecializations()
    {
        $this->secondary_specializations = [];
        if ($this->secondary_tracks_id) {
            $q = SecondarySpecialization::query()->where('secondary_track_id', $this->secondary_tracks_id)->get();
            if ($q->count() > 0) {
                $this->secondary_specializations = $q;
            } else {
                $this->secondary_specializations = false;
            }
        }
    }
    public function getScondaryBranch()
    {

        $this->secondary_branch          = [];
        $this->secondary_sub_branch      = [];
        $this->secondary_specializations = [];
        if ($this->secondary_tracks_id) {
            $this->getSecondarySpecializations();
            $q = SecondaryBranch::query()->where('secondary_track_id', $this->secondary_tracks_id)->get();
            if ($q->count() > 0) {
                $this->secondary_branch = $q;
            } else {
                $this->secondary_branch = false;
            }
        }
    }
    public function getSecondaryTrack()
    {
        $this->secondary_tracks = [];
        if ($this->education_stages_id) {
            $q = SecondaryTrack::query()->where('education_stage_id', $this->education_stages_id)->get();
            if ($q->count() > 0) {
                $this->secondary_tracks = $q;
            } else {
                $this->getStagesGradeds();
            }
        }
    }
    public function getStagesGradeds()
    {
        $this->stage_grades = [];
        if ($this->education_stages_id) {
            $q = StageGrade::query()->where('education_stage_id', $this->education_stages_id)->get();
            if ($q->count() > 0) {
                $this->stage_grades = $q;
            } else {
                $this->stage_grades = false;
            }
        }
    }
    public function getEducationStages()
    {
        $this->education_stages = [];
        $this->education_stages = EducationStage::query()->where('education_system_id', $this->education_system_id)->get();
    }
    public function getCourses($id)
    {
        $this->action = 'show-course';
        session(['teacher_id' => $id]);
        $this->teacher_id = $id;
        $this->teacher    = Teacher::query()->where('id', $id)->first();
        $course           = TeacherCourseOverview::query()->where('teacher_id', $id)->with(['subject', 'education'])->get();
        $this->courses    = $course;
    }

    private function checkSession($id, $parameter)
    {
        if (session()->has($id)) {
            $this->$parameter(session($id));
        }
    }

    public function getSecondarySubBranch()
    {
        $this->secondary_sub_branch = [];
        if ($this->secondary_branch_id) {
            $q = SecondarySubBranch::query()->where('secondary_branch_id', $this->secondary_branch_id)->get();
            if ($q->count() > 0) {
                $this->secondary_sub_branch = $q;
            } else {
                $this->secondary_sub_branch = false;
            }
        }
    }
    public function mount()
    {
        if (! $this->search) {

            $this->resetForm();
        }
        $this->switchAction("", false, [], ['teacher_id', 'course', 'course_id', 'lesson_id']);
        $this->checkSession('teacher_id', "getCourses");
        $this->checkSession('course', "editCourse");
        $this->checkSession('course_id', "subjectManagment");
        $this->checkSession('lesson_id', "addLessonContent");
    }
    public function addCourse()
    {

        // $this->action = 'add-course';

        $this->teacher = Teacher::query()->where('id', $this->teacher_id)->first();
        $this->resetForm('add-course');
    }
    public function resetForm($action = null)
    {
        if ($action !== null) {
            $this->action = $action;
        }
        // $this->teacher_id = null;
        // $this->courses = [];
        // $this->teacher = [];
        $this->education_system_id          = null;
        $this->education_stages_id          = null;
        $this->stage_grades_id              = null;
        $this->secondary_tracks_id          = null;
        $this->secondary_branch_id          = null;
        $this->secondary_specializations_id = null;
        $this->price                        = null;
        $this->course_name                  = null;
        $this->subject_id                   = null;
        $this->description                  = null;
        $this->price_note                   = null;
        $this->optional_link                = null;
        $this->secondary_sub_branch_id      = null;
        $this->education_stages             = [];
        $this->stage_grades                 = [];
        $this->secondary_tracks             = [];
        $this->secondary_branch             = [];
        $this->secondary_specializations    = [];
        $this->secondary_sub_branch         = [];
    }

    public function save_secondary_details()
    {
        $select_secondary_tracks_id          = $this->secondary_tracks_id;
        $select_secondary_branch_id          = $this->secondary_branch_id;
        $select_secondary_sub_branch_id      = $this->secondary_sub_branch_id;
        $select_stage_grades_id              = $this->stage_grades_id;
        $select_secondary_specializations_id = $this->secondary_specializations_id;

        // dd($select_secondary_tracks_id , $select_secondary_branch_id , $select_secondary_sub_branch_id , $select_stage_grades_id , $select_secondary_specializations_id);

        $teacher_secondary_details                              = new teacher_secondary_details();
        $teacher_secondary_details->secondary_track_id          = $select_secondary_tracks_id ?? null;
        $teacher_secondary_details->secondary_branch_id         = $select_secondary_branch_id ?? null;
        $teacher_secondary_details->secondary_sub_branch_id     = $select_secondary_sub_branch_id ?? null;
        $teacher_secondary_details->secondary_grade_id          = $select_stage_grades_id ?? null;
        $teacher_secondary_details->secondary_specialization_id = $select_secondary_specializations_id ?? null;
        $teacher_secondary_details->save();
        if ($teacher_secondary_details) {
            return teacher_secondary_details::find($teacher_secondary_details->id);
        } else {
            $this->dispatch('error', error: __('Error'));
        }
    } //save_secondary_details

    public function saveCourseOverview()
    {
        $this->validate([
            'course_name'                  => 'required|string|max:255',
            'subject_id'                   => 'required|exists:subjects,id',
            'price'                        => 'nullable|numeric|min:0',
            'stage_grades_id'              => 'exclude_if:secondary_specializations_id,!=,null|nullable|integer',
            'secondary_specializations_id' => 'exclude_if:stage_grades_id,!=,null|nullable|integer',
            'image'                        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // $academicYearModel = $this->getSelectedAcademicYearModel();
        $education_stage = EducationStage::query()->find($this->education_stages_id);
        if ($education_stage->stage_id == 'secondary') {
            $academicYearModel = $this->save_secondary_details();
        } else {
            $academicYearModel = $this->getSelectedAcademicYearModel();
        }

        // dd($academicYearModel);

        if (! $academicYearModel) {
            $this->dispatch('message', message: __('Please select the specific academic year (grade or specialization) for the course.'));
            return;
        }
        // dd($academicYearModel);
        // ✅ التعديل: إذا كان $editingCourseOverview موجوداً، نستخدمه، وإلا ننشئ كائن جديد
        if ($this->editingCourseOverview) {
            $courseOverview = $this->editingCourseOverview;
            $message        = __('Course updated successfully!');
        } else {
            $courseOverview             = new TeacherCourseOverview();
            $courseOverview->teacher_id = $this->teacher_id;
            $message                    = __('Course added successfully!');
        }

        // 3. تعبئة البيانات (مشتركة بين الإضافة والتعديل)
        $courseOverview->subject_id    = $this->subject_id;
        $courseOverview->name          = ['ar' => $this->course_name];
        $courseOverview->price         = $this->price;
        $courseOverview->price_note    = $this->price_note;
        $courseOverview->description   = $this->description;
        $courseOverview->optional_link = $this->optional_link;
        $imageName                     = $this->image ? $this->image->store('courses', 'public') : $this->old_image;
        if ($this->image) {

            $courseOverview->image = $imageName;
        } elseif ($this->old_image) {
            $courseOverview->image = $imageName;
        }

        // 4. ربط/تحديث العلاقة المرنة
        // $courseOverview->academicYear()->associate($academicYearModel);
        $courseOverview->education()->associate($academicYearModel);

        // 5. الحفظ
        $courseOverview->save();

        $this->dispatch('message', message: $message);

        // 6. إعادة تعيين الخصائص
        $this->reset([
            'action',
            'editingCourseOverview',
            'course_name',
            'subject_id',
            'price',
            'description',
            'price_note',
            'optional_link',
            'education_system_id',
            'education_stages_id',
            'stage_grades_id',
            'secondary_tracks_id',
            'secondary_branch_id',
            'secondary_specializations_id',
            'secondary_sub_branch_id',
            'image',
        ]);

        // 7. تحديث قائمة الكورسات
        $this->getCourses($this->teacher_id);
    }
    public function editCourse(TeacherCourseOverview $course)
    {
        $this->reset([
            'education_system_id',
            'education_stages',
            'stage_grades',
            'secondary_tracks',
            'secondary_branch',
            'secondary_specializations',
            'secondary_sub_branch',
            'image',
            'old_image',
        ]);
        session(['course' => $course]);
        // 1. تخزين الكائن في خاصية النموذج
        $this->editingCourseOverview = $course;

        // 2. تحديث الإجراء لاستخدام نفس نموذج الإضافة/التعديل
        $this->action = 'add-course';

        // 3. تحميل البيانات الأساسية
        // (افتراض أنك تستخدم Spatie/Translatable لعمود 'name' من نوع JSON)
        $this->course_name   = $course->getTranslation('name', 'ar');
        $this->subject_id    = $course->subject_id;
        $this->price         = $course->price;
        $this->price_note    = $course->price_note;
        $this->description   = $course->description;
        $this->optional_link = $course->optional_link;
        if ($course->image) {
            $this->old_image = $course->image;
        }
        $this->LoadAcademicForEdit($course->education_id);
        // 4. تحميل بيانات العلاقة المرنة وملء قوائم الإسقاط (Dropdowns)
        // $academicYear = $course->academicYear;
        $academicYear = $course->education;
        // $this->LoadAcademicForEdit($academicYear);
    }

    public function subjectManagment($id)
    {
        $this->reset(['lesson_content_ar_name', 'lesson_content_en_name', 'content_type_id', 'lesson_content_link', 'image']);
        $this->action = 'subject-managment';
        session(['course_id' => $id]);
        $this->course_id   = $id;
        $this->subjectShow = TeacherCourseOverview::query()->where('id', $id)->with('subject')->first();
        $this->lessons     = TeacherCourseLesson::query()->where('tco_id', $id)->get();
    }
    public function editLesson($id)
    {
        $this->action         = 'add-lesson';
        $this->lesson_id      = $id;
        $q                    = TeacherCourseLesson::query()->where('id', $id)->first();
        $this->lessons        = $q;
        $this->lesson_ar_name = $q->getTranslation('name', 'ar');
        $this->lesson_en_name = $q->getTranslation('name', 'en');
    }
    public function saveLesson()
    {
        $this->validate([
            'lesson_ar_name' => 'required',
            'lesson_en_name' => 'required',
        ]);

        $q = TeacherCourseLesson::query()->updateOrCreate([
            'id' => $this->lesson_id,
        ], [
            'name'   => [
                'ar' => $this->lesson_ar_name,
                'en' => $this->lesson_en_name,
            ],
            'tco_id' => $this->course_id,
        ]);
        if ($q) {
            $this->dispatch('message', message: __('Lesson added successfully!'));
            $this->reset(['lesson_ar_name', 'lesson_en_name']);
            $this->subjectManagment($this->course_id);
        } else {
            $this->dispatch('message', message: __('Lesson added failed!'));
            return;
        }
    }
    public function deleteLesson($id)
    {
        $q = TeacherCourseLesson::query()->where('id', $id)->delete();
        if ($q) {
            $this->dispatch('message', message: __('Lesson deleted successfully!'));
            $this->subjectManagment($this->course_id);
        } else {
            $this->dispatch('message', message: __('Lesson deleted failed!'));
            return;
        }
    } //delete lessons

    public function addLessonContent($id)
    {
        $this->action = 'add-lesson-content';
        session(['lesson_id' => $id]);
        $this->lesson_id       = $id;
        $this->lessons_content = TeacherCourseLessonContent::query()->where('tcl_id', $id)->get();
        $this->contentTypes    = ContentType::query()->where('status', 1)->get();
    }

    public function updatedQuizId($value)
    {
        $quiz                         = exam::find($value);
        $this->lesson_content_ar_name = $quiz?->title;
        $this->lesson_content_link    = $quiz?->id;
    }


    private function LoadAcademicForEdit($academicYear)
    {
        $q = TeacherCourseOverview::query()->where('education_id', $academicYear)->with('education')->first();
        $this->getEducationStages();
        $education = $q->education;
        if ($q->education_type == 'secondary_teacher_details') {
            $this->education_system_id = $education->secondaryTrack->stage->education_system_id;
            $this->education_stages_id = $education->secondaryTrack->education_stage_id;
            $this->getEducationStages();
            $this->secondary_tracks_id = $education->secondary_track_id;
            $this->getSecondaryTrack();
            $this->stage_grades_id = $education->secondary_grade_id;
            $this->getScondaryGrades();
            $this->secondary_branch_id = $education->secondary_branch_id;
            $this->getScondaryBranch();
            $this->secondary_sub_branch_id = $education->secondary_sub_branch_id;
            $this->getSecondarySubBranch();
            $this->secondary_specializations_id = $education->secondary_specialization_id;
            $this->getSecondarySpecializations();
        } elseif ($q->education_type == 'stage_grades') {
            $stage_grades              = StageGrade::query()->where('grade_id', $education->grade_id)->with(['stage.system'])->first();
            $this->education_system_id = $stage_grades->stage->education_system_id;
            $this->education_stages_id = $stage_grades->education_stage_id;
            $this->getEducationStages();
            $this->stage_grades_id = $stage_grades->id;
            $this->getStagesGradeds();
        }
        return $q;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $subjects_all     = subjects::all();
        $education_system = EducationSystem::all();
        $teachers         = Teacher::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
                $query->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->where(['state' => 1, 'in_out' => 1])->get();
        return view('livewire.admins.teachers.courses.index', compact('teachers', 'education_system', 'subjects_all'));
    }
}
