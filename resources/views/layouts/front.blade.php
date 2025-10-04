<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	
	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="QuickCode OU">
	<meta name="format-detection" content="telephone=no">
    @php
        $defaultTitle       = config('app.name', 'Uued Horisondid');
        $defaultDescription = 'Официальный сайт — мероприятия, новости и записи.';
        $defaultKeywords    = 'мероприятия, новости, обучение, Таллинн';
        $defaultImage       = asset('assets/images/social-default.png');
        $defaultRobots      = 'index,follow';
        $defaultOgType      = 'website';
        $defaultTwitterCard = 'summary_large_image';
        $defaultCanonical   = request()->url();

        $metaTitle       = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', $defaultTitle)));
        $metaDescription = trim($__env->yieldContent('meta_description', $defaultDescription));
        $metaKeywords    = trim($__env->yieldContent('meta_keywords', $defaultKeywords));
        $metaImage       = trim($__env->yieldContent('meta_image', $defaultImage));
        $metaRobots      = trim($__env->yieldContent('meta_robots', $defaultRobots));
        $ogType          = trim($__env->yieldContent('og_type', $defaultOgType));
        $twitterCard     = trim($__env->yieldContent('twitter_card', $defaultTwitterCard));
        $canonical       = trim($__env->yieldContent('canonical', $defaultCanonical));
    @endphp

    <title>{{ $metaTitle }}</title>

    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:url" content="{{ $canonical }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    @hasSection('meta')
        @yield('meta')
    @endif

    @stack('head')

	
    <!-- Scripts -->
    @vite(['resources/init/front.css'])
	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
	<!-- Favicon icon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    
	
	<!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/rangeslider/rangeslider.css') }}">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

</head>
<body>
    <div id="app">
        <div class="page-wraper">
            <!-- Info Bar -->
            <div class="info-bar p-0" style="background-color: #f8f9fa;">
                <div class="container d-none d-xl-block py-2">
                    <div class="row d-flex justify-content-between">
                        <div class="dlab-topbar-left">
                            <ul class="" style="display: flex; gap: 10px; justify-content: space-between;">
                                <li class="small"><i class="fa-solid fa-phone-volume"></i> <a href="tel:+37254290030">+372 54290030</a></li>
                                <li class="small"><i class="fa-solid fa-envelope"></i> <a href="mailto:uuedhorisondidtallinn@gmail.com">uuedhorisondidtallinn@gmail.com</a></li>
                                <li class="small info"><i class="fa-solid fa-bullhorn"></i> Сейчас идёт мероприятие! </li>
                                <li class="small"><i class="fa-solid fa-map-marker-alt"></i> <a href="https://www.google.ee/maps/place/Henna+Koda+Stuudio/@59.4314389,24.7661408,19z/data=!4m6!3m5!1s0x46929522841efa05:0x6a7184e4900dd5c4!8m2!3d59.4315548!4d24.7664408!16s%2Fg%2F11rb45_j04!5m2!1e2!1e4?entry=ttu&g_ep=EgoyMDI1MDkxMC4wIKXMDSoASAFQAw%3D%3D" target="_blank">Tartu mnt 30 Tallinn</a></li>
                                <li class="small"><i class="fa-solid fa-receipt"></i> MTÜ Uued Horisondid: EE087700771011884557</li>
                            </ul>
                        </div>
                    </div>
                </div>
        
                <!-- Header -->
                <header class="site-header mo-left header header-transparent transparent-white style-2">
                    <!-- Main Header -->
                    <div class="sticky-header main-bar-wraper navbar-expand-lg">
                        <div class="main-bar clearfix ">
                            <div class="container-fluid clearfix">
                                
                                <!-- Website Logo -->
                                <div class="logo-header mostion">
                                    <a href="{{ url('/') }}" ><img src="{{ asset('assets/images/logopng.png') }}" alt="logo"></a>
                                </div>
                                
                                <!-- Nav Toggle Button -->
                                <button class="navbar-toggler navbar-toggler navbar-toggler collapsed navicon justify-content-end" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                                
                                <!-- EXTRA NAV -->
                                <div class="extra-nav">
                                    <div class="extra-cell">
                                        <div class="menu-btn">
                                            <a href="javascript:void(0);">
                                                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.04102 17.3984H29.041" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M4.04102 8.39844H29.541" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M4.04102 25.3984H29.041" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- EXTRA NAV -->
                                <!-- Header Nav -->
                                <div class="header-nav navbar-collapse collapse justify-content-end" id="navbarNavDropdown">
                                    <div class="logo-header">
                                        <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logopng.png') }}" alt="logo"></a>
                                    </div>
                                    <ul class="nav navbar-nav navbar navbar-left">
                                        <li>
                                            <a href="{{ route('home') }}">Главная</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('events.index') }}">Мероприятия</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('blog.index') }}">Новости</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('contact') }}">Контакты</a>
                                        </li>
                                        <li class="sub-menu-down">
                                            <a href="javascript:void(0);">
                                                <img src="{{ asset('assets/images/localization/ru.png') }}" alt="RU" style="width: 24px; margin-right: 5px;"> Русский
                                            </a>
                                            <ul class="sub-menu">
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="{{ asset('assets/images/localization/ru.png') }}" alt="RU" style="width: 24px; margin-right: 5px;"> Русский
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="{{ asset('assets/images/localization/et.png') }}" alt="ET" style="width: 24px; margin-right: 5px;"> Eesti
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="{{ asset('assets/images/localization/gb.png') }}" alt="EN" style="width: 24px; margin-right: 5px;"> English
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <div class="dz-social-icon">
                                        <ul>
                                            <li><a target="_blank" class="fab fa-facebook-f" href="https://www.facebook.com/share/161aHLsUuT/"></a></li>
                                            <li><a target="_blank" class="fab fa-instagram" href="https://www.instagram.com/keelekohvik?utm_source=qr&igsh=MWYxN3BqbjNwOGxwcA=="></a></li>
                                            <li><a target="_blank" class="fab fa-telegram" href="https://t.me/Keeleklubi"></a></li>
                                        </ul>
                                    </div>	
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main Header End -->
                </header>
            </div>
            <!-- Info Bar End -->
            
            <!-- Header -->
            <div class="contact-sidebar">
                <div class="contact-box1">
                    <div class="logo-contact logo-header">
                        <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logopng.png') }}" alt="logo"></a>
                    </div>
                    <div class="m-b50 contact-text">
                        <div class="dz-title">
                            <h4 class="m-b0">About us</h4>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        <a href="about-us.html" class="btn btn-primary btn-hover-2"><span>READ MORE</span></a>
                    </div>
                    <div class="dz-title">
                        <h4 class="m-b20">Contact Info</h4>
                    </div>
                    <div class="icon-bx-wraper left">
                        <div class="icon-md m-r20">
                            <span class="icon-cell"><i class="las la-phone-volume"></i></span> 
                        </div>
                        <div class="icon-content">
                            <h6 class="tilte">Call Now</h6>
                            <p class="m-b0">+91 123 456 7890,<br> +91 987 654 3210</p>
                        </div>
                    </div>
                    <div class="icon-bx-wraper left">
                        <div class="icon-md m-r20">
                            <span class="icon-cell"><i class="las la-envelope-open"></i></span> 
                        </div>
                        <div class="icon-content">
                            <h6 class="tilte">Location</h6>
                            <p class="m-b0">15/B Miranda House, New York, US</p>
                        </div>
                    </div>
                    <div class="icon-bx-wraper left">
                        <div class="icon-md m-r20">
                            <span class="icon-cell"><i class="las la-map-marker"></i></span> 
                        </div>
                        <div class="icon-content">
                            <h6 class="tilte">Email Now</h6>
                            <p class="m-b0">info@gmail.com, services@gmail.com</p>
                        </div>
                    </div>
                </div>	
            </div>
            <div class="menu-close"></div>
                @yield('content')
	
            <!--Footer-->
            <footer class="site-footer style-2" id="footer">
                <div class="footer-bg-wrapper" id="app-banner">
                    <div class="footer-top">
                        <div class="container">
                            <div class="footer-subscribe-wrapper">
                                <div class="wrapper-inner">
                                    <div class="row justify-content-between">
                                        <div class="col-xl-4 col-lg-4 m-lg-0 m-b20 wow fadeInUp" data-wow-delay="0.4s">
                                            <div class="footer-logo">
                                                <a href="{{ url('/') }}">
                                                    <img src="{{ asset('assets/images/logopng.png') }}" alt="logo">
                                                </a>
                                            </div>
                                            <p class="text-white mb-0 font-14">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 wow fadeInUp" data-wow-delay="0.6s">
                                            <h4 class="text-white title m-b15">Subscribe To Our Newsletter</h4>
                                            <form class="dzSubscribe" action="{{ asset('assets/script/mailchamp.php') }}" method="post">
                                                <div class="dzSubscribeMsg text-white"></div>
                                                <div class="input-group">
                                                    <input name="dzEmail" required="required" type="text" class="form-control" placeholder="Enter Your Email">
                                                    <div class="input-group-addon">
                                                        <button name="submit" value="submit" type="submit" class="btn btn-primary btn-hover-2">
                                                            <span>Subscribe</span><i class="fa-solid fa-paper-plane"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="0.4s">
                                    <div class="widget widget_getintuch">
                                        <h5 class="footer-title">Contact</h5>
                                        <ul>
                                            <li>
                                                <i class="flaticon-placeholder"></i>
                                                <p>1247/Plot No. 39, 15th Phase, Colony, Kkatpally, Hyderabad</p>
                                            </li>
                                            <li>
                                                <i class="flaticon-telephone"></i>
                                                <p>+91 987-654-3210<br>
                                                    +91 123-456-7890</p>
                                            </li>
                                            <li>
                                                <i class="flaticon-email-1"></i>
                                                <p>info@example.com<br>
                                                info@example.com</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-2 col-md-6 col-sm-6">
                                    <div class="widget widget_services">
                                        <h5 class="footer-title">Our Links</h5>
                                        <ul>
                                            <li><a href="index.html"><span>Home</span></a></li>
                                            <li><a href="about-us.html"><span>About Us</span></a></li>
                                            <li><a href="services.html"><span>Services</span></a></li>
                                            <li><a href="team.html"><span>Team</span></a></li>
                                            <li><a href="blog-standard.html"><span>Blog</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                                    <div class="widget widget_services">
                                        <h5 class="footer-title">OUR SERVICES</h5>
                                        <ul>
                                            <li><a href="blog-open-gutenberg.html"><span>Strategy & Research</span></a></li>
                                            <li><a href="services.html"><span>Fast Delivery</span></a></li>
                                            <li><a href="contact-us.html"><span>Seat Reservation</span></a></li>
                                            <li><a href="shop-style-1.html"><span>Pickup In Store</span></a></li>
                                            <li><a href="our-menu-1.html"><span>Our Menu</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6	">
                                    <div class="widget widget_services">
                                        <h5 class="footer-title">Help Center</h5>
                                        <ul>
                                            <li><a href="faq.html"><span>FAQ</span></a></li>
                                            <li><a href="shop-style-1.html"><span>Shop</span></a></li>
                                            <li><a href="shop-style-2.html"><span>Category Filter</span></a></li>
                                            <li><a href="testimonial.html"><span>Testimonials</span></a></li>
                                            <li><a href="contact-us.html"><span>Contact Us</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Bottom Part -->
                <div class="container">
                    <div class="footer-bottom">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6">
                                <span class="copyright-text">Crafted With <span class="heart"></span> by <a href="https://dexignzone.com/" target="_blank">DexignZone</a></span>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <ul class="footer-link">
                                    <li><a href="blog-standard.html">Blog Detail</a></li>
                                    <li><a href="about-us.html">About</a></li>
                                    <li><a href="testimonial.html">Testimonials</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Footer -->
            
            <div class="scroltop-progress scroltop-primary">
                <svg width="100%" height="100%" viewBox="-1 -1 102 102">
                    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
                </svg>
            </div>
            
        </div>
    </div>
    <!-- JAVASCRIPT FILES ========================================= -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script><!-- JQUERY.MIN JS -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script><!-- BOOTSTRAP.MIN JS -->
    <script src="{{ asset('assets/vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script><!-- BOOTSTRAP SELECT -->
    <script src="{{ asset('assets/vendor/magnific-popup/magnific-popup.js') }}"></script><!-- MAGNIFIC POPUP JS -->
    <script src="{{ asset('assets/vendor/masonry/masonry-4.2.2.js') }}"></script><!-- MASONRY -->
    <script src="{{ asset('assets/vendor/wow/wow.js') }}"></script><!-- WOW JS -->
    <script src="{{ asset('assets/vendor/masonry/isotope.pkgd.min.js') }}"></script><!-- ISOTOPE -->
    <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.js') }}"></script><!-- IMAGESLOADED -->
    <script src="{{ asset('assets/vendor/counter/waypoints-min.js') }}"></script><!-- WAYPOINTS JS -->
    <script src="{{ asset('assets/vendor/counter/counterup.min.js') }}"></script><!-- COUNTERUP JS -->
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script><!-- OWL-CAROUSEL -->
    <script src="{{ asset('assets/vendor/particles/particles.js') }}"></script>
    <script src="{{ asset('assets/vendor/particles/particles-app.js') }}"></script>
    <script src="{{ asset('assets/js/dz.carousel.min.js') }}"></script><!-- OWL-CAROUSEL -->
    <script src="{{ asset('assets/js/dz.ajax.js') }}"></script><!-- AJAX -->
    <script src="{{ asset('assets/js/custom.js') }}"></script><!-- CUSTOM JS -->
    <script src="{{ asset('assets/js/dznav-init.js') }}"></script><!-- DZNAV INIT -->
    <script src="{{ asset('assets/vendor/rangeslider/rangeslider.js') }}"></script><!-- CUSTOM JS -->


    <script>
        jQuery(document).ready(function(){
            dzSettingsOptions.themeFullColor_value = 'color_1';
            new dzSettings(dzSettingsOptions);
        });
    </script>
    @stack('scripts')
</body>
</html>