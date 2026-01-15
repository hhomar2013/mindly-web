<?php

namespace App\Livewire\Admins\Users;

use App\Models\Students;
use App\Models\StudentsLogs;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Attributes\Layout;
use Livewire\Component;

class UsersIndex extends Component
{
    public $type = 'users';
    protected $listeners = ['logoutStudent' => 'logoutStudent'];

    public function selectType($type)
    {
        $this->type = $type;
    }


    public function logoutStudent($id)
    {
        // 1. جلب الطالب وتحديث حالته
        $student = Students::find($id);
        if (!$student) return;

        $student->update(['status' => false]);

        // 2. تحديث سجلات الدخول (Logs)
        // بنعمل update لجميع الجلسات النشطة مرة واحدة بدل Latest فقط (أضمن للـ Multi-device)
        StudentsLogs::where('student_id', $id)
            ->where('is_active', true)
            ->update([
                'action' => 'logout',
                'is_active' => false
            ]);

        // 3. مسح التوكنز (Personal Access Tokens)
        // الطريقة الأفضل والمباشرة من خلال العلاقة لو كنت ضايف HasApiTokens في الموديل
        if (method_exists($student, 'tokens')) {
            $student->tokens()->delete(); // بيمسح كل التوكنز الخاصة بالطالب ده فوراً
        } else {
            // لو مش حاطط العلاقة، نستخدم الطريقة اليدوية بس بشكل أصح
            PersonalAccessToken::where('tokenable_type', get_class($student))
                ->where('tokenable_id', $id)
                ->delete();
        }
    }




    #[Layout('layouts.app')]
    public function render()
    {

        return view(
            'livewire.admins.users.users-index',
            ['users' => $this->type == 'users' ?  User::query()->where('email', '!=', 'omar@app.com')->get() : Students::all()]
        );
    }
}
