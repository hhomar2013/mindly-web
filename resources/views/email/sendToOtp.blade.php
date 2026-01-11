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

{{-- @extends('layouts.email')

@section('content')
    <div style="text-align: center;">
        <img src="https://mindlyedu.com/assets/img/mindly_icon.png" alt="Logo" style="width: 100px;">

        <h2 style="color: #333; margin-top: 20px;">رمز التحقق الخاص بك</h2>

        <div
            style="background-color: #5e72e4; color: white; padding: 15px; font-size: 32px; font-weight: bold; border-radius: 5px; display: inline-block; margin: 20px 0; letter-spacing: 5px;">
            {{ $otp }}
        </div>

        <p style="color: #666;">هذا الرمز صالح لمدة 5 دقائق فقط.</p>
    </div>
@endsection --}}
