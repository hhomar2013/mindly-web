<div class="p-6 max-w-4xl mx-auto">
    <div class="bg-white p-6 shadow-sm rounded-lg mb-6 border border-gray-100">
        <h3 class="font-bold text-gray-700 mb-4 flex items-center">
            {{ __('Add new permission') }}
        </h3>
        <form wire:submit="save">
            <div>
                <label for="">{{ __('Permission name') }}</label>
                <input type="text" wire:model="name" class="form-control">
                @error('name')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for=""> {{ __('Group name') }} </label>
                <input type="text" wire:model="group" class="form-control">
                @error('group')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            <br>
            <button type="submit" class="btn btn-primary">
                {{ __('save') }}
            </button>
        </form>
    </div>

    <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden p-3">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-gray-700">{{ __('Permessions') }} </h3>
        </div>
        <table class="table table-striped table-hovered ">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm">
                    <th class="p-3 text-right font-semibold">{{ __('Permission name') }}</th>
                    <th class="p-3 text-center font-semibold w-24">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($groupedPermissions as $groupName => $permissions)
                    <tr class="bg-primary ">
                        <td colspan="2" class="text-white p-3">
                            📁 {{ $groupName }}
                        </td>
                    </tr>

                    @foreach ($permissions as $permission)
                        <tr class="hover:bg-gray-50 transition duration-75">
                            <td class="p-3 text-gray-700 pr-8 text-sm">
                                <span class="text-gray-400 ml-2">•</span> {{ $permission->name }}
                            </td>
                            <td class="p-3 text-center">
                                <button class="btn btn-danger"
                                    onclick="confirmDelete({{ $permission->id }} ,'deletePermission')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@script
    @include('tools.message')
@endscript
@include('tools.confimDelete', ['method' => ''])
