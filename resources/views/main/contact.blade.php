
@extends('layouts.front')

@section('title', __('seo.contact.page_title'))
@section('meta_title', __('seo.contact.meta_title'))
@section('meta_description', __('seo.contact.meta_description'))
@section('og_type', 'article')
@section('canonical', request()->url())

@section('content')
<div class="page-content bg-white">

    <!-- Хлебные крошки -->
    <div class="dz-breadcrumb-bnr">
        <div class="container">
            <nav aria-label="breadcrumb" class="breadcrumb-row">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Контакты</li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="container my-5">
        <div class="row g-3 ev-contact-row align-items-stretch">

            {{-- Адрес --}}
            <div class="col-lg-4 col-sm-6">
              <a href="https://www.google.com/maps/dir//Uus+Horisont+Keeltekohvik,+Viru+väljak+4,+Tallinn,+10111/@59.4370259,24.723339,17z/data=!4m8!4m7!1m0!1m5!1m1!1s0x469293f7b6f5e6b5:0x400ee9169fbbd0!2m2!1d24.7255282!2d59.4370259"
                 target="_blank" rel="noopener"
                 class="ev-info-card d-flex h-100">
                <span class="ev-info-icon" style="--icon-bg:#e0f2fe;--icon-color:#0284c7;">
                  <i class="flaticon-placeholder"></i>
                </span>
                <span class="ev-info-content">
                  <strong class="ev-info-title">Адрес</strong>
                  <span class="ev-info-value">Viru väljak 4, Tallinn</span>
                  <small class="ev-info-hint">Открыть в Google Maps</small>
                </span>
              </a>
            </div>
          
            {{-- Телефон --}}
            <div class="col-lg-4 col-sm-6">
              <a href="tel:+37254290030" class="ev-info-card d-flex h-100">
                <span class="ev-info-icon" style="--icon-bg:#ecfeff;--icon-color:#0ea5e9;">
                  <i class="flaticon-telephone"></i>
                </span>
                <span class="ev-info-content">
                  <strong class="ev-info-title">Телефон</strong>
                  <span class="ev-info-value">+372 54290030</span>
                  <small class="ev-info-hint">Позвонить сейчас</small>
                </span>
              </a>
            </div>
          
            {{-- Email --}}
            <div class="col-lg-4 col-sm-6">
              <a href="mailto:uuedhorisondidtallinn@gmail.com" class="ev-info-card d-flex h-100">
                <span class="ev-info-icon" style="--icon-bg:#fef3c7;--icon-color:#d97706;">
                  <i class="flaticon-email-1"></i>
                </span>
                <span class="ev-info-content">
                  <strong class="ev-info-title">Email</strong>
                  <span class="ev-info-value">uuedhorisondidtallinn@gmail.com</span>
                  <small class="ev-info-hint">Написать письмо</small>
                </span>
              </a>
            </div>
          
          </div>

        <div class="google-map border rounded-3 overflow-hidden shadow-sm">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1984.303606622634!2d24.72552817611064!3d59.43702598192344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469293f7b6f5e6b5%3A0x400ee9169fbbd0!2z0JrQuNC90LjRgtC10YLRjCDQn9C10YDQtdC90LjRjw!5e0!3m2!1sru!2see!4v1697045727928!5m2!1sru!2see"
                width="100%" height="450" style="border:0;" allowfullscreen
                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="contact-after-map mt-4">

            {{-- Навигация: Google Maps / Waze --}}
            <div class="row g-3 ev-nav-row">
                <div class="col-md-6">
                    <a href="https://www.google.com/maps/dir//Uus+Horisont+Keeltekohvik,+Viru+väljak+4,+Tallinn,+10111/@59.4370259,24.723339,17z/data=!4m8!4m7!1m0!1m5!1m1!1s0x469293f7b6f5e6b5:0x400ee9169fbbd0!2m2!1d24.7255282!2d59.4370259"
                       target="_blank" rel="noopener"
                       class="ev-nav-card d-flex align-items-center gap-3">
                        <span class="ev-nav-icon">
                            <i class="fa-brands fa-google"></i>
                        </span>
                        <span class="ev-nav-text">
                            <strong>Открыть маршрут в Google Maps</strong>
                            <small>Построить путь до Viru väljak 4</small>
                        </span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="https://www.waze.com/ul?ll=59.4370259,24.7255282&navigate=yes"
                       target="_blank" rel="noopener"
                       class="ev-nav-card ev-nav-card--waze d-flex align-items-center gap-3">
                        <span class="ev-nav-icon">
                            <i class="fa-brands fa-waze"></i>
                        </span>
                        <span class="ev-nav-text">
                            <strong>Открыть маршрут в Waze</strong>
                            <small>Навигация до Viru väljak 4</small>
                        </span>
                    </a>
                </div>
            </div>
        
            {{-- Социальные сети --}}
            <div class="section-head text-center mt-5 mb-3">
                <h2 class="title mb-0">Мы в социальных сетях</h2>
                <p class="text-muted mt-2">Подписывайтесь — новости, анонсы и фотоотчёты</p>
            </div>
        
            <div class="row g-3 ev-social-grid">
                <div class="col-lg-4 col-md-6">
                    <a href="https://www.instagram.com/keelekohvik?utm_source=qr&igsh=MWYxN3BqbjNwOGxwcA==" target="_blank" rel="noopener"
                       class="ev-social-card d-flex align-items-center">
                        <span class="ev-social-icon" style="--icon-color:#E4405F;">
                            <i class="fab fa-instagram"></i>
                        </span>
                        <span class="ev-social-text">
                            <strong>Instagram</strong>
                            <small>Фото, анонсы, сторис</small>
                        </span>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a href="https://www.facebook.com/share/161aHLsUuT/" target="_blank" rel="noopener"
                       class="ev-social-card d-flex align-items-center">
                        <span class="ev-social-icon" style="--icon-color:#1877F2;">
                            <i class="fab fa-facebook-f"></i>
                        </span>
                        <span class="ev-social-text">
                            <strong>Facebook</strong>
                            <small>Новости и события</small>
                        </span>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a href="https://t.me/Keeleklubi" target="_blank" rel="noopener"
                       class="ev-social-card d-flex align-items-center">
                        <span class="ev-social-icon" style="--icon-color:#229ED9;">
                            <i class="fab fa-telegram"></i>
                        </span>
                        <span class="ev-social-text">
                            <strong>Telegram</strong>
                            <small>Оповещения и чат</small>
                        </span>
                    </a>
                </div>
            </div>
        
            {{-- CTA-лента с быстрыми контактами --}}
            <div class="ev-cta-ribbon mt-5">
                <div class="ev-cta-ribbon__inner">
                    <div class="ev-cta-ribbon__text">
                        <h5 class="mb-1">Остались вопросы?</h5>
                        <p class="mb-0">Напишите нам на почту или позвоните — ответим и поможем с записью.</p>
                    </div>
                    <div class="ev-cta-ribbon__actions">
                        <a href="mailto:uuedhorisondidtallinn@gmail.com" class="btn ev-btn ev-btn--light">
                            <i class="fa-solid fa-envelope me-2"></i> Написать Email
                        </a>
                        <a href="tel:+37254290030" class="btn ev-btn ev-btn--dark">
                            <i class="fa-solid fa-phone me-2"></i> Позвонить
                        </a>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
    
