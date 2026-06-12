@extends('layouts.landing')

@section('css')
    <style>
        /* Premium design variables and layout rules */
        .redeem-container {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 50% 50%, rgba(219, 4, 41, 0.03) 0%, rgba(255, 255, 255, 0) 100%), #fbfbfb;
            padding: 40px 15px;
        }

        .redeem-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(217, 4, 41, 0.08);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            padding: 40px;
            max-width: 520px;
            width: 100%;
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
        }

        .redeem-card:hover {
            
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(217, 4, 41, 0.08);
        }

        .redeem-icon {
            width: 72px;
            height: 72px;
            background-color: rgba(217, 4, 41, 0.06);
            color: var(--primary-red);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 25px;
        }

        .form-control-custom {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .form-control-custom:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 4px rgba(217, 4, 41, 0.1);
            outline: none;
        }

        .btn-action-primary {
            background-color: var(--primary-red);
            border: none;
            border-radius: 12px;
            padding: 14px 28px;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-action-primary:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(217, 4, 41, 0.3);
        }

        .btn-action-primary:active {
            transform: translateY(0);
        }

        .btn-qr-scan {
            background-color: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 0 20px;
            color: #475569;
            transition: all 0.3s ease;
        }

        /* Support RTL layout for input groups */
        html[dir="rtl"] .btn-qr-scan {
            border-left: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .btn-qr-scan:hover {
            background-color: #e2e8f0;
            color: var(--primary-red);
        }

        /* QR Scanner camera style adjustments */
        #qr-reader-container {
            position: relative;
        }

        #qr-reader {
            width: 100% !important;
            border: none !important;
            background-color: #000;
            border-radius: 16px;
            overflow: hidden;
        }

        #qr-reader video {
            border-radius: 16px;
            object-fit: cover !important;
        }

        /* Success animation overlay */
        .success-celebration {
            text-align: center;
            display: none;
        }

        .success-check-icon {
            font-size: 4.5rem;
            color: #22c55e;
            margin-bottom: 20px;
            animation: scaleUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }

        @keyframes scaleUp {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .course-card-result {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin: 25px 0;
        }

        .course-name-display {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.25rem;
            margin-top: 5px;
        }

        .deep-link-notice {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 15px;
        }

        /* Modal styling */
        .modal-content-custom {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .modal-header-custom {
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 24px;
        }

        .modal-body-custom {
            padding: 24px;
        }
    </style>
@endsection

@section('content')
    <div class="redeem-container">
        <div class="redeem-card">

            @if ($student)
                <!-- AUTHENTICATED STATE -->
                <div id="redeem-form-section">
                    <div class="redeem-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>

                    <h3 class="text-center fw-bold mb-2">{{ app()->getLocale() == 'ar' ? 'تفعيل الكود' : 'Redeem Code' }}
                    </h3>
                    <p class="text-center text-muted mb-4">
                        {{ app()->getLocale() == 'ar' ? 'مرحباً بك، ' : 'Welcome back, ' }}
                        <strong>{{ $student->name }}</strong>!
                        {{ app()->getLocale() == 'ar' ? 'أدخل كود التفعيل المطبوع لتفعيل دورتك فوراً.' : 'Enter your activation code below to unlock your course instantly.' }}
                    </p>

                    <!-- Redeem Form -->
                    <form id="redeemForm" action="{{ route('student.redeem.submit') }}" method="POST">
                        @csrf

                        <!-- Code input group with camera scan button -->
                        <div class="mb-4">
                            <label for="cardCode"
                                class="form-label fw-bold text-secondary small uppercase tracking-wider mb-2">
                                {{ app()->getLocale() == 'ar' ? 'كود التفعيل' : 'Activation Code' }}
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-custom" id="cardCode"
                                    name="cardCode" required
                                    placeholder="{{ app()->getLocale() == 'ar' ? 'مثال: 12345678901234' : 'e.g. 12345678901234' }}"
                                    autocomplete="off">
                                <button class="btn btn-qr-scan" type="button" id="startScannerBtn"
                                    title="{{ app()->getLocale() == 'ar' ? 'مسح رمز QR' : 'Scan QR Code' }}">
                                    <i class="fa-solid fa-camera fa-lg"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback d-block mt-2 fw-semibold" id="errorMessage"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-action-primary w-100 mt-2" id="submitBtn">
                            <span>{{ app()->getLocale() == 'ar' ? 'تفعيل دورتك الآن' : 'Unlock Course Now' }}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- SUCCESS STATE -->
                <div class="success-celebration" id="success-section">
                    <div class="success-check-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>

                    <h3 class="fw-bold">
                        {{ app()->getLocale() == 'ar' ? 'تم التفعيل بنجاح! 🎉' : 'Activated Successfully! 🎉' }}</h3>
                    <p class="text-muted">
                        {{ app()->getLocale() == 'ar' ? 'لقد تم إضافة هذه الدورة إلى حسابك بنجاح.' : 'This course has been successfully added to your account.' }}
                    </p>

                    <div class="course-card-result">
                        <small
                            class="text-uppercase text-secondary fw-bold tracking-wider">{{ app()->getLocale() == 'ar' ? 'الدورة المفتوحة' : 'Unlocked Course' }}</small>
                        <div class="course-name-display" id="successCourseName">--</div>
                    </div>

                    <!-- Deep Link Redirect Button -->
                    <a href="#" class="btn btn-action-primary w-100" id="successDeepLink">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'الذهاب إلى التطبيق' : 'Jump to Application' }}</span>
                    </a>

                    <p class="deep-link-notice">
                        {{ app()->getLocale() == 'ar' ? 'سيتم تشغيل تطبيق مايندلي والذهاب للدورة مباشرة.' : 'Mindly App will launch and navigate directly to the course.' }}
                    </p>
                </div>
            @else
                <!-- UNAUTHENTICATED STATE -->
                <div>
                    <div class="redeem-icon" style="background-color: rgba(217, 4, 41, 0.05); color: var(--primary-red);">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>

                    <h3 class="text-center fw-bold mb-2">
                        {{ app()->getLocale() == 'ar' ? 'يتطلب تسجيل الدخول' : 'Authentication Required' }}</h3>
                    <p class="text-center text-muted mb-4">
                        {{ app()->getLocale() == 'ar' ? 'من أجل تفعيل كود دورتك، يرجى فتح هذه الصفحة مباشرة من خلال تطبيق مايندلي.' : 'To redeem your course code, please open this link from within the Mindly mobile application.' }}
                    </p>

                    <!-- Deep link to app welcome/login page -->
                    <a href="{{ $appScheme }}://welcome" class="btn btn-action-primary w-100">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'فتح تطبيق مايندلي' : 'Open Mindly App' }}</span>
                    </a>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            {{ app()->getLocale() == 'ar' ? 'يرجى تسجيل الدخول أو إنشاء حساب في التطبيق أولاً.' : 'Please register or sign in inside the app first.' }}
                        </small>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- QR Camera Scanner Modal -->
    @if ($student)
        <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-content-custom">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold" id="qrScannerModalLabel">
                            <i class="fa-solid fa-qrcode text-danger me-2"></i>
                            {{ app()->getLocale() == 'ar' ? 'امسح رمز QR لكود التفعيل' : 'Scan Card QR Code' }}
                        </h5>
                        <button type="button" class="btn-close" id="closeScannerModalBtn" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-body-custom">
                        <div id="qr-reader-container">
                            <div id="qr-reader"></div>
                        </div>
                        <div class="text-center mt-3 text-muted small">
                            {{ app()->getLocale() == 'ar' ? 'وجه كاميرا الهاتف نحو رمز الـ QR الموجود على بطاقة الدورة.' : 'Align your camera view with the QR code printed on the course card.' }}
                        </div>
                        <div class="alert alert-warning d-none mt-3 text-center py-2" id="cameraError">
                            {{ app()->getLocale() == 'ar' ? 'لا يمكن تشغيل الكاميرا. يرجى تفعيل أذونات الكاميرا في المتصفح.' : 'Unable to access camera. Please grant camera permissions in your browser.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('js')
    @if ($student)
        <!-- html5-qrcode library for browser camera scanner -->
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const redeemForm = document.getElementById('redeemForm');
                const cardCodeInput = document.getElementById('cardCode');
                const submitBtn = document.getElementById('submitBtn');
                const errorMessage = document.getElementById('errorMessage');

                const formSection = document.getElementById('redeem-form-section');
                const successSection = document.getElementById('success-section');
                const successCourseName = document.getElementById('successCourseName');
                const successDeepLink = document.getElementById('successDeepLink');

                const qrModalEl = document.getElementById('qrScannerModal');
                const startScannerBtn = document.getElementById('startScannerBtn');
                const closeScannerModalBtn = document.getElementById('closeScannerModalBtn');
                const cameraErrorAlert = document.getElementById('cameraError');

                let html5QrcodeScanner = null;
                let qrModalInstance = null;

                if (typeof bootstrap !== 'undefined') {
                    qrModalInstance = new bootstrap.Modal(qrModalEl);
                }

                // AJAX Form Submission
                redeemForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Clear errors
                    errorMessage.textContent = '';
                    cardCodeInput.classList.remove('is-invalid');

                    // Set loading state
                    submitBtn.disabled = true;
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span>{{ app()->getLocale() == 'ar' ? 'جاري التحقق والتفعيل...' : 'Verifying...' }}</span>
            `;

                    const formData = new FormData(redeemForm);

                    fetch(redeemForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_csrf"]').value ||
                                    '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) {
                                throw new Error(data.message || 'Error occurred');
                            }
                            return data;
                        })
                        .then(data => {
                            // Animate to success state
                            formSection.style.display = 'none';
                            successSection.style.display = 'block';
                            successCourseName.textContent = data.course_name;
                            successDeepLink.href = data.deep_link;
                        })
                        .catch(error => {
                            errorMessage.textContent = error.message;
                            cardCodeInput.classList.add('is-invalid');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        });
                });

                // Initialize and Start QR Scanner
                function startQrScanner() {
                    cameraErrorAlert.classList.add('d-none');

                    // Create target instance
                    html5QrcodeScanner = new Html5Qrcode("qr-reader");

                    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                        // Fill code input
                        cardCodeInput.value = decodedText;

                        // Stop scanner and close modal
                        stopQrScanner().then(() => {
                            if (qrModalInstance) {
                                qrModalInstance.hide();
                            }
                            // Focus on the input to show user it filled
                            cardCodeInput.focus();
                        });
                    };

                    const config = {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        },
                        aspectRatio: 1.0
                    };

                    // Start scanning via back/rear camera if available
                    html5QrcodeScanner.start({
                                facingMode: "environment"
                            },
                            config,
                            qrCodeSuccessCallback
                        )
                        .catch(err => {
                            console.error("Camera access error:", err);
                            cameraErrorAlert.classList.remove('d-none');
                        });
                }

                // Stop camera stream cleanly
                function stopQrScanner() {
                    if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                        return html5QrcodeScanner.stop().then(() => {
                            html5QrcodeScanner = null;
                        });
                    }
                    return Promise.resolve();
                }

                // Handle open scanner modal
                startScannerBtn.addEventListener('click', function() {
                    if (qrModalInstance) {
                        qrModalInstance.show();
                        // Delay scanner initialization slightly so modal fully renders first
                        setTimeout(startQrScanner, 400);
                    }
                });

                // Handle close scanner modal
                closeScannerModalBtn.addEventListener('click', function() {
                    stopQrScanner().then(() => {
                        if (qrModalInstance) {
                            qrModalInstance.hide();
                        }
                    });
                });

                // Handle modal backdrop dismiss clean up
                qrModalEl.addEventListener('hidden.bs.modal', function() {
                    stopQrScanner();
                });
            });
        </script>
    @endif
@endsection
