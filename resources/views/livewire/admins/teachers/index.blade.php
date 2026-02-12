<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fa-solid fa-graduation-cap text-dark text-sm opacity-10"></i>&nbsp;
                        {{ __('Teachers') }}
                    </h5>
                    <div class="text-start">
                        @include('tools.spinner')
                    </div>
                </div>
                @if ($action == 'index')
                    @include('livewire.admins.teachers.show')
                @elseif($action == 'create')
                    @include('livewire.admins.teachers.create')
                @endif

            </div>
        </div>
    </div>
</div>
@script
    @include('tools.message')
@endscript
