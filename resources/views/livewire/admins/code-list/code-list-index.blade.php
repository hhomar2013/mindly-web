<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        {{-- <i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i> --}}
                        <i class="fa-solid fa-qrcode text-dark  opacity-10"></i>
                        {{ __('Code List') }}
                    </h5>
                    <div>
                        @include('tools.spinner')
                    </div>
                </div>

                @if ($action == 'show')
                    @include('livewire.admins.code-list.code-list-show')
                @elseif($action == 'create')
                    @include('livewire.admins.code-list.code-list-create')
                @elseif ($action == 'show-codes')
                    @include('livewire.admins.code-list.code-list')
                @endif

            </div>
        </div>
    </div>
</div>


@script
    @include('tools.message')
@endscript