<div>
    <div class="card-header text-strat">
        <div class="col-12 p-4">
            <button class="btn btn-primary btn-rounded" id="printButton" onclick="printDiv()"> <i class="fas fa-print"> </i>
                {{ __('Print') }}</button>

            <button class="btn btn-danger btn-rounded" onclick="window.history.back()">
                <i class="fas fa-arrow-left"> </i></button>
        </div>

    </div>
    <div class="card-body" id="printArea">
        <div class="row">
            @forelse ($showCodesList as $showCodesListVal)
                <div class="col-lg-3 p-2">
                    <div class="card  shadow-lg {{ $showCodesListVal->tos->color }}">
                        <div class="card-body">
                            <div class="row  ">
                                <img src="{{ asset('assets/img/mindly1.png') }}" alt="" style="width:70%; margin: auto;" />
                                   <hr>
                                <img src="data:image/svg+xml;base64,{{ $showCodesListVal->qr_code }}"
                                    class="text-center" style="width:100%; height: 10rem;" />
                                <br>
                                {{-- <hr style="border: black 2px solid "> --}}
                                <h6 class="text-center {{ $showCodesListVal->tos->color }}">{{ $showCodesListVal->code }}</h6>
                                <h6 class="text-center {{ $showCodesListVal->tos->color }}">{{ $showCodesListVal->tos->name }}</h6>
                            </div>
                            <div class="card-footer text-start">
                                <hr style="border: black 2px solid ">

                                <strong class="mb-0"
                                    style="font-size: 12px; line-height: 1.2; white-space: normal; word-wrap: break-word; max-width: 100%;">
                                    {{ $showCodesListVal->address ?? 'المحله الكبرى' }} <i
                                        class="fas fa-home me-1"></i><br>
                                </strong>
                                <strong class="mb-0"
                                    style="font-size: 12px; line-height: 1.2; white-space: normal; word-wrap: break-word; max-width: 100%;">
                                    {{ $showCodesListVal->phone ?? '0100202020101' }} <i
                                        class="fas fa-phone me-1"></i><br>
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


</div>
<script>
    function printDiv() {
        var printContents = document.getElementById('printArea').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
