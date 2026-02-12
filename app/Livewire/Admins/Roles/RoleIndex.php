<?php

namespace App\Livewire\Admins\Roles;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;



class RoleIndex extends Component
{

    protected $listeners = ['refresh' => '$refresh', 'deleteRole' => 'deleteRole'];

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        $this->dispatch('message', message: __('Role deleted successfully'));
        $this->dispatch('refresh');
    }


    public function edit($id)
    {
        return redirect()->route('admins.roles.edit', ['id' => $id]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admins.roles.role-index', [
            'roles' => Role::all()
        ]);
    }
}
