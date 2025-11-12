<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            {{-- <nav aria-label="breadcrumb">

                <h6 class="font-weight-bolder text-white mb-0">{{ __('Dashboard') }}</h6>
            </nav> --}}
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    {{-- <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" placeholder="Type here...">
                    </div> --}}
                </div>
                <ul class="navbar-nav  justify-content-end">


                    <li class="nav-item d-flex align-items-center ps-2">
                        @guest
                        <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                            <i class="fa-solid fa-key"></i>
                            <span class="d-sm-inline d-none">{{ __('Signin') }}</span>
                        </a>

                        @else
                        <div class="dropdown navbar-dropdown">
                            <a href="javascript:;" class="nav-link text-white " id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-sm-inline d-none">{{ Auth::user()->name }}</span>
                                <i class="fa fa-user ms-sm-1"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            {{ __('logout') }}
                                        </button>
                                    </form>
                                    {{-- <a class="dropdown-item" href="{{ route('') }}">{{ __('logout') }}</a> --}}
                                </li>

                            </ul>
                        </div>
                        @endguest

                    </li>&nbsp;&nbsp;


                    <li class="nav-item dropdown pe-2 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell cursor-pointer"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4">
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="d-flex py-1">
                                        <div class="my-auto">
                                            <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                <span class="font-weight-bold">New message</span> from Laur
                                            </h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <i class="fa fa-clock me-1"></i>
                                                13 minutes ago
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>

                    </li>
                    &nbsp;
                    <li class="nav-item d-flex align-items-center">
                        <a href="{{ app()->getLocale() == 'ar' ? LaravelLocalization::getLocalizedURL('en', null, [], true) : LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" class="btn btn-primary m-2">
                            <i class="fa-solid fa-language"></i> &nbsp;
                            {{ app()->getLocale() == 'ar' ? 'ع' : 'EN' }}
                        </a>

                    </li>
                    &nbsp; &nbsp;
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
