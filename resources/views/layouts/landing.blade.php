<!DOCTYPE html>
<html lang="en" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() == 'ar' ? 'مايندلي' :'Mindly' }}</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/mindly_icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/mindly_icon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/landing_css.css') }}">

    @yield('css')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            @if ( app()->getLocale() == 'ar')
            <a class="navbar-brand fw-bold fs-3" href="{{ route('website.home') }}">ما<span class="text-white-50">يندلي</span></a>
            @else
            <a class="navbar-brand fw-bold fs-3" href="{{ route('website.home') }}">Mi<span class="text-white-50">ndly</span></a>

            @endif

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item "><a class="nav-link {{ request()->routeIs('website.home') ? 'active' : '' }}" href="{{ route('website.home') }}" data-key="home">{{ __('Home') }}</a></li>
                    <li class="nav-item"><a class="nav-link " href="#teachers" data-key="teachers">{{ __('Teachers') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#centers" data-key="centers">{{ __('Educational Centers') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.about.us') ? 'active' : '' }}" href="{{ route('website.about.us') }}" data-key="about">{{ __('About Us') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.contact.us') ? 'active' : '' }}" href="{{ route('website.contact.us') }}" data-key="contact">{{ __('Call Us') }}</a></li>
                </ul>

                <a href="{{ app()->getLocale() == 'ar' ? LaravelLocalization::getLocalizedURL('en', null, [], true) : LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" class="lang-switch" style="text-decoration: none;">
                    {{-- <i class="fa-solid fa-language"></i> &nbsp; --}}
                    {{ app()->getLocale() == 'ar' ? 'ع' : 'EN' }}
                </a>
                {{-- <button class="lang-switch" onclick="toggleLanguage()" id="langBtn">AR</button> --}}
            </div>
        </div>
    </nav>
    @yield('content')


    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    @if (app()->getLocale() == 'ar')
                    <h5 class="fs-3">ما<span class="text-white-50">يندلي</span></h5>
                    @else
                    <h5 class="fs-3">Mi<span class="text-white-50">ndly</span></h5>

                    @endif

                    {{-- <p>Leading the way in digital education across the Middle East. Excellence in every lesson.</p> --}}
                </div>
                <div class="col-md-3 mb-4 ">
                    <h5 data-key="quickLinks">{{ __('Quick Links') }}</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="#">{{ __('Terms of Service') }}</a></li>
                        <li><a href="#">{{ __('Support Center') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 data-key="contact">{{ __('Contact Info') }}</h5>
                    <p><i class="fas fa-phone me-2"></i> <a class="text-white" href="tel:{{ $mainData['phone'] }}">{{ $mainData['phone'] }}</a></p>
                    <p><i class="fas fa-envelope me-2"></i> <a class="text-white" href="MailTo: {{ $mainData['email'] }}"> {{ $mainData['email'] }}</a></p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> {{ $mainData['address'] }}</p>
                    <div class="d-flex gap-3">
                        <a href="#"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-3 mb-4 d-flex align-items-end">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="#">
                            <img src="{{ asset('assets/img/platform/android.png') }}" class="platform-img" alt="Google Play">
                        </a>
                        <a href="#">
                            <img src="{{ asset('assets/img/platform/apple.png') }}" class="platform-img" alt="App Store">
                        </a>
                    </div>
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center pt-2">
                <small>&copy; {{ Carbon\Carbon::now()->format('Y') }}
                    {{ app()->getLocale() == 'ar' ? 'مايندلي' : 'Mindly' }}
                    {{ __('All rights reserved') }}.
                    {{ __('Developed by') }} <b><a href="">MTG .</a></b>
                </small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/js/landing_js.js') }}"></script>
</body>

</html>
