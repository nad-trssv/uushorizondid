
@extends('layouts.front')

@section('title', __('seo.homepage.page_title'))
@section('meta_title', __('seo.homepage.meta_title'))
@section('meta_description', __('seo.homepage.meta_description'))
@section('og_type', 'article')
@section('canonical', request()->url())

@section('content')
	
	<div class="page-content bg-white">
		<!-- Banner -->
		<div class="main-bnr-three overflow-hidden top-space">
			<div class="swiper-bnr-pagination left-align">
				<div class="main-button-prev"><i class="icon-arrow-up"></i></div>
				<div class="main-swiper3-pagination"></div>
				<div class="main-button-next"><i class="icon-arrow-down"></i></div>
			</div>
			<div class="main-slider-3">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<div class="banner-inner overflow-hidden" data-swiper-parallax="-10" data-swiper-parallax-duration="0.5" style="background-image:url('{{ asset('assets/images/banner/banner1.png') }}'); background-size:cover;background-position: 90% center;">
							<div class="container">
								<div class="row align-items-center" data-swiper-parallax="-100">
									<div class="col-xl-8 col-lg-8 col-md-8 px-4">
										<div class="banner-content">
											<span class="sub-title text-primary">Уютная атмосфера обучения</span>
											<h1 class="title text-white">Языковое кафе в центре Таллинна</h1>
											<p class="bnr-text">
												Место, где эстонский оживает в играх, походах и дружеских встречах.
												<span class="d-none d-md-block">Мы верим, что изучение языка - это не только слова и грамматика, 
													но и погружение в традиции, музыку, кухню и повседневную жизнь Эстонии.</span>
											</p>
											
											<div class="banner-btn d-flex align-items-center">
												<a href="contact-us.html" class="btn btn-primary btn-md shadow-primary m-r30 btn-hover-1"><span>Хочу участвовать</span></a>
												<a href="about-us.html" class="btn btn-outline-primary btn-md shadow-primary btn-hover-1"><span>Все мероприятия</span></a>
											</div>
										</div>
									</div>
									
									<div class="col-xl-4 col-lg-4 col-md-4 d-none d-xl-block">
										<div class="banner-event-card">
											<div class="event-soon-badge">НА ЭТОЙ НЕДЕЛЕ!</div>
											<div class="event-card">
												<div class="event-date-badge">
													<span class="event-day">Сб</span>
													<span class="event-date">19</span>
													<span class="event-month">октября</span>
												</div>
												<div class="event-content">
													<h4 class="event-title">Киновечер</h4>
													<div class="event-time info" style="color: #fff !important;">
														<i class="flaticon-clock"></i> 18:30–20:00
													</div>
													<p class="event-description">Смотрим, обсуждаем, пополняем словарный запас из живой речи.</p>
													<a href="blog-standard.html" class="btn btn-primary btn-sm btn-hover-2 event-btn">Записаться</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="banner-inner overflow-hidden" data-swiper-parallax="-10" data-swiper-parallax-duration="0.5" style="background-image:url('{{ asset('assets/images/banner/banner2.png') }}'); background-size:cover;background-position: 90% center;">
							<div class="container">
								<div class="row align-items-center" data-swiper-parallax="-100">
									<div class="col-xl-8 col-lg-8 col-md-8 px-4">
										<div class="banner-content">
											<span class="sub-title text-primary">Учимся играя</span>
											<h1 class="title text-white">Эстонский через интерактивные методы</h1>
											<p class="bnr-text">
												Языковые карточки, ролевые игры, квизы и командные задания делают обучение увлекательным приключением.
												<span class="d-none d-md-block"> Забудьте о скучных учебниках - изучайте эстонский через творчество, смех и живое взаимодействие.</span>
											</p>
											<div class="banner-btn d-flex align-items-center">
												<a href="contact-us.html" class="btn btn-primary btn-md shadow-primary m-r30 btn-hover-1"><span>Хочу участвовать</span></a>
												<a href="about-us.html" class="btn btn-outline-primary btn-md shadow-primary btn-hover-1"><span>Все мероприятия</span></a>
											</div>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 d-none d-xl-block">
										<div class="banner-event-card">
											<div class="event-soon-badge">НА ЭТОЙ НЕДЕЛЕ!</div>
											<div class="event-card">
												<div class="event-date-badge">
													<span class="event-day">Сб</span>
													<span class="event-date">19</span>
													<span class="event-month">октября</span>
												</div>
												<div class="event-content">
													<h4 class="event-title">Киновечер</h4>
													<div class="event-time info" style="color: #fff !important;">
														<i class="flaticon-clock"></i> 18:30–20:00
													</div>
													<p class="event-description">Смотрим, обсуждаем, пополняем словарный запас из живой речи.</p>
													<a href="blog-standard.html" class="btn btn-primary btn-sm btn-hover-2 event-btn">Записаться</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="swiper-slide">
						<div class="banner-inner overflow-hidden" data-swiper-parallax="-10" data-swiper-parallax-duration="0.5" style="background-image:url('{{ asset('assets/images/banner/banner3.jpg') }}'); background-size:cover;background-position: 90% center;">
							<div class="container">
								<div class="row align-items-center" data-swiper-parallax="-100">
									<div class="col-xl-8 col-lg-8 col-md-8 px-4">
										<div class="banner-content">
											<span class="sub-title text-primary">Реальные навыки для жизни</span>
											<h1 class="title text-white">Эстонский для работы и повседневности</h1>
											<p class="bnr-text">
												Подготовка к собеседованиям, общение с врачами, походы в магазины 
												и государственные учреждения.
												<span class="d-none d-md-block">Изучайте именно те фразы и ситуации, 
													которые пригодятся вам каждый день.</span>
											</p>
											<div class="banner-btn d-flex align-items-center">
												<a href="contact-us.html" class="btn btn-primary btn-md shadow-primary m-r30 btn-hover-1"><span>Хочу участвовать</span></a>
												<a href="about-us.html" class="btn btn-outline-primary btn-md shadow-primary btn-hover-1"><span>Все мероприятия</span></a>
											</div>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 d-none d-xl-block">
										<div class="banner-event-card">
											<div class="event-soon-badge">НА ЭТОЙ НЕДЕЛЕ!</div>
											<div class="event-card">
												<div class="event-date-badge">
													<span class="event-day">Сб</span>
													<span class="event-date">19</span>
													<span class="event-month">октября</span>
												</div>
												<div class="event-content">
													<h4 class="event-title">Киновечер</h4>
													<div class="event-time info" style="color: #fff !important;">
														<i class="flaticon-clock"></i> 18:30–20:00
													</div>
													<p class="event-description">Смотрим, обсуждаем, пополняем словарный запас из живой речи.</p>
													<a href="blog-standard.html" class="btn btn-primary btn-sm btn-hover-2 event-btn">Записаться</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					{{-- <div class="swiper-slide">
						<div class="banner-inner overflow-hidden" data-swiper-parallax="-10" data-swiper-parallax-duration="0.5" style="background-image:url('{{ asset('assets/images/main-slider/slider2/bg3.jpg') }}'); background-size:cover;">
							<div class="container">
								<div class="row align-items-center" data-swiper-parallax="-100">
									<div class="col-xl-7 col-lg-7 col-md-8">
										<div class="banner-content">
											<span class="sub-title text-primary">Exploring the Delicious World</span>
											<h1 class="title text-white">Food that Makes You Happy And Healthy</h1>
											<p class="bnr-text ow fadeInUp">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
											
											<div class="banner-btn d-flex align-items-center">
												<a href="contact-us.html" class="btn btn-primary btn-md shadow-primary m-r30 btn-hover-1"><span>Хочу участвовать</span></a>
												<a href="about-us.html" class="btn btn-outline-primary btn-md shadow-primary btn-hover-1"><span>Все мероприятия</span></a>
											</div>
											<div class="food-card">
												<div class="dz-head">
													<h5 class="text-white title">Veg Biryani</h5>
													<ul class="rating">
														<li><i class="fa-solid fa-star"></i></li>
														<li><i class="fa-solid fa-star"></i></li>
														<li><i class="fa-solid fa-star"></i></li>
														<li><i class="fa-solid fa-star"></i></li>
														<li><i class="fa-solid fa-star"></i></li>
													</ul>
												</div>
												<div class="dz-body">
													<div class="dz-left">
														<div class="profile-info">
															<div class="dz-media">
																<img src="assets/images/team/pic1.jpg" alt="/">
															</div>
															<div class="dz-content">
																<h6 class="title text-white">Kamy Klay</h6>
																<p>Master Chief</p>
															</div>
														</div>
														<p class="text">Lorem ipsum dolor shit amet...</p>
													</div>
													<div class="dz-right">
														<h5 class="text-primary">$10.00</h5>
														<a href="shop-cart.html" class="btn btn-primary btn-cart"><i class="flaticon-shopping-cart"></i></a>
													</div>
												</div>
												<img class="target-line" src="assets/images/main-slider/slider2/line.png" alt="/">
											</div>
										</div>
									</div>
									<div class="col-xl-5 col-lg-5 col-md-4">
										<div class="banner-media">
											<img src="assets/images/main-slider/slider2/pic3.png" alt="/" data-swiper-parallax-scale="0.8">
										</div>
									</div>
								</div>
							</div>
							<img class="leaf" src="assets/images/main-slider/slider2/pic4.png" alt="/">
						</div>
					</div> --}}
				</div>
			</div>
		</div>	
		<!--Banner-->
		

		<!-- Testimonials -->
		<section class="content-inner-2 overflow-hidden">
			<div class="container">
				<div class="section-head text-center">
					<h2 class="title wow flipInX" data-wow-delay="0.2s">Кто мы такие?</h2>
				</div>
				<div class="swiper testimonial-two-swiper swiper-btn-lr swiper-single swiper-visible">
					<div class="swiper-wrapper">
						<div class="swiper-slide">
							<div class="testimonial-2">
								<div class="dz-media">
									<img src="{{ asset('assets/images/about.png') }}" alt="Uuedhorisondid">
								</div>
								<div class="testimonial-detail">
									<div class="testimonial-text wow fadeInUp" data-wow-delay="0.4s">
										<p>Наше языковое кафе — это место, где язык оживает в играх, походах и дружеских встречах. Мы верим, что изучение языка — это не только слова и грамматика, но и погружение в традиции, музыку, кухню и повседневную жизнь Эстонии.</p>
										<p>Здесь вы сможете практиковать эстонский язык в живых беседах, погрузиться в культуру и завести новых друзей.</p>
									</div>
									<div class="testimonial-info wow fadeInUp" data-wow-delay="0.6s">
										<h5 class="testimonial-name">Artem Zhaivoronok</h5>
										<span class="testimonial-position">Энтузиаст языкового кафе</span>
									</div>
									<i class="flaticon-right-quote quote"></i>
								</div>
							</div>
						</div>
						{{-- <div class="swiper-slide">
							<div class="testimonial-2">
								<div class="dz-media">
									<img src="assets/images/testimonial/large/pic2.jpg" alt="/">
								</div>
								<div class="testimonial-detail">
									<div class="testimonial-text wow fadeInUp" data-wow-delay="0.4s">
										<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
									</div>
									<div class="testimonial-info wow fadeInUp" data-wow-delay="0.6s">
										<h5 class="testimonial-name">John Doe</h5>
										<span class="testimonial-position">Food Expert</span>
									</div>
									<i class="flaticon-right-quote quote"></i>
								</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="testimonial-2">
								<div class="dz-media">
									<img src="assets/images/testimonial/large/pic3.jpg" alt="/">
								</div>
								<div class="testimonial-detail">
									<div class="testimonial-text wow fadeInUp" data-wow-delay="0.4s">
										<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
									</div>
									<div class="testimonial-info wow fadeInUp" data-wow-delay="0.6s">
										<h5 class="testimonial-name">Marn Kamk</h5>
										<span class="testimonial-position">Food Expert</span>
									</div>
									<i class="flaticon-right-quote quote"></i>
								</div>
							</div>
						</div> --}}
					</div>
					<div class="pagination">
						<div class="testimonial-2-button-prev btn-prev rounded-xl btn-hover-2"><i class="fa-solid fa-arrow-left"></i></div>
						<div class="testimonial-2-button-next btn-next rounded-xl btn-hover-2"><i class="fa-solid fa-arrow-right"></i></div>
					</div>
				</div>
			</div>
		</section>
		<!-- Testimonial -->

		<!-- Icon Wrapper-2 -->
		<section class="content-inner bg-white">
			<div class="container">
				<div class="section-head menu-align">
					<h2 class="title mb-0 wow flipInX" data-wow-delay="0.2s">Больше чем просто кафе</h2>
				</div>
			</div>
			<div class="container">
				<div class="row icon-wrapper2 gx-lg-5">
					<div class="col-lg-4 col-md-6 m-b30 wow fadeInUp" data-wow-delay="0.2s">
						<div class="icon-bx-wraper style-2">
							<div class="icon-bx radius">
								<span class="icon-cell">
									<i class="flaticon-offer"></i>
								</span>
							</div>
							<div class="icon-content">
								<h5 class="dz-title"><a href="services.html">Практиковать эстонский язык</a></h5>
								<p>в живых беседах, играх и увлекательных упражнениях</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 m-b30 wow fadeInUp" data-wow-delay="0.4s">
						<div class="icon-bx-wraper style-2">
							<div class="icon-bx radius">
								<span class="icon-cell">
									<i class="flaticon-hamburger"></i>
								</span>
							</div>
							<div class="icon-content">
								<h5 class="dz-title"><a href="services.html">Погружаться в культуру:</a></h5>
								<p>знакомиться с традициями, кухней, праздниками и искусством Эстонии</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 m-b30 wow fadeInUp" data-wow-delay="0.6s">
						<div class="icon-bx-wraper style-2">
							<div class="icon-bx radius">
								<span class="icon-cell">
									<i class="flaticon-room-service"></i>
								</span>
							</div>
							<div class="icon-content">
								<h5 class="dz-title"><a href="services.html">Находить друзей и единомышленников:</a></h5>
								<p>Смеяться, делиться историями, обмениваться опытом и заводить новые знакомства</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Icon Wrapper 2 -->

		<!-- More about us -->
		<section class="content-inner-1 section-wrapper-3 overflow-hidden">
			<div class="container">
				{{-- Why choose us  --}}
				<div class="section-head text-center">
					<h2 class="title wow flipInX" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: flipInX;">Уют и комфорт для всех</h2>
				</div>
				<div class="icon-wrapper1">
					<div class="row wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
						<div class="col-lg-3 col-md-6 col-sm-6">
							<div class="icon-bx-wraper style-1 box-hover center active" style="background-image: url('{{ asset('assets/images/about.png') }}')">
								<div class="inner-content">
									<div class="icon-bx m-b25"> 
										<span class="icon-cell icon-md">
											<i class="flaticon-restaurant"></i>
										</span> 
									</div>
									<div class="icon-content">
										<h5 class="dz-title">Расположение</h5>
										<p>Удобное расположение - в самом центре города</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6">
							<div class="icon-bx-wraper style-1 box-hover center" style="background-image: url(assets/images/gallery/grid/pic2.jpg)">
								<div class="inner-content">
									<div class="icon-bx m-b25"> 
										<span class="icon-cell icon-md">
											<i class="flaticon-martini"></i>
										</span> 
									</div>
									<div class="icon-content">
										<h5 class="dz-title">Парковка</h5>
										<p>Бесплатная парковка для гостей</p>
									</div>
								</div>
							</div>
					 
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6">
							<div class="icon-bx-wraper style-1 box-hover center" style="background-image: url(assets/images/gallery/grid/pic3.jpg)">
								<div class="inner-content">
									<div class="icon-bx m-b25"> 
											<span class="icon-cell icon-md">
												<i class="flaticon-coffee-cup"></i>
											</span> 
										</div>
									<div class="icon-content">
										<h5 class="dz-title">Детская комната</h5>
										<p>Учитесь, пока дети играют</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6">
							<div class="icon-bx-wraper style-1 box-hover center" style="background-image: url(assets/images/gallery/grid/pic4.jpg)">
								<div class="inner-content">
									<div class="m-b25"> 
										<span class="icon-cell icon-md">
											<i class="flaticon-cake"></i>
										</span> 
									</div>
									<div class="icon-content">
										<h5 class="dz-title">Атмосфера</h5>
										<p>кофе, уют и живое общение</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
				{{-- Why choose us end--}}
				
			</div>
			<img class="bg1 dz-parallax" data-parallax-speed="0.05" src="assets/images/background/pic3.png" alt="/" style="transform: translate3d(0px, 36.684912px, 0px) rotate(36.684912deg);">
			<img class="bg2 dz-parallax" data-parallax-speed="0.05" src="assets/images/background/pic4.png" alt="/" style="transform: translate3d(0px, 6.992139px, 0px) rotate(6.992139deg);">
		</section>
		<!-- More about us End-->

		<!-- Events -->
		<section class="content-inner-1 overflow-hidden">
			<div class="container">
				<div class="section-head text-center">
					<h2 class="title wow flipInX" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: flipInX;">Ближайшие мероприятия</h2>
				</div>
				<div class="event_wraper pb-4">
					<div class="event_item">
						<div class="dz-card style-1 blog-half overlay-shine dz-img-effect zoom wow fadeInUp" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;">
							<div class="dz-media">
								<a href="blog-standard.html"><img src="assets/images/blog/grid/pic4.jpg" alt="/"></a>
							</div>
							<div class="dz-info">
								<div class="dz-meta">
									<ul>
										<li><a href="javascript:void(0);"><i class="flaticon-calendar-date"></i> Сб, 19 октября</a></li>
										<li class="dz-comment"><a href="javascript:void(0);"><i class="flaticon-clock"></i> 18:30–20:00</a></li>
									</ul>
								</div> 
								<h5 class="dz-title"><a href="blog-standard.html">Киновечер</a></h5>
								<p>Смотрим, обсуждаем, пополняем словарный запас из живой речи.</p>
								<div class="read-btn">
									<a href="blog-standard.html" class="btn btn-primary btn-hover-2">Записаться</a>
								</div>
							</div>
						</div>
					</div>
					<div class="event_item">
						<div class="dz-card style-1 blog-half overlay-shine dz-img-effect zoom wow fadeInUp" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;">
							<div class="dz-media">
								<a href="blog-standard.html"><img src="assets/images/blog/grid/pic4.jpg" alt="/"></a>
							</div>
							<div class="dz-info">
								<div class="dz-meta">
									<ul>
										<li><a href="javascript:void(0);"><i class="flaticon-calendar-date"></i> Ср, 23 октября</a></li>
										<li class="dz-comment"><a href="javascript:void(0);"><i class="flaticon-clock"></i> 19:00–21:00</a></li>
									</ul>
								</div> 
								<h5 class="dz-title"><a href="blog-standard.html">Кулинарный мастер-класс</a></h5>
								<p>Готовим простое блюдо и общаемся на эстонском без стресса.</p>
								<div class="read-btn">
									<a href="blog-standard.html" class="btn btn-primary btn-hover-2">Записаться</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 text-center m-t10">
					<a href="our-menu-2.html" class="btn btn-md btn-primary btn-hover-1"><span>Все мероприятия</span></a>
				</div>
			</div>
		</section>
		<!-- Events End -->

		<!-- Our Partners -->
		<section class="section-wrapper-4 content-inner overflow-hidden bg-parallax" style="background-image:url('assets/images/background/pic10.png'); background-attachment: fixed;">
			<div class="container">
				<div class="section-head text-center">
					<h2 class="title wow flipInX" data-wow-delay="0.2s">Наши партнёры</h2>
				</div>
				<div class="container">
					<div class="menu-slider-wrap">
						<div class="partner-swiper swiper">
							<div class="swiper-wrapper">
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
								<div class="swiper-slide"><img src="{{ asset('assets/images/partners/henna.png') }}" alt="Logo 1"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Our Partners End  -->

		<!-- Our Blog  -->
		<section class="content-inner overflow-hidden">
			<div class="container">
				<div class="row">
					<div class="col-xl-7 col-lg-12">
						<div class="section-head d-flex justify-content-between align-items-center m-b30">
							<h2 class="title wow flipInX" data-wow-delay="0.2s" style="visibility: visible;">Новости и статьи</h2>
							
							<a href="blog-standard.html" class="read-all-link wow fadeInRight" data-wow-delay="0.3s" style="visibility: visible;">
								<span>Читать все</span>
								<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="2"/>
									<path d="M8 6L12 10L8 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</a>
						</div>
						<div class="dz-card style-2 blog-half wow fadeInUp m-b30" data-wow-delay="0.4s" style="visibility: visible;">
							<div class="dz-media">
								<a href="blog-standard.html"><img src="assets/images/blog/grid2/pic1.jpg" alt="/"></a>
								<div class="dz-date">29 авг 2025</div>
							</div>
							<div class="dz-info">
								<h4 class="dz-title"><a href="blog-standard.html">Эстонская кухня: учим язык через рецепты</a></h4>
								<div class="dz-meta">
									<ul>
										<li class="dz-user">
											<a href="javascript:void(0);"><i class="fa-solid fa-camera"></i>
											Фотографий: 12</a>
										</li>
										<li class="dz-comment">
											<a href="javascript:void(0);"><i class="fa-solid fa-message"></i>
											Комментариев: 12</a>
										</li>
									</ul>
								</div>
								<div class="btn-wrapper p-t10">
									<a href="blog-standard.html" class="btn btn-primary btn-hover-2">Читать далее</a>
								</div>
							</div>
						</div>
						<div class="dz-card style-2 blog-half wow fadeInUp m-b30" data-wow-delay="0.6s" style="visibility: visible;">
							<div class="dz-media">
								<a href="blog-standard.html"><img src="assets/images/blog/grid2/pic2.jpg" alt="/"></a>
								<div class="dz-date">24 авг 2023</div>
							</div>
							<div class="dz-info">
								<h4 class="dz-title"><a href="blog-standard.html">Летняя прогулка: эстонский язык в парке</a></h4>
								<div class="dz-meta">
									<ul>
										<li class="dz-user">
											<a href="javascript:void(0);"><i class="fa-solid fa-camera"></i>
												Фотографий: 12</a>
										</li>
										<li class="dz-comment">
											<a href="javascript:void(0);"><i class="fa-solid fa-message"></i>
											Комментариев: 4</a>
										</li>
									</ul>
								</div>
								<div class="btn-wrapper p-t10">
									<a href="blog-standard.html" class="btn btn-primary btn-hover-2">Читать далее</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-5 col-lg-12 m-b30 wow fadeInUp" data-wow-delay="0.4s" style="visibility: visible;">
						<div class="dz-card style-3 dz-card-large" style="background-image: url({{ asset('assets/images/blog/news1.png') }});">
							
							<div class="dz-info">
								<h3 class="dz-title"><a href="blog-standard.html" class="text-white">Кино у озера Õismäe</a></h3>
								<div class="dz-meta">
									<ul>
										<li class="dz-date">5 сентября 2025</li>
										<li class="dz-user">
											<a href="javascript:void(0);"><i class="fa-solid fa-camera"></i>
												Фотографий: 32</a>
										</li>
										<li class="dz-comment">
											<a href="javascript:void(0);"><i class="fa-solid fa-message"></i>
												Комментариев: 2</a>
										</li>
									</ul>
								</div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Our Blog End  -->	

		<!-- Google map and after 2 links for looking location on google map and waze -->
		<section class="content-inner overflow-hidden">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-head text-center">
							<h2 class="title wow flipInX" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: flipInX;">Где мы находимся?</h2>
						</div>
						<div class="google-map border rounded">
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1984.303606622634!2d24.72552817611064!3d59.43702598192344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469293f7b6f5e6b5%3A0x400ee9169fbbd0!2z0JrQuNC90LjRgtC10YLRjCDQn9C10YDQtdC90LjRjw!5e0!3m2!1sru!2see!4v1697045727928!5m2!1sru!2see" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
						<div class="maps-grid">
							<a href="https://www.google.com/maps/dir//Uus+Horisont+Keeltekohvik,+Viru+väljak+4,+Tallinn,+10111/@59.4370259,24.723339,17z/data=!4m8!4m7!1m0!1m5!1m1!1s0x469293f7b6f5e6b5:0x400ee9169fbbd0!2m2!1d24.7255282!2d59.4370259" 
							   target="_blank" 
							   class="m-r10 gap-1 location-google">
								<i class="fa-brands fa-google"></i> <span> Google Maps</span>
							</a>
							<a href="https://www.waze.com/ul?ll=59.4370259,24.7255282&navigate=yes" 
							   target="_blank" 
							   class="gap-1 location-waze">
								<i class="fa-brands fa-waze"></i> <span> Waze</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Google map and after 2 links for looking location on google map and waze End -->

		<!-- Social media -->
		<!-- Social media -->
