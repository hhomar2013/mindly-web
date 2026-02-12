<?php

namespace App\Livewire\Admins\Permissions;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionIndex extends Component
{
    public $name;
    public $group;
    protected $listeners = ['deletePermission' => 'deletePermission', 'refresh' => '$refresh'];
    public function save()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name',
            'group' => 'required'
        ]);

        Permission::create(['name' => $this->name, 'group' => $this->group]);

        $this->name = '';
        $this->group = '';
        // session()->flash('message', 'تم إضافة الصلاحية بنجاح.');
    }

    public function deletePermission($id)
    {
        $delete = Permission::findOrFail($id)->delete();
        if ($delete) {
            // session()->flash('message', 'تم حذف الصلاحية بنجاح.');
            $this->dispatch('refresh');
        }
    }
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admins.permissions.permission-index', [
            'groupedPermissions' => Permission::all()->groupBy('group')
        ]);
    }
}