</div>
<style>
    :root{
  --ev-radius: 14px;
  --ev-soft: #f8fafc;
  --ev-border: #e5e7eb;
  --ev-shadow: 0 10px 24px rgba(15,23,42,.06);
}

/* Карточки навигации (Google / Waze) */
.ev-nav-card{
  width:100%; background:#fff; border:1px solid var(--ev-border);
  border-radius: var(--ev-radius); padding:14px 16px; box-shadow: var(--ev-shadow);
  transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
  text-decoration:none; color:inherit;
}
.ev-nav-card:hover{ transform: translateY(-2px); border-color:#cbd5e1; box-shadow: 0 12px 26px rgba(15,23,42,.08); }
.ev-nav-card .ev-nav-icon{
  display:inline-grid; place-items:center; width:44px; height:44px; border-radius:12px;
  background:#eef2ff; color:#4338ca; font-size:20px; flex:0 0 auto;
}
.ev-nav-card--waze .ev-nav-icon{ background:#e0f2fe; color:#0284c7; }
.ev-nav-card .ev-nav-text strong{ display:block; line-height:1.1; }
.ev-nav-card .ev-nav-text small{ display:block; color:#6b7280; }

/* Соцсети */
.ev-social-card{
  background:#fff; border:1px solid var(--ev-border); border-radius: var(--ev-radius);
  padding:14px 16px; box-shadow: var(--ev-shadow); gap:12px; text-decoration:none; color:inherit;
  transition: transform .15s ease, border-color .15s ease, box-shadow .15s ease;
}
.ev-social-card:hover{ transform: translateY(-2px); border-color:#cbd5e1; box-shadow: 0 12px 26px rgba(15,23,42,.08); }
.ev-social-icon{
  --icon-color:#111827;
  display:inline-grid; place-items:center; width:46px; height:46px; border-radius:12px;
  background: color-mix(in srgb, var(--icon-color) 10%, transparent);
  color: var(--icon-color); font-size:20px; flex:0 0 auto;
}
.ev-social-text strong{ display:block; line-height:1.1; }
.ev-social-text small{ display:block; color:#6b7280; }

/* CTA-лента */
.ev-cta-ribbon{
  background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);
  border-radius: 18px; color:#fff; box-shadow: var(--ev-shadow);
}
.ev-cta-ribbon__inner{
  display:flex; align-items:center; justify-content:space-between; gap:16px;
  padding:18px 20px;
}
.ev-cta-ribbon__text h5{ font-weight:800; }
.ev-cta-ribbon__text p{ opacity:.95; }
.ev-cta-ribbon__actions{ display:flex; gap:10px; flex-wrap:wrap; }
.ev-btn{
  border-radius: 999px; padding:10px 14px; font-weight:700; border:1px solid rgba(255,255,255,.25);
  box-shadow: 0 8px 18px rgba(0,0,0,.12);
}
.ev-btn--light{ background:#ffffff; color:#0f172a; }
.ev-btn--light:hover{ filter:brightness(0.95); }
.ev-btn--dark{ background: rgba(0,0,0,.15); color:#fff; }
.ev-btn--dark:hover{ background: rgba(0,0,0,.22); }

@media (max-width: 768px){
  .ev-cta-ribbon__inner{ flex-direction:column; align-items:flex-start; }
}

/* Мелкие косметические правки заголовков секций */
.section-head .title{ margin-bottom:0; }

.ev-contact-row{ margin-bottom: .5rem; }

.ev-info-card{
  background:#fff; border:1px solid var(--ev-border); border-radius: var(--ev-radius);
  padding:14px; text-decoration:none; color:inherit; gap:14px;
  box-shadow: var(--ev-shadow);
  transition: transform .15s ease, border-color .15s ease, box-shadow .15s ease, background .15s ease;
}
.ev-info-card:hover{
  transform: translateY(-2px);
  border-color:#cbd5e1;
  box-shadow: 0 12px 26px rgba(15,23,42,.08);
  background:#fcfcfd;
}

.ev-info-icon{
  display:grid; place-items:center; width:54px; height:54px; border-radius:12px;
  background: var(--icon-bg, #eef2ff); color: var(--icon-color, #4338ca);
  font-size:20px; flex:0 0 auto;
}
.ev-info-icon i{ line-height:1; }

.ev-info-content{ display:flex; flex-direction:column; justify-content:center; min-width:0; }
.ev-info-title{ font-weight:800; line-height:1.1; margin-bottom:2px; color:#0f172a; }
.ev-info-value{ color:#334155; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ev-info-hint{ color:#6b7280; }

@media (max-width: 576px){
  .ev-info-card{ padding:12px; gap:10px; }
  .ev-info-icon{ width:48px; height:48px; font-size:18px; }
  .ev-info-title{ font-size:15px; }
  .ev-info-value{ font-size:14px; }
}
</style>
@endsection
