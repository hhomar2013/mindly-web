@extends('layouts.landing')
@section('content')

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($banners as $index => $banner)
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}">
        </button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach ($banners as $banner)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100 h-100 object-fit-cover" alt="Banner Image">
            <div class="carousel-caption">
                {{-- <h2 class="text-dark" data-key="heroTitle">{{ $banner->comment ?? 'Unlock Your Future' }}</h2> --}}
                {{-- <p class="fs-5" data-key="heroSub">{{ $banner->description ?? 'Join thousands of students learning from the best experts.' }}</p> --}}
            </div>
        </div>
        @endforeach
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<section class="container py-5 mt-5" id="teachers">
    <div class="text-center mb-5 reveal">
        <h2 class="fw-bold" data-key="topTeachers">{{ __('Top Rated Teachers') }}</h2>
        <div class="mx-auto" style="width: 80px; height: 4px; background: var(--primary-red);"></div>
    </div>
    <div class="row">

        @foreach ($teachers as $teacher)
        <div class="col-md-3 reveal">
            <div class="card custom-card">
                <img src="{{ asset('storage/' . $teacher->image)  }}" class="card-img-top" style="height: 300px; width: auto;" alt="Teacher">
                <div class="card-body text-center">
                    <h5 class="fw-bold">{{ $teacher->name }}</h5>
                    <p class="text-muted"></p>
                    <a href="{{ route('website.teacher.profile',['id'=>$teacher->id]) }}" class="btn btn-main">{{ __('Show Profile') }}</a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>

<section class="container py-5 mt-5" id="centers">
    <div class="text-center mb-5 reveal">
        <h2 class="fw-bold" data-key="centers">{{ __('Educational Centers') }}</h2>
        <div class="mx-auto" style="width: 80px; height: 4px; background: var(--primary-red);"></div>
    </div>
    <div class="row">
        <div class="col-md-4 reveal">
            <div class="card custom-card">
                <img src="{{ asset('assets/img/mindly_bg.jpg') }}" class="card-img-top" alt="Teacher">
                <div class="card-body text-center">
                    <h5 class="fw-bold">Mindly Center</h5>
                    {{-- <p class="text-muted">Mathematics Expert</p> --}}
                    <a href="teacher-profile.html" class="btn btn-main" data-key="showProfile">{{ __('Show Profile') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
