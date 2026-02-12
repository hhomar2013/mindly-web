<div class="p-6 max-w-lg mx-auto bg-white shadow rounded">
    <h3 class="text-lg font-bold mb-4"> {{ __('Add new role') }} </h3>

    <form wire:submit="{{ $editMode ? 'update' : 'save' }}">
        <div class="mb-4">
            <label class="block"> {{ __('Role Name') }} </label>
            <input type="text" wire:model="name" class="form-control" class="w-full border p-2 rounded">
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary rounded">{{ $editMode ? __('update') : __('save') }}</button>
        <a href="/roles" class="btn btn-secondary rounded">{{ __('cancel') }}</a>

    </form>

    <div class="mb-4">
        <div class="flex items-center justify-between mt-2">
            <label class="block font-bold">{{ __('Select permissions') }}</label>
            <div class="form-check">
                <label for="selectAll" class="form-check-label"><input type="checkbox" wire:model.live="selectAll"
                        id="selectAll" class="form-check-input">
                    {{ __('Check All') }}</label>
            </div>
        </div>
        <div class="mt-2" style="max-height: 500px; overflow-y:scroll;overflow-x:hidden;">
            {{-- @foreach ($permissions as $permission)
                <div class="form-check">
                    <label class="form-check-label block" for="permission-{{ $permission->name }}">
                        <input type="checkbox" class="form-check-input" id="permission-{{ $permission->name }}"
                            wire:model="selectedPermissions" value="{{ $permission->name }}">
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach --}}

            <div class="space-y-6">
                @foreach ($groupedPermissions as $groupName => $permissions)
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h4 class="font-bold text-blue-700 mb-3 border-b pb-2 uppercase text-sm">
                            {{ $groupName }}
                        </h4>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" wire:model="selectedPermissions"
                                            value="{{ $permission->name }}" class="form-check-input">
                                        <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @error('selectedPermissions')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

</div>

@script
    @include('tools.message')
@endscript
