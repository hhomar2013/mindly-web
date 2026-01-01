<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i>
                        {{ __('Type of Subscriptions') }}
                    </h5>
                    <div class="text-start">
                        @include('tools.spinner')
                    </div>
                </div>
                @if ($action == 'index')
                    @include('livewire.admins.type-of-subscription.tos-show')
                @elseif($action == 'create')
                    @include('livewire.admins.type-of-subscription.tos-create')
                @endif
            </div>
        </div>
    </div>
</div>
@script
    @include('tools.message')
@endscript
@include('tools.confimDelete', ['method' => 'deleteSocialMediaType'])
