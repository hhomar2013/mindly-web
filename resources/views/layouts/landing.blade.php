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

    <style>
        :root {
            --primary-red: #D90429;
            --dark-red: #A4031F;
            --soft-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition-speed: 0.5s;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #ffffff;
            overflow-x: hidden;
            transition: opacity 0.5s ease-in-out;
        }

        /* --- Navbar Styles --- */
        .navbar {
            background-color: var(--primary-red) !important;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
        }

        .nav-link {
            color: white !important;
            font-weight: 700;
            margin: 0 15px;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: white;
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .lang-switch {
            background: white;
            color: var(--primary-red);
            border-radius: 50px;
            padding: 5px 20px;
            font-weight: 700;
            border: 2px solid white;
            transition: 0.3s;
        }

        .lang-switch:hover {
            background: transparent;
            color: white;
        }

        /* --- Hero Section --- */
        .carousel-item {
            height: 70vh;
            min-height: 400px;
            background: #333;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .carousel-caption {
            bottom: 30%;
            z-index: 10;
        }

        .carousel-caption h2 {
            font-size: 3.5rem;
            font-weight: 700;
            animation: fadeInUp 1s ease;
        }

        /* --- Card Styles --- */
        .custom-card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--soft-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .custom-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(217, 4, 41, 0.2);
        }

        .btn-main {
            background-color: var(--primary-red);
            color: white;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
        }

        .btn-main:hover {
            background-color: var(--dark-red);
            color: white;
            box-shadow: 0 0 15px rgba(217, 4, 41, 0.4);
        }

        /* --- Profile Page Components --- */
        .profile-banner {
            height: 350px;
            background: linear-gradient(45deg, #eee, #ddd);
            position: relative;
        }

        .profile-img-container {
            position: relative;
            margin-top: -100px;
            z-index: 5;
        }

        [dir="rtl"] .profile-img-container {
            text-align: right;
            padding-right: 50px;
        }

        [dir="ltr"] .profile-img-container {
            text-align: left;
            padding-left: 50px;
        }

        .profile-avatar {
            width: 200px;
            height: 200px;
            border: 8px solid white;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: var(--soft-shadow);
        }

        /* --- Footer --- */
        footer {
            background-color: var(--primary-red);
            color: white;
            padding: 60px 0 20px;
        }

        footer h5 {
            font-weight: 700;
            margin-bottom: 25px;
        }

        footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: 0.3s;
        }

        footer a:hover {
            color: white;
            padding-left: 5px;
        }

        /* --- Animations --- */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RTL Adjustments */
        [dir="rtl"] .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }



        /* تنسيق المؤشرات أسفل السلايدر */
        .custom-indicators {
            bottom: 20px;
            /* المسافة من الأسفل */
        }

        .custom-indicators [data-bs-target] {
            width: 40px;
            /* عرض الخط */
            height: 6px;
            /* سمك الخط */
            border-radius: 3px;
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            /* إزالة الحدود الافتراضية */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 5px;
        }

        .custom-indicators .active {
            width: 80px;
            /* يتمدد الخط عند تفعيله */
            background-color: var(--primary-red) !important;
            /* اللون الأحمر الخاص بالهوية */
        }

        /* تأثير بسيط عند التمرير بالماوس */
        .custom-indicators [data-bs-target]:hover {
            background-color: rgba(255, 255, 255, 0.8);
        }


        /* تغيير شكل الماوس ليوحي بالسحب */
        .carousel-inner {
            cursor: grab;
        }

        .carousel-inner:active {
            cursor: grabbing;
        }

    </style>
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('website.home') }}" data-key="home">{{ __('Home') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#teachers" data-key="teachers">{{ __('Teachers') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#centers" data-key="centers">{{ __('Educational Centers') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-key="about">{{ __('About Us') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-key="contact">{{ __('Call Us') }}</a></li>
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
                <div class="col-md-4 mb-4">
                    @if (app()->getLocale() == 'ar')
                    <h5 class="fs-3">ما<span class="text-white-50">يندلي</span></h5>
                    @else
                    <h5 class="fs-3">Mi<span class="text-white-50">ndly</span></h5>

                    @endif

                    {{-- <p>Leading the way in digital education across the Middle East. Excellence in every lesson.</p> --}}
                </div>
                <div class="col-md-4 mb-4 ">
                    <h5 data-key="quickLinks">{{ __('Quick Links') }}</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="#">{{ __('Terms of Service') }}</a></li>
                        <li><a href="#">{{ __('Support Center') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 data-key="contact">{{ __('Contact Info') }}</h5>
                    <p><i class="fas fa-phone me-2"></i> +123 456 789</p>
                    <p><i class="fas fa-envelope me-2"></i> info@edupremium.com</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Cairo, Egypt</p>
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

    <script>
        // --- Language Configuration ---
        const translations = {
            en: {
                home: "Home"
                , teachers: "Teachers"
                , centers: "Centers"
                , about: "About Us"
                , contact: "Call Us"
                , heroTitle: "Unlock Your Future"
                , heroSub: "Join thousands of students learning from the best experts."
                , topTeachers: "Top Rated Teachers"
                , showProfile: "Show Profile"
                , quickLinks: "Quick Links"
            }
            , ar: {
                home: "الرئيسية"
                , teachers: "المعلمون"
                , centers: "المراكز"
                , about: "من نحن"
                , contact: "اتصل بنا"
                , heroTitle: "اصنع مستقبلك الآن"
                , heroSub: "انضم لآلاف الطلاب الذين يتعلمون على يد أفضل الخبراء."
                , topTeachers: "المعلمون الأعلى تقييماً"
                , showProfile: "عرض الملف الشخصي"
                , quickLinks: "روابط سريعة"
            }
        };

        let currentLang = 'en';

        function toggleLanguage() {
            // Smooth fade out
            document.body.style.opacity = 0;

            setTimeout(() => {
                currentLang = currentLang === 'en' ? 'ar' : 'en';
                const html = document.documentElement;

                // Switch direction
                html.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
                html.lang = currentLang;

                // Update text content
                document.querySelectorAll('[data-key]').forEach(el => {
                    const key = el.getAttribute('data-key');
                    el.textContent = translations[currentLang][key];
                });

                document.getElementById('langBtn').textContent = currentLang === 'en' ? 'AR' : 'EN';

                // Smooth fade in
                document.body.style.opacity = 1;
            }, 300);
        }

        // --- Scroll Animations (Intersection Observer) ---
        const observerOptions = {
            threshold: 0.15
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // --- Ripple Effect for Buttons ---
        document.querySelectorAll('.btn-main').forEach(button => {
            button.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });


        const carousel = document.querySelector('#heroCarousel');
        const bsCarousel = new bootstrap.Carousel(carousel);

        let startX = 0;
        let endX = 0;

        // عند ضغط الماوس
        carousel.addEventListener('mousedown', (e) => {
            startX = e.pageX;
        });

        // عند ترك الماوس
        carousel.addEventListener('mouseup', (e) => {
            endX = e.pageX;
            handleSwipe();
        });

        function handleSwipe() {
            const threshold = 50; // المسافة الأدنى للسحب (بالبكسل)
            const isRTL = document.documentElement.dir === 'rtl';

            if (startX - endX > threshold) {
                // سحب لليسار
                isRTL ? bsCarousel.prev() : bsCarousel.next();
            } else if (endX - startX > threshold) {
                // سحب لليمين
                isRTL ? bsCarousel.next() : bsCarousel.prev();
            }
        }

    </script>
</body>

</html>
