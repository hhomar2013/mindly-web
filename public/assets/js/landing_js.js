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



        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('section, div[id]'); // بياخد أي ديف أو سيكشن واخد ID

            window.addEventListener('scroll', () => {
                let current = "";

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    // الـ 150 دي عشان يغير الـ Active قبل ما يوصل للسكشن بمسافة بسيطة
                    if (pageYOffset >= (sectionTop - 150)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    // لو اللينك بيشاور على القسم الحالي، ضيف كلاس active
                    if (link.getAttribute('href').includes(current) && current !== "") {
                        link.classList.add('active');
                    }
                });

                // لو إحنا فوق خالص (عند الهوم)
                if (pageYOffset < 200) {
                    navLinks[0].classList.add('active');
                }
            });
        });
