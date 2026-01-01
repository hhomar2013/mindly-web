<div class="row">
    @forelse ($teacher_wallets as $teacher_wallet)
        <div class="col-lg-3 col-md-4 p-3">
            <div class="card shadow bg-gradient-danger text-white">
                <div class="card-body text-center">
                    <img class="bg-white" src="{{ asset('storage/' . $teacher_wallet->teacher->image) }}"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">

                    <h5 class="card-title">{{ $teacher_wallet->teacher->name }}</h5>
                    <h4 class="text-bold text-white"> {{ $teacher_wallet->balance }} {{ __('EGP') }} </h4>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success btn-sm" wire:click.prevent="showWallet({{ $teacher_wallet->id }})">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    <button class="btn btn-dark btn-sm"
                        wire:click.prevent="addValueToWllet({{ $teacher_wallet->teacher->id }})">
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
