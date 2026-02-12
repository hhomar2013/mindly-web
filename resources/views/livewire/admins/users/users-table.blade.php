<input class="form-control rounded" type="text" wire:model.live.debounce.500ms="search"
    placeholder="{{ __('Search') }}" />

<table class="table table-striped table-hover table-bordered table-responsive table-sm">
    <thead class="text-center">
        <tr>
            <th scope="col">{{ __('#') }}</th>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Email') }}</th>
            <th scope="col">{{ __('Current Role') }}</th>
            <th scope="col">{{ __('Roles') }}</th>
            <th scope="col">{{ __('Created At') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr class="text-center">
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="p-3">
                    @foreach ($user->roles as $role)
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs mr-1">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </td>
                <td class="p-3">
                    <div class="flex flex-wrap gap-2">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Assign Role') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @foreach ($roles as $role)
                                    <li>
                                        <button
                                            class="dropdown-item {{ $user->hasRole($role->name) ? 'text-danger' : '' }}"
                                            wire:click="assignRole({{ $user->id }}, '{{ $role->name }}')">
                                            {{ $user->hasRole($role->name) ? __('cancel') : __('Assign') }}
                                            {{ $role->name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                </td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>
                    <button class="btn btn-sm btn-rounded btn-primary"
                        wire:click.prevent="editUser({{ $user->id }})"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-sm  btn-rounded btn-danger"><i class="fa fa-trash"></i></button>

                </td>
            </tr>
        @endforeach
    </tbody>




</table>
<div class="mb-2 p-3">
    {{ $users->links() }}
</div>
