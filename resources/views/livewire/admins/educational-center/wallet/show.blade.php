{{-- <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title">
        <button class="btn btn-success btn-rounded" wire:click.prevent="createCenter()">
            <i class="fa-solid fa-plus"></i>
            {{ __('') }}</button>
    </h5>
</div> --}} 
<div class="row">
    @forelse ($center_wallets as $center_wallet)
        <div class="col-lg-3 col-md-4 p-3">
            <div class="card shadow bg-gradient-danger text-white">
                <div class="card-body text-center">
                    <img class="bg-white" src="{{ asset('storage/' . $center_wallet->center->logo) }}"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">

                    <h5 class="card-title">{{ $center_wallet->center->name }}</h5>
                    <h4 class="text-bold text-white"> {{ $center_wallet->balance }} {{ __('EGP') }} </h4>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success btn-sm" wire:click.prevent="showWallet({{ $center_wallet->id }})">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    <button class="btn btn-dark btn-sm" wire:click.prevent="addValueToWllet({{ $center_wallet->center->id }})">
                        {{ __('Recharge balance') }}
                        <i class="fa-solid fa-coins"></i>
                    </button>
                </div>
            </div>
        </div>

    @empty
        <div class="col-lg-12 p-4">
            <div class="card bg-danger text-white text-center">
                <div class="card-body">
                    <h5 class="card-title">{{ __('none results') }}</h5>
                </div>
            </div>
        </div>
    @endforelse

    <div class="card-footer">
        <div class="pagination">
            {{-- {{ $centers->links() }} --}}
        </div>
    </div>
</div>
