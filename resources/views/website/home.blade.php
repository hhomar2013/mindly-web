@extends('layouts.landing')
@section('content')
@section('css')
    <style>
        .card-img-top {
            height: 300px;
            width: auto;
        }

        @media screen and (max-width: 768px) {
            .card-img-top {
                height: 150px;
                width: auto;
            }

            .teacher-name {
                font-size: 14px;
                font-weight: 500;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
            }
        }
    </style>
@endsection
<section class="container-fluid" id="banners">
    <div id="carouselExampleIndicators" class="carousel slide p-3" data-bs-ride="carousel">

        <div class="carousel-indicators">
            @foreach ($banners as $index => $banner)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}"
                    class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}">
                </button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach ($banners as $banner)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100 rounded-4"
                        style="height: 250px; object-fit: cover;" alt="Banner Image">
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
</section>


<section class="container py-5 mt-5" id="teachers">
    <div class="text-center mb-5 reveal">
        <h2 class="fw-bold" data-key="topTeachers">{{ __('Top Rated Teachers') }}</h2>
        <div class="mx-auto" style="width: 80px; height: 4px; background: var(--primary-red);"></div>
    </div>
    <div class="row">

        @foreach ($teachers as $teacher)
            <div class="col-6 col-md-3  reveal">
                <div class="card custom-card">
                    <img src="{{ asset('storage/' . $teacher->image) }}" class="card-img-top" alt="Teacher">
                    <div class="card-body text-center">
                        <h5 class="fw-bold teacher-name">{{ $teacher->name }}</h5>
                        <p class="text-muted"></p>
                        <a href="{{ route('website.teacher.profile', ['id' => $teacher->id]) }}"
                            class="btn btn-main btn-sm">
                            <i class="fas fa-eye"></i>
                            {{-- {{ __('Show Profile') }} --}}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</section>
@foreach ($centers as $center)
    <section class="container py-5 mt-5" id="centers">
        <div class="text-center mb-5 reveal">
            <h2 class="fw-bold" data-key="centers">{{ __('Educational Centers') }}</h2>
            <div class="mx-auto" style="width: 80px; height: 4px; background: var(--primary-red);"></div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3 reveal">
                <div class="card custom-card">
                    <img src="{{ asset('storage/' . $center->logo) }}" class="card-img-top" alt="Center">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">{{ $center->name }}</h5>
                        {{-- <p class="text-muted">Mathematics Expert</p> --}}
                        <a href="#/centers" class="btn btn-main btn-sm" data-key="showProfile">
                            <i class="fas fa-eye"></i>
                            {{-- {{ __('Show Profile') }} --}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endforeach
@endsection
