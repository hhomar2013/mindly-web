@extends('layouts.landing')

@section('content')
<section class="py-5 bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm p-4 p-md-5">
                    <div class="text-center mb-5">
                        <i class="fas fa-file-contract fa-3x text-danger mb-3"></i>
                        <h1 class="fw-bold">{{ __('Platform policies and terms') }}</h1>
                        <p class="text-muted">
                            {{ __('Last Update') }} :
                            {{-- {{ isset($terms->updated_at) ? \Carbon\Carbon::parse($terms->updated_at)->format('M d, Y') : '2026' }} --}}

                            {{isset($terms->updated_at) ? \Carbon\Carbon::parse($terms->updated_at)->locale(app()->getLocale())->translatedFormat('d F, Y')  : '2026'}}

                        </p>
                    </div>

                    <div class="terms-content">
                        <div class="card">

                            <div class="card-body">
                                {!! isset($terms->content) ? $terms->content : '<p>لا توجد شروط وأحكام متاحة حالياً.</p>' !!}
                            </div>

                        </div>

                        <hr>
                        <div class="card">

                            <div class="card-body">
                                {!! isset($privacy->content) ? $privacy->content : '<p>لا توجد شروط وأحكام متاحة حالياً.</p>' !!}
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
