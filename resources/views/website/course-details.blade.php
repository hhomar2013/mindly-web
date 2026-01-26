@extends('layouts.landing')
@section('content')
<style>
    .banner-section {
        padding: 20px 0;
    }

    .carousel-item {
        padding: 0 15px;
    }

    .carousel-item img {
        height: 220px;
        object-fit: cover;
        border-radius: var(--border-radius);
        /* box-shadow: 0    10px 20px rgba(0, 0, 0, 0.05); */
    }

    /* 2. Tabs Design */
    .nav-pills .nav-link {
        color: #636e72;
        border-radius: 12px;
        font-weight: 500;
        margin-right: 10px;
        padding: 10px 20px;
    }

    .nav-pills .nav-link.active {
        background-color: var(--primary-red);
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }

    /* 3. Course Details */
    .course-title {
        font-weight: 800;
        color: var(--text-dark);
        margin-top: 15px;
    }

    .teacher-name {
        color: #b2bec3;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .course-description {
        line-height: 1.8;
        color: #636e72;
    }

    /* 4. Accordion (Lessons) */
    .accordion-item {
        border: none;
        margin-bottom: 10px;
        border-radius: 15px !important;
        overflow: hidden;
    }

    .accordion-button {
        background: #fff;
        font-weight: 600;
        border-radius: 15px !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #eef5ff;
        color: var(--primary-red);
    }

    .lesson-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
    }

    .lesson-item i {
        margin-left: 10px;
        color: var(--primary-red);
    }

    /* 5. Reviews Card */
    .review-card {
        background: #fff;
        border-radius: 18px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #f1f1f1;
    }

    .student-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .stars {
        color: #fdcb6e;
        font-size: 0.8rem;
    }

    /* حاوية التابات للسماح بالسحب في الموبايل */
    .tabs-wrapper {
        border-bottom: 2px solid #eee;
        background: #fff;
        position: sticky;
        /* يخلي التابات ثابتة فوق وأنت بتعمل سكرول */
        top: 0;
        z-index: 1000;
        margin: 0 -15px;
        /* عشان تاخد عرض الشاشة في الموبايل */
        padding: 0 15px;
    }

    .nav-tabs-mindly {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        /* سحب أفقي لو التابات كتير */
        white-space: nowrap;
        border: none;
        scrollbar-width: none;
        /* إخفاء السكرول بار للمتصفحات الحديثة */
    }

    .nav-tabs-mindly::-webkit-scrollbar {
        display: none;
        /* إخفاء السكرول بار للكروم */
    }

    .nav-tabs-mindly .nav-item {
        margin-right: 20px;
    }

    .nav-tabs-mindly .nav-link {
        border: none;
        background: none;
        padding: 15px 5px;
        color: #7f8c8d;
        font-weight: 600;
        position: relative;
        transition: all 0.3s ease;
    }

    /* الخط السفلي للتاب النشط */
    .nav-tabs-mindly .nav-link.active {
        color: var(--primary-red) !important;
        background: none;
    }

    .nav-tabs-mindly .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--primary-red);
        border-radius: 10px 10px 0 0;
    }

    .nav-tabs-mindly .nav-link:hover {
        color: var(--primary-red) !important;
    }

    /* التنسيق الأساسي للحاوية */
    .tabs-wrapper {
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
        margin: 15px -15px;
        /* عشان تملأ عرض الموبايل */
        padding: 0 15px;
    }

    .nav-tabs-mindly {
        display: flex;
        border: none;
        gap: 25px;
        /* مسافة بين التابات */
    }

    .nav-tabs-mindly .nav-link {
        border: none;
        background: none;
        padding: 12px 0;
        /* الحالة العادية: أسود وواضح */
        color: #000000 !important;
        font-weight: 700;
        /* خط سميك */
        font-size: 1rem;
        opacity: 0.8;
        /* شفافية بسيطة عشان تفرق عن الـ Active */
        position: relative;
        transition: all 0.2s ease-in-out;
    }

    /* الحالة النشطة: أزرق */
    .nav-tabs-mindly .nav-link.active {
        color: var(--primary-red) !important;
        /* لون Mindly الأزرق */
        opacity: 1;
        /* وضوح كامل */
    }

    /* الخط السفلي عند النشاط */
    .nav-tabs-mindly .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: -1px;
        /* يلمس الخط الفاصل السفلي */
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--primary-red);
        border-radius: 20px 20px 0 0;
    }

    /* تأثير عند تمرير الماوس */
    .nav-tabs-mindly .nav-link:hover {
        color: var(--primary-red) !important;
        opacity: 1;
    }

