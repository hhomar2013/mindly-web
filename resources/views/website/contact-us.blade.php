@extends('layouts.landing')

@section('content')
<section id="contact" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ __('Contact Us') }}</h2>
            <p class="text-muted">{{ __('We are always happy to respond to your inquiries and suggestions') }}</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center">
                    <a style="text-decoration: none" href="tel:{{ $mainData['phone'] }}" class="text-danger">
                        <div class="icon-box mb-3 mx-auto bg-primary-light p-3 rounded-circle" style="width: 70px;">
                            <i class="fas fa-phone-alt fa-2x text-danger"></i>
                        </div>
                        <h5>{{ __('phone') }}</h5>
                        <p class="text-muted">{{ $mainData['phone'] }}</p>
                    </a>

                    <a href="mailto:{{ $mainData['email'] }}" class="text-danger" style="text-decoration: none">
                        <div class="icon-box mb-3 mt-4 mx-auto bg-primary-light p-3 rounded-circle" style="width: 70px;">
                            <i class="fas fa-envelope fa-2x text-danger"></i>
                        </div>
                        <h5>{{ __('Email') }}</h5>
                        <p class="text-muted">{{ $mainData['email'] }}</p>
                    </a>


                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4">
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Enter your name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input type="email" name="email" class="form-control" placeholder="example@mail.com">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Message title') }}</label>
                            <input type="text" name="subject" class="form-control" placeholder="{{ __('Enter message title') }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">{{ __('Message') }}</label>
                            <textarea class="form-control" name="message" rows="4" placeholder="{{ __('How can we help you ?') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger px-5 py-2 fw-bold">
                            <i class="fa fa-paper-plane"></i>
                            {{ __('Send') }} </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
