{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta title="Mindly" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/mindly_icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/mindly_icon.png') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="col-12">
                        <a href="{{ app()->getLocale() == 'ar' ? LaravelLocalization::getLocalizedURL('en', null, [], true) : LaravelLocalization::getLocalizedURL('ar', null, [], true) }}"
                            class="btn bg-gradient-primary">
                            <i class="fa-solid fa-language"></i> &nbsp;
                            {{ app()->getLocale() == 'ar' ? 'ع' : 'EN' }}
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            {{ $slot }}
                        </div>
                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="background-image: url('{{ asset('assets/img/mindly.png') }}'); background-size: cover;">
                                <span class="mask bg-gradient-primary opacity-1"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <div class="modal fade" id="imagePopupModal" tabindex="-1" aria-labelledby="imagePopupModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-body text-center p-4">
                        <img src="{{ asset('assets/img/mindly_icon.png') }}" id="popupImageDisplay"
                            class="img-fluid rounded shadow" alt="Preview Image"
                            style="max-height: 70vh; object-fit: fill;">
                    </div>
                </div>
            </div>
        </div>




        <script>
            function showImagePopup(imageUrl = null, title = null) {
                var labelEl = document.getElementById('imagePopupModalLabel');
                if (title && labelEl) {
                    labelEl.innerText = title;
                }
                var imgEl = document.getElementById('popupImageDisplay');
                if (imageUrl && imgEl) {
                    imgEl.src = imageUrl;
                }
                if (typeof bootstrap !== 'undefined') {
                    var imageModal = new bootstrap.Modal(document.getElementById('imagePopupModal'));
                    imageModal.show();
                } else {
                    console.error('Bootstrap is not defined!');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    showImagePopup();
                }, 1000);
            });
        </script>

    </main>



    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.1.0') }}"></script>




</body>

</html> --}}


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta title="Mindly" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/mindly_icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/mindly_icon.png') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!--      Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="col-12">
                        <a href="{{ app()->getLocale() == 'ar' ? LaravelLocalization::getLocalizedURL('en', null, [], true) : LaravelLocalization::getLocalizedURL('ar', null, [], true) }}"
                            class="btn bg-gradient-primary">
                            <i class="fa-solid fa-language"></i> &nbsp;
                            {{ app()->getLocale() == 'ar' ? 'ع' : 'EN' }}
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            {{ $slot }}
                        </div>
                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="background-image: url('{{ asset('assets/img/mindly.png') }}'); background-size: cover;">
                                <span class="mask bg-gradient-primary opacity-1"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!--    Core JS Files    -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard -->
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.1.0') }}"></script>


</body>

</html>
