<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fa-solid fa-wallet text-dark text-sm opacity-10"></i>&nbsp;
                        {{ __('Teacher Wallets') }}
                    </h5>
                    <div class="text-start">
                        @include('tools.spinner')
                    </div>
                </div>
                @if ($action == 'show-wallet')
                    @include('livewire.admins.teachers.wallet.show_wallet')
                @elseif ($action == 'add-value-to-wallet')
                    @include('livewire.admins.teachers.wallet.add_value_to_wallet')
                @else
                    @include('livewire.admins.teachers.wallet.show')
                @endif

            </div>
        </div>
    </div>
</div>
@script
    @include('tools.message')
@endscript
