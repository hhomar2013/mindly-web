<?php

namespace App\Livewire\Admins\Roles;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleCreate extends Component
{
    public $name;
    public $selectedPermissions = [];
    // public $groupedPermissions = [];
    public $selectAll = false;
    public $editMode = false;
    protected $listeners = ['refresh' => '$refresh'];
    protected $rules = [
        'name' => 'required|unique:roles,name',
        'selectedPermissions' => 'required|array'
    ];

    public $roleId;

    public function mount($id = null)
    {
        if ($id) {
            $this->editMode = true;
            $this->roleId = $id;
            $role = Role::find($id);
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function save()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);
        $this->reset('name', 'selectedPermissions');
        $this->dispatch('message', message: __('Role created successfully'));
        $this->dispatch('refresh');
    }


    public function update()
    {
        $this->validate(['name' => 'required|unique:roles,name,' . $this->roleId]);
        $role = Role::find($this->roleId);
        $role->update(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);
        $this->reset('name', 'selectedPermissions');
        $this->dispatch('message', message: __('Role updated successfully'));
        $this->dispatch('refresh');
    }



    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedPermissions = Permission::get()->pluck('name')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
        $this->dispatch('refresh');
    }
    #[Layout('layouts.app')]
    public function render()
    {
        // $this->groupedPermissions = Permission::groupBy('group')->get();
        return view('livewire.admins.roles.role-create', [
            'groupedPermissions' => Permission::all()->groupBy('group')
        ]);
    }
}
