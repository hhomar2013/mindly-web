<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fa-solid fa-users text-dark "></i>
                        {{ __('Users') }}
                    </h5>



                    @include('tools.spinner')
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="text-start">
                            <button class="btn btn-sm btn-rounded btn-primary">
                                <i class="fa fa-plus"></i>
                                {{ __('Add User') }}
                            </button>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-rounded  {{ $type == 'users' ? 'btn-danger' : 'btn-primary' }}"
                                wire:click.prevent="selectType('users')">{{ __('Users') }}</button>
                            <button class="btn btn-rounded {{ $type == 'students' ? 'btn-danger' : 'btn-primary' }}"
                                wire:click.prevent="selectType('students')">{{ __('Students') }}</button>
                        </div>
                    </div>

                    <h5 class="card-title">{{ __('Users List') }}</h5>
                    <p class="card-text">
                        {{ __('This is the list of all users registered in the system.') }}
                    </p>
                    <!-- Users Table -->
                    @if ($type == 'users')
                        @include('livewire.admins.users.users-table', ['users' => $users])
                    @else
                        @include('livewire.admins.users.student-table', ['users' => $users])
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
@include('tools.confimDelete', ['method' => 'LogoutStudent'])
