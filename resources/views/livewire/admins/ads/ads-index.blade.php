<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fa-brands fa-adversal text-dark  opacity-10"></i>
                        {{ __('Ads') }}
                    </h5>
                    <div>
                        @include('tools.spinner')
                    </div>
                </div>

                @if ($action == 'index')
                    @include('livewire.admins.ads.ads-show')
                @elseif($action == 'create')
                    @include('livewire.admins.ads.ads-create')
                @endif

            </div>
        </div>
    </div>
</div>


@script
    @include('tools.message')
@endscript
