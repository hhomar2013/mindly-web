@extends('layouts.landing')
@section('css')

@endsection
@section('content')
<section id="profile-preview" class="pb-5">
    <div class="profile-banner">
        <img src="{{ $teacher->banner ? asset('storage/' . $teacher->banner)  : asset('assets/img/mindly_bg.jpg') }}" class="w-100 h-100 object-fit-cover" alt="banner">
    </div>
    <div class="container">
        <div class="profile-img-container reveal">
            <img src="{{ asset('storage/'.$teacher->image) }}" class="profile-avatar" alt="Teacher">
        </div>
        <div class="row mt-4 reveal">
            <div class="col-md-8">
                <h1 class="fw-bold">{{ $teacher->name }}</h1>
                <h4 class="text-danger mb-3">{{ $teacher->cities->name }} , {{ $teacher->cities->Governorates->name }}</h4>
                <p class="lead">{{ $teacher->description }}</p>
                <div class="d-flex gap-3 mt-4">
                    <div class="star-rating">
                        @for ($i = 1; $i <= 5; $i++) @if ($i <=$teacher->rating_system)
                            <i class="fas fa-star text-danger"></i> {{-- نجمة ملونة --}}
                            @else
                            <i class="far fa-star text-secondary"></i> {{-- نجمة فارغة --}}
                            @endif
                            @endfor
                            <span class="ms-2">({{ $teacher->rating_system }}/5)</span>
                    </div>
                    {{-- <button class="btn btn-main px-5">Book Now</button>
                    <button class="btn btn-outline-danger px-5">Message</button> --}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-5">
                <h2 class="fw-bold mb-4">{{ __('Courses') }}</h2>
                <div class="row g-4">
                    @forelse ($teacher->teacherCourseOverview as $course)
                    <div class="col-md-3 reveal">
                        <div class="card custom-card">
                            <img src="{{ asset($course->image)  }}" class="card-img-top" style="height: 200px; width: auto;" alt="Course">
                            <div class="card-body text-center">
                                <h5 class="fw-bold">{{ $course->name }}</h5>
                                <p class="text-muted">{{ Str::limit($course->description, 50) }}</p>
                                <a href="{{ route('website.course.details',['id'=>$course->id]) }}" class="btn btn-main">{{ __('View Course') }}</a>
                            </div>

                        </div>
                    </div>
                    @empty
                    <p class="text-white bg-danger text-center p-5">{{ __('No courses available for this teacher.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
