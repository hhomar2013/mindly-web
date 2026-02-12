<style>
    .navbar-vertical.bg-white .navbar-nav>.nav-item .nav-link.active {
        background-color: #a4adea !important;
        font-weight: bold;
        /* color: white !important; */
    }
</style>
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3
fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/">
            <img src="{{ asset('assets/img/mindly_icon.png') }}" class="navbar-brand-img" alt="main_logo">
            <span class="ms-1 font-weight-bold"><b>{{ config('app.name') }}</b></span>
        </a>

    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is(app()->getLocale() . '/dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is(app()->getLocale() . '/profile') ? 'active' : '' }}"
                    href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ __('Profile') }}</span>
                </a>
            </li>

            @can('Teacher Managment')
                {{-- Teacher Titel --}}
                <li class="nav-item mt-3">
                    <h6 class="p-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                        <i class="fa-solid fa-graduation-cap"></i>&nbsp;
                        {{ __('Teacher Management') }}
                    </h6>
                </li>{{-- End Teacher Titel --}}
                {{-- Techer Management --}}
                <li class="nav-item">
                    @can('teachers')
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/teachers') ? 'active' : '' }}"
                            href="{{ route('admins.teachers.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                @if (request()->is(app()->getLocale() . '/teachers'))
                                    <i class="fa-regular fa-circle-dot text-dark text-sm opacity-10"></i>
                                @else
                                    <i class="fa-regular fa-circle text-dark text-sm opacity-10"></i>
                                @endif
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Teachers') }}</span>
                        </a>
                    @endcan
                    {{-- End teachers --}}
                    @can('teacher-wallets')
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/teacher-wallets') ? 'active' : '' }}"
                            href="{{ route('admins.teacher-wallets.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                @if (request()->is(app()->getLocale() . '/teacher-wallets'))
                                    <i class="fa-regular fa-circle-dot text-dark text-sm opacity-10"></i>
                                @else
                                    <i class="fa-regular fa-circle text-dark text-sm opacity-10"></i>
                                @endif
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Teacher Wallets') }}</span>
                        </a>
                    @endcan
                    {{-- End teacher Wallets --}}

                    @can('teacher-courses')
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/teacher-courses') ? 'active' : '' }}"
                            href="{{ route('admins.teacher-courses.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                @if (request()->is(app()->getLocale() . '/teacher-courses'))
                                    <i class="fa-regular fa-circle-dot text-dark text-sm opacity-10"></i>
                                @else
                                    <i class="fa-regular fa-circle text-dark text-sm opacity-10"></i>
                                @endif
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Teacher Courses') }}</span>
                        </a>
                    @endcan
                    {{-- End Teacher Courses --}}

                    @can('quiz')
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/quiz') ? 'active' : '' }}"
                            href="{{ route('admins.quiz.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                @if (request()->is(app()->getLocale() . '/quiz'))
                                    <i class="fa-regular fa-circle-dot text-dark text-sm opacity-10"></i>
                                @else
                                    <i class="fa-regular fa-circle text-dark text-sm opacity-10"></i>
                                @endif
                            </div>
                            <span class="nav-link-text ms-1"><i class="fa-regular fa-file-pdf"></i>{{ __('Quiz') }}</span>
                        </a>
                    @endcan
                    <!-- End Quiz -->
                </li>{{-- End Teacher Management --}}
            @endcan
            @can('educational-center-managment')
                {{-- Educational Centers Management Title --}}
                <li class="nav-item mt-3">
                    <h6 class="p-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                        <i class="fa-solid fa-graduation-cap"></i>&nbsp;
                        {{ __('Educational Centers Management') }}
                    </h6>
                </li>{{-- End Educational Centers Management Title --}}
                <li class="nav-item">
                    @can('educational-center')
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/educational-center') ? 'active' : '' }}"
                            href="{{ route('admins.educationalCenters.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                @if (request()->is(app()->getLocale() . '/educational-center'))
                                    <i class="fa-regular fa-circle-dot text-dark text-sm opacity-10"></i>
                                @else
                                    <i class="fa-regular fa-circle text-dark text-sm opacity-10"></i>
                                @endif
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Educational Centers') }}</span>
                        </a>
                    @endcan
                    <!-- End Educational Center -->
                    @can('educational-center-wallets')
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/educational-center-wallets') ? 'active' : '' }}"
                            href="{{ route('admins.educational-center-wallets.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                @if (request()->is(app()->getLocale() . '/educational-center-wallets'))
                                    <i class="fa-regular fa-circle-dot text-dark text-sm opacity-10"></i>
                                @else
                                    <i class="fa-regular fa-circle text-dark text-sm opacity-10"></i>
                                @endif
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Educational Center Wallets') }}</span>
                        </a> <!-- End Education Center Wallets -->
                    @endcan
                </li><!-- End Educational Centers Management -->
            @endcan
            @can('code-list')
                {{-- Code List Managemenrt --}}
                <li class="nav-item mt-3">
                    <h6 class="p-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                        <i class="fa-solid fa-qrcode"></i>&nbsp;
                        {{ __('Codes lists management') }}
                    </h6>
                </li>{{-- End Code List Management --}}

                <li class="nav-item">
                    <a class="nav-link {{ request()->is(app()->getLocale() . '/code-list') ? 'active' : '' }}"
                        href="{{ route('admins.code-list.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                            @if (request()->is(app()->getLocale() . '/code-list'))
                                <i class="fa-regular fa-circle-dot text-dark text-sm opacity-10"></i>
                            @else
                                <i class="fa-regular fa-circle text-dark text-sm opacity-10"></i>
                            @endif
                        </div>
                        <span class="nav-link-text ms-1">{{ __('Code List') }}</span>
                    </a> <!-- End Code List -->

                </li><!-- End Code List Management -->

            @endcan
            @can('settings')
                {{-- Settings Title --}}
                <li class="nav-item mt-3">
                    <h6 class="p-2 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                        <i class="fa-solid fa-gears"></i>&nbsp;
                        {{ __('Settings') }}
                    </h6>
                </li><!-- Settings Title -->
                @can('users')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/users') ? 'active' : '' }}"
                            href="{{ route('admins.users.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-users text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Users') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Users -->
                @can('countries')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/countries') ? 'active' : '' }}"
                            href="{{ route('admins.countries.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Countries') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Countries -->
                @can('governorate')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/governorate') ? 'active' : '' }}"
                            href="{{ route('admins.governorate.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Governors') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Governors -->
                @can('cities')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/cities') ? 'active' : '' }}"
                            href="{{ route('admins.cities.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('cities') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Cities -->
                @can('social-media-types')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/social-media-types') ? 'active' : '' }}"
                            href="{{ route('admins.socialMediaTypes.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                {{-- <i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i> --}}
                                <i class="fa-brands fa-instagram text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Social Media Types') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Social Media Types -->
                @can('content-types')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/content-types') ? 'active' : '' }}"
                            href="{{ route('admins.contentTypes.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-lines-leaning text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Content Types') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Content Types -->
                @can('subjects')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/subjects') ? 'active' : '' }}"
                            href="{{ route('admins.subjects.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-lines-leaning text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Subjects') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Subjects -->
                @can('type-of-subscriptions')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/type-of-subscriptions') ? 'active' : '' }}"
                            href="{{ route('admins.tos.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-file-invoice-dollar text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Type of Subscriptions') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Type of Subscription -->
                @can('ads')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/ads') ? 'active' : '' }}"
                            href="{{ route('admins.ads.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                <i class="fa-brands fa-adversal text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Ads') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Ads -->
                @can('TermsAndCondetions')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is(app()->getLocale() . '/TermsAndCondetions') ? 'active' : '' }}"
                            href="{{ route('admins.termsAndCondetions.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center ms-1 d-flex align-items-center justify-content-center">
                                {{-- <i class="fa fa-sacale-balanced text-dark text-sm opacity-10"></i> --}}
                                <i class="fa-solid fa-scale-balanced text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Terms And Condetions') }}</span>
                        </a>
                    </li>
                @endcan
                <!-- End Terms conditions -->
            @endcan
            {{-- End Settings --}}
        </ul>
    </div>

</aside>