<section class="content-inner overflow-hidden">
	<div class="container">
	  <div class="section-head text-center">
		<h2 class="title wow flipInX" data-wow-delay="0.2s">Мы в социальных сетях</h2>
		<p class="text-muted mb-0">Подписывайтесь, чтобы не пропускать анонсы и фото с мероприятий</p>
	  </div>
  
	  <div class="row sm-grid justify-content-center">
		<div class="col-12 col-sm-6 col-lg-4">
		  <a href="https://www.instagram.com/keelekohvik?utm_source=qr&igsh=MWYxN3BqbjNwOGxwcA==" target="_blank" rel="noopener" class="sm-card">
			<div class="sm-icon sm-ig">
			  <i class="fab fa-instagram"></i>
			</div>
			<div class="sm-body">
			  <h5 class="sm-title">Instagram</h5>
			  <div class="sm-handle">@keelekohvik</div>
			  <span class="btn-smline" aria-label="Открыть Instagram">
				<i class="fa-solid fa-arrow-up-right-from-square"></i> Открыть
			  </span>
			</div>
		  </a>
		</div>
  
		<div class="col-12 col-sm-6 col-lg-4">
		  <a href="https://www.facebook.com/share/161aHLsUuT/" target="_blank" rel="noopener" class="sm-card">
			<div class="sm-icon sm-fb">
			  <i class="fab fa-facebook-f"></i>
			</div>
			<div class="sm-body">
			  <h5 class="sm-title">Facebook</h5>
			  <div class="sm-handle">Новости и события</div>
			  <span class="btn-smline" aria-label="Открыть Facebook">
				<i class="fa-solid fa-arrow-up-right-from-square"></i> Открыть
			  </span>
			</div>
		  </a>
		</div>
  
		<div class="col-12 col-sm-6 col-lg-4">
		  <a href="https://t.me/Keeleklubi" target="_blank" rel="noopener" class="sm-card">
			<div class="sm-icon sm-tg">
			  <i class="fab fa-telegram"></i>
			</div>
			<div class="sm-body">
			  <h5 class="sm-title">Telegram</h5>
			  <div class="sm-handle">Оповещения и чат</div>
			  <span class="btn-smline" aria-label="Открыть Telegram">
				<i class="fa-solid fa-arrow-up-right-from-square"></i> Открыть
			  </span>
			</div>
		  </a>
		</div>
	  </div>
	</div>
  </section>
  
		<!-- Social media End -->
	</div>
@endsection