</style>

<div class="container pb-5">
    <section class="banner-section">
        <div id="courseSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset($courseOverView->image) }}" class="w-100" alt="Banner 1">
                </div>
            </div>
        </div>
    </section>

    <div class="tabs-wrapper mb-4">
        <ul class="nav nav-tabs-mindly" id="courseTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#info">نظرة عامة</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="lessons-tab" data-bs-toggle="pill" data-bs-target="#lessons">الدروس المتاحة</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="pill" data-bs-target="#reviews">المراجعات والردود</button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">

        <div class="tab-pane fade show active" id="info">
            <h2 class="course-title">{{ $courseOverView->name }}</h2>
            <p class="teacher-name">{{ __('By') }} : {{ $courseOverView->teacher->name }}</p>
            <div class="course-description">
                {!! $courseOverView->description !!}
            </div>
        </div>

        <div class="tab-pane fade" id="lessons">
            <div class="accordion" id="accordionLessons">
                @foreach ($courseLessons as $key => $courseLesson)
                <div class="accordion-item shadow-sm mb-3" style="border: none; border-radius: 15px; overflow: hidden;">
                    <h2 class="accordion-header" id="heading-{{ $key }}">
                        <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#unit-{{ $key }}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}">
                            <div class="d-flex flex-column">
                                <span style="font-weight: 700; color: #000;">{{ $courseLesson->name }}</span>
                                {{-- <small class="text-muted" style="font-size: 0.75rem; font-weight: 400;">
                                    {{ __('Last Update') }}: {{ $courseLesson->updated_at->format('d M, Y') }}
                                </small> --}}
                            </div>
                        </button>
                    </h2>


                    <div id="unit-{{ $key }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $key }}" data-bs-parent="#accordionLessons">
                        <div class="accordion-body p-0">
                            @foreach ($courseLesson->CourseLessonContent as $val)
                            <div class="lesson-item d-flex align-items-center p-3 border-bottom" style="background: #fff;">
                                <i class="bi bi-play-circle-fill me-3" style="color: #4A90E2; font-size: 1.2rem;"></i>
                                <span style="font-weight: 500; color: #333;">{{ $val->name }}</span>
                                <i class="bi bi-chevron-left ms-auto text-muted" style="font-size: 0.8rem;"></i>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="tab-pane fade" id="reviews">
            @forelse ($reviews as $review)
            <div class="review-card shadow-sm d-flex align-items-center bg-light p-3 mb-3">
                <img src="{{  $review->student->image ?
                asset($review->student->image) :
                asset('assets/img/mindly_bg.jpg')}}" class="student-img p-1" alt="Student">
                <div>
                    <h6 class="mb-1">{{ $review->student->name }}</h6>
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->star_number)
                            <i class="fas fa-star"></i>
                            @else
                            <i class="far fa-star"></i>
                            @endif
                            @endfor
                    </div>
                    <p class="small text-secondary mb-0">{{ $review->content }}</p>
                </div>
            </div>
            @empty
            <div class="review-card shadow-sm d-flex align-items-center bg-danger p-3 mb-3">
                <p class="small text-white mb-0">{{ __('No reviews available for this course.') }}</p>
            </div>
            @endforelse

        </div>

    </div>
</div>
@endsection
