<?php
namespace App\Livewire\Admins\Users;

use App\Models\Students;
use App\Models\StudentsLogs;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsersIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selectedRoles      = [];
    public $type               = 'users';
    public $action             = 'index';
    public $name;
    public $email;
    public $password;
    public $user;
    public $search;
    public $perPage      = 10;
    protected $listeners = ['logout' => 'logoutStudent', 'refresh' => '$refresh'];

    public function assignRole($userId, $roleName)
    {
        $user = User::findOrFail($userId);
        if ($user->hasRole($roleName)) {
            $user->removeRole($roleName);
            $this->dispatch('refresh');
        } else {
            $user->assignRole($roleName);
            $this->dispatch('refresh');
        }
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function selectType($type)
    {
        $this->resetPage();
        $this->type = $type;
    }

    public function saveUser()
    {
        $this->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $user ? $this->dispatch('message', message : __('User created successfully')):
        $this->dispatch('message', message: __('User created failed'));
        $this->name     = '';
        $this->email    = '';
        $this->password = '';
        $this->dispatch('refresh');
        $this->action = 'index';
    }

    public function editUser($id)
    {
        $this->action = 'create';
        $this->user   = User::find($id);
        $this->name   = $this->user->name;
        $this->email  = $this->user->email;
    }

    public function logoutStudent($id)
    {
        $student = Students::find($id);
        if (! $student) {
            return;
        }

        // $student->update(['status' => false]);
        StudentsLogs::where('student_id', $id)
            ->where('is_active', true)
            ->update([
                'action'    => 'logout',
                'is_active' => false,
            ]);
        if (method_exists($student, 'tokens')) {
            $student->tokens()->delete();
        } else {
            PersonalAccessToken::where('tokenable_type', get_class($student))
                ->where('tokenable_id', $id)
                ->delete();
        }
        $this->dispatch('message', message: __('Student logged out successfully'));
        $this->dispatch('refresh');
        $this->type   = 'students';
        $this->action = 'index';
    }

    public function studentIsOnline($id)
    {
        $log = StudentsLogs::query()->where('student_id', $id)->where('is_active', true)->latest()->first();
        if (! $log) {
            return false;
        }
        return $log;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[Computed()]
    public function system_users()
    {

        return User::query()->where('email', '!=', 'omar@app.com')
            ->where('name', 'like', '%' . $this->search . '%')
            ->with('roles')->paginate($this->perPage);
    }

    #[Computed()]
    public function students()
    {
        return Students::with(['logs' => function ($query) {
            $query->where('is_active', true);
        }])->paginate($this->perPage);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        if ($this->action == 'create') {
            return view('livewire.admins.users.create-user');
        }

        return view(
            'livewire.admins.users.users-index',
            [
                'roles' => Role::all(),
                'users' => $this->type == 'users' ?
                $this->system_users() :
                $this->students(),
            ]
        );
    }
}
