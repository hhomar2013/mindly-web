@extends('layouts.landing')
@section('css')
<style>
    /* خلفية خفيفة للأيقونات */
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }

    /* تحسين شكل الـ Inputs */
    .form-control {
        padding: 12px;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    .form-control:focus {
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }

    /* أنيميشن بسيط */
    section {
        transition: all 0.5s ease-in-out;
    }

    .card {
        transition: transform 0.3s ease;
        border-radius: 15px;
    }

    .card:hover {
        transform: translateY(-5px);
    }

</style>
@endsection
@section('content')
<section id="about" class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="position-relative">
                    <img src="{{ asset('assets/img/mindly_bg.jpg') }}" class="img-fluid rounded-4 shadow" alt="About Us">
                    <div class="position-absolute bottom-0 start-0 bg-primary text-white p-3 rounded-end shadow-lg d-none d-md-block">
                        <span class="fs-4 fw-bold">+10</span>
                        <p class="mb-0 small">سنوات من الخبرة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 px-md-5">
                <h6 class="text-primary fw-bold text-uppercase" data-key="aboutSub">من نحن</h6>
                <h2 class="display-5 fw-bold mb-4" data-key="aboutTitle">نحن شريكك في رحلة النجاح التعليمي</h2>
                <p class="text-muted fs-5 mb-4">
                    منصتنا تهدف لربط الطلاب بأفضل المعلمين والمراكز التعليمية في مصر، لتوفير تجربة تعليمية سلسة ومتقدمة.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> نخبة من أفضل المعلمين المعتمدين.</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> مراكز تعليمية مجهزة بأحدث الوسائل.</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> دعم فني متاح على مدار الساعة.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
