@extends('layouts.email')

@section('content')
    <div class="text-center">
        <img src="{{ asset('assets/img/mindly_icon.png') }}" alt="">
        <br>
        <h2>رمز التحقق الخاص بك</h2>
        <h1>{{ $otp }}</h1>
        <p>هذا الرمز صالح لمدة 5 دقائق فقط.</p>
    </div>
    
    <br>
    <div class="sticky-bottom  text-center">
        <div class="row">
            <div class="mb-4 p-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    {{ __('Copyright For') }} {{ config('app.name' ?? 'Mindly') }} ©
                    <?php echo date('Y'); ?>,
                    {{ __('Developed by') }} <a href="" class="font-weight-bold" target="_blank">MahgoubTech</a>

                </div>
            </div>
        </div>
    </div>
@endsection
