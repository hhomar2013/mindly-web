<div class="card-header text-strat">
    {{-- <button class="btn btn-success" wire:click="createCL">
        <i class="fa fa-plus"></i>
        {{ __('Add Code List') }}</button> --}}

    <div class="col-12 p-4">
        <a class="btn btn-primary btn-rounded" href="{{ route('pdf.code-list', ['id' => $selectedCodeLitsId]) }}"> <i
                class="fas fa-eye"> </i>
            {{ __('view') }}</a>
    </div>
</div>
<div class="card-body">
    <div class="row">
        @forelse ($showCodesList->where('is_used',0) as $showCodesListVal)
            <div class="col-lg-2 p-2">
                <div class="card  shadow-lg {{ $showCodesListVal->tos->color }}">
                    <div class="card-body">
                        <div class="row ">
                            <img src="{{ asset('assets/img/mindly.png') }}" alt=""
                                style="width:14rem; height: 8rem;" />
                            <img src="data:image/svg+xml;base64,{{ $showCodesListVal->qr_code }}" class="text-center"
                                style="width:14rem; height: 8rem;" />
                            <br>
                            {{-- <hr style="border: black 2px solid "> --}}
                            <h6 class="text-center {{ $showCodesListVal->tos->color }}">{{ $showCodesListVal->code }}
                            </h6>
                            <h6 class="text-center {{ $showCodesListVal->tos->color }}">
                                {{ $showCodesListVal->tos->name }}</h6>
                        </div>
                        <div class="card-footer text-start">
                            <hr style="border: black 2px solid">

                            <strong class="mb-0"
                                style="font-size: 12px; line-height: 1.2; white-space: normal; word-wrap: break-word; max-width: 100%;">
                                {{ $showCodesListVal->address ?? 'المحله الكبرى' }} <i class="fas fa-home me-1"></i><br>
                            </strong>
                            <strong class="mb-0"
                                style="font-size: 12px; line-height: 1.2; white-space: normal; word-wrap: break-word; max-width: 100%;">
                                {{ $showCodesListVal->phone ?? '0100202020101' }} <i class="fas fa-phone me-1"></i><br>
                            </strong>
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
