<div class="card-header text-strat">
    {{-- <button class="btn btn-success" wire:click="createCL">
        <i class="fa fa-plus"></i>
        {{ __('Add Code List') }}</button> --}}
</div>
<div class="card-body">
    <div class="row">
        @forelse ($showCodesList as $showCodesListVal)
            <div class="col-lg-3 p-2">
                <div class="card  shadow-lg">
                    <div class="card-body text-center">
                        <div class="row  ">
                            <img src="{{ asset('assets/img/mindly.png') }}" alt="" style="width:15rem; height: 15rem;" />
                            <img src="data:image/svg+xml;base64,{{ $showCodesListVal->qr_code }}" class="text-center"
                                style="width:15rem; height: 10rem;" />
                            <strong class="text-center">{{ $showCodesListVal->code }}</strong>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-12">
                <div class="card bg-danger">
                    <div class="card-body  text-center text-white">
                        <h5 class="card-title">{{ __('No Code Found') }}</h5>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
