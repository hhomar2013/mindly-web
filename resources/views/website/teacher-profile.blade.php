@extends('layouts.landing')
@section('content')
<section id="profile-preview" class="pb-5">
    <div class="profile-banner">
        <img src="{{ asset('storage/' . $teacher->banner) }}" class="w-100 h-100 object-fit-cover" alt="banner">
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
                    {{-- <button class="btn btn-main px-5">Book Now</button>
                    <button class="btn btn-outline-danger px-5">Message</button> --}}
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
