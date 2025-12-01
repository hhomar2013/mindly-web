@extends('layouts.email')

@section('content')
    <h2>رمز التحقق الخاص بك:</h2>
    <h1>{{ $otp }}</h1>
    <p>هذا الرمز صالح لمدة 5 دقائق فقط.</p>
@endsection
