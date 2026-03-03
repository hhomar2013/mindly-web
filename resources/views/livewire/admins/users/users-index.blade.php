<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fa-solid fa-users text-dark"></i>
                        {{ __('Users') }}
                    </h5>
                    @include('tools.spinner')
                    <select wire:model.live="perPage" class="form-select form-select-sm w-5">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="text-start">
                            <button class="btn btn-sm btn-rounded btn-primary" wire:click.prevent="$set('action', 'create')">
                                <i class="fa fa-plus"></i>
                                {{ __('Add User') }}
                            </button>

                            <a href="{{ route('admins.roles') }}" class="btn btn-sm btn-rounded btn-primary">
                                {{ __('Roles') }}
                            </a>
                            <a href="{{ route('admins.permissions') }}" class="btn btn-sm btn-rounded btn-primary">
                                {{ __('Permissions') }}
                            </a>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-rounded  {{ $type == 'users' ? 'btn-danger' : 'btn-primary' }}" wire:click.prevent="selectType('users')">{{ __('Users') }}</button>
                            <button class="btn btn-rounded {{ $type == 'students' ? 'btn-danger' : 'btn-primary' }}" wire:click.prevent="selectType('students')">{{ __('Students') }}</button>
                        </div>
                    </div>

                    <h5 class="card-title">{{ __('Users List') }}</h5>
                    <p class="card-text">
                        {{ __('This is the list of all users registered in the system.') }}
                    </p>
                    <!-- Users Table -->
                    @if ($type == 'users')
                    @include('livewire.admins.users.users-table', ['users' => $this->system_users])
                    @else
                    @include('livewire.admins.users.student-table', ['users' => $this->students])
                    @endif
                    <!-- End Users Table -->
                </div>
            </div>
        </div>
    </div>
</div>

@script
@include('tools.message')
@endscript
@include('tools.confimDelete', ['method' => 'logout'])
