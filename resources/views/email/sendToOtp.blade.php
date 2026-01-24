{{-- @extends('layouts.email')

@section('content')
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 15px; overflow: hidden; border: 1px solid #eee;">

    <div style="padding: 40px 20px; text-align: center;">
        <img src="{{ asset('assets/img/mindly_icon.png') }}" alt="Logo" style="width: 80px; margin-bottom: 20px;">

<h2 style="color: #333; font-size: 22px; margin-bottom: 10px;">رمز التحقق الخاص بك</h2>
<p style="color: #777; font-size: 16px; margin-bottom: 30px;">برجاء استخدام الرمز التالي لإتمام عملية التحقق</p>

<div style="background-color: #f8f9fe; border: 2px dashed #5e72e4; color: #5e72e4; padding: 20px; font-size: 35px; font-weight: bold; border-radius: 12px; display: inline-block; letter-spacing: 10px; min-width: 200px;">
    {{ $otp }}
</div>

<p style="color: #fb6340; font-size: 14px; margin-top: 25px; font-weight: 500;">
    <i class="far fa-clock"></i> هذا الرمز صالح لمدة 5 دقائق فقط.
</p>
</div>

<div style="background-color: #f4f5f7; padding: 20px; text-align: center; border-top: 1px solid #eee;">
    <p style="margin: 0; color: #8898aa; font-size: 12px;">
        {{ __('Copyright For') }} {{ config('app.name', 'Mindly') }} © {{ date('Y') }}
    </p>
    <p style="margin: 5px 0 0 0; color: #8898aa; font-size: 12px;">
        {{ __('Developed by') }}
        <a href="https://mahgoubtech.com" style="color: #5e72e4; text-decoration: none; font-weight: bold;" target="_blank">MahgoubTech</a>
    </p>
</div>
</div>
@endsection --}}


@extends('layouts.email')

@section('content')
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 15px; overflow: hidden; border: 1px solid #eee; direction: rtl;">

    <div style="padding: 40px 20px; text-align: center;">
        <img src="{{ asset('assets/img/mindly_icon.png') }}" alt="Logo" style="width: 80px; margin-bottom: 20px;">

        <h2 style="color: #333; font-size: 22px; margin-bottom: 10px;">رمز التحقق الخاص بك</h2>
        <p style="color: #777; font-size: 15px; margin-bottom: 25px;">إلمس الرمز بالأسفل لنسخه بسهولة:</p>

        <div style="background-color: #f8f9fe;
                    border: 2px dashed #5e72e4;
                    color: #5e72e4;
                    padding: 20px;
                    font-size: 35px;
                    font-weight: bold;
                    border-radius: 12px;
                    display: inline-block;
                    letter-spacing: 10px;
                    min-width: 200px;
                    -webkit-user-select: all;
                    user-select: all;
                    cursor: pointer;" title="إضغط لتحديد الرمز">
            {{ $otp }}
        </div>

        <div style="margin-top: 20px;">
            <span style="background-color: #5e72e4;
                         color: white;
                         padding: 8px 20px;
                         border-radius: 5px;
                         font-size: 14px;
                         display: inline-block;
                         text-decoration: none;">
                <i class="far fa-copy"></i> إضغط مطولاً للنسخ
            </span>
        </div>

        <p style="color: #fb6340; font-size: 14px; margin-top: 25px; font-weight: 500;">
            هذا الرمز صالح لمدة 5 دقائق فقط.
        </p>
    </div>

    <div style="background-color: #f4f5f7; padding: 20px; text-align: center; border-top: 1px solid #eee;">
        <p style="margin: 0; color: #8898aa; font-size: 12px;">
            {{ __('Copyright For') }} {{ config('app.name', 'Mindly') }} © {{ date('Y') }}
        </p>
        <p style="margin: 5px 0 0 0; color: #8898aa; font-size: 12px;">
            {{ __('Developed by') }}
            <a href="https://mahgoubtech.com" style="color: #5e72e4; text-decoration: none; font-weight: bold;" target="_blank">MahgoubTech</a>
        </p>
    </div>
</div>
@endsection
