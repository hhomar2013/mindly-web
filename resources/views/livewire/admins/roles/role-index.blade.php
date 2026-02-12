<div class="p-6">
    <div class="flex justify-between mb-4">
        <h3 class="text-lg font-bold"> {{ __('Role list') }} </h3>
        <a href="{{ route('admins.roles.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ __('Add new role') }}
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-2 mb-4">{{ session('message') }}</div>
    @endif

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">{{ __('Role Name') }}</th>
                <th class="border px-4 py-2"> {{ __('Actions') }} </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td class="border px-4 py-2">{{ $role->name }}</td>
                    <td class="border px-4 py-2">
                        <button class="btn btn-warning btn-sm btn-rounded"
                            wire:click.prevent="edit({{ $role->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-rounded"
                            onclick="confirm({{ $role->id }}, 'deleteRole')">
                            {{ __('delete') }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@script
    @include('tools.message')
@endscript
@include('tools.confimDelete', ['method' => ''])
