@extends('layouts.front')

@section('title', $pageTitle)
@section('meta_title', $pageTitle)
@section('meta_description', $metaDesc)
@section('og_type', 'article')
@section('canonical', request()->url())

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/vendor/magnific-popup/magnific-popup.css') }}">
@endpush

@section('content')
<div class="page-content bg-white">
    <!-- Хлебные крошки (RU) -->
    <div class="dz-breadcrumb-bnr">
        <div class="container">
            <nav aria-label="breadcrumb" class="breadcrumb-row">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Мероприятия</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $tr['title'] ?? 'Без названия' }}</li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="overflow-hidden">
        <div class="min-container">
            <div class="blog-single dz-card ev-card my-4">
                <!-- Заголовок -->
                <div class="post-header ev-post-header">
                    <h1 class="dz-title ev-title">{{ $tr['title'] ?? 'Без названия' }}</h1>
                    <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($tr['title']) }}&dates={{ $start->format('Ymd\THis\Z') }}/{{ $end->format('Ymd\THis\Z') }}&details={{ urlencode($metaDesc) }}&location={{ urlencode($tr['location'] ?? '') }}" target="_blank" class="btn btn-outline-primary btn-sm">Google Calendar</a>

                    <!-- Центрированная мета -->
                    <div class="dz-meta ev-meta ev-meta-center">
                        <ul>
                            <li class="dz-date d-flex align-items-center">
                                <i class="flaticon-calendar-date"></i>
                                {{ $start ? $start->format('d M Y') : '' }}
                                @if($start && $end)
                                    &nbsp;•&nbsp; {{ $start->format('H:i') }} – {{ $end->format('H:i') }}
                                @endif
                            </li>
                            <li class="dz-user d-flex align-items-center">
                                <i class="flaticon-price-tag"></i>
                                {!! ($event->price ?? 0) > 0 ? number_format($event->price,2).' €' : '<span class="text-primary">Бесплатно</span>' !!}
                            </li>
                            @if($event->max_participants)
                                <li class="dz-user d-flex align-items-center">
                                    <i class="flaticon-user"></i>
                                    @if(!$hasSpots)
                                        <span class="ev-tag ev-tag-danger">Мест нет</span>
                                    @else
                                        <span class="ev-tag {{ $fewSpots ? 'ev-tag-warn' : 'ev-tag-success' }}">
                                            Осталось {{ $remaining }}
                                        </span>
                                    @endif
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FULL-BLEED ГЕРОЙ (на всю ширину) --}}
    @if(!empty($mainImage))
        <div class="ev-hero-full">
            <img src="{{ $mainImage }}" alt="{{ $tr['title'] ?? 'Event' }}" onerror="this.onerror=null;this.src='{{ asset('/storage/placeholders/600x400.svg') }}'">
        </div>
    @endif

    <div class="content-inner overflow-hidden pt-4">
        <div class="min-container">
            <div class="blog-single dz-card ev-card">

                <!-- CTA / Статус -->
                @php
                    $deadline = $event->registration_deadline ? \Carbon\Carbon::parse($event->registration_deadline) : null;
                    $deadlinePassed = $deadline ? \Carbon\Carbon::now()->gt($deadline) : false;

                    $contactPhone = config('app.contact_phone', '+372 54290030');
                    $contactPhoneHref = preg_replace('/\s+/', '', $contactPhone);
                    $contactEmail = config('app.contact_email', 'uuedhorisondidtallinn@gmail.com');
                @endphp

                <div class="container my-3 text-center">
                    @if($eventPassed)
                        <div class="ev-cta ev-cta-past">Мероприятие прошло</div>

                    @elseif($deadlinePassed)
                        <div class="ev-cta ev-cta-neutral">Время на регистрацию прошло</div>

                    @elseif(!$hasSpots)
                        <div class="ev-cta-row">
                            <div class="ev-cta ev-cta-danger">Мест нет</div>
                            <div class="ev-contacts">
                                <a href="tel:{{ $contactPhoneHref }}" class="ev-contact">
                                    <i class="fas fa-phone"></i> {{ $contactPhone }}
                                </a>
                                <a href="mailto:{{ $contactEmail }}" class="ev-contact">
                                    <i class="fas fa-envelope"></i> {{ $contactEmail }}
                                </a>
                            </div>
                        </div>

                    @elseif(!$registrationOpen)
                        <div class="ev-cta ev-cta-neutral">Регистрация закрыта</div>

                    @else
                        <button type="button" class="ev-cta ev-cta-primary ev-cta-block-sm" data-bs-toggle="modal" data-bs-target="#registerModal" style="width: 100%;">
                            <i class="fas fa-pencil-alt mx-2"></i> Записаться
                        </button>
                    @endif
                </div>

                <!-- Короткое описание -->
                <div class="dz-info">
                    @if(!empty($tr['short_description']))
                        <div class="dz-post-text ev-lead">
                            <p>{{ $tr['short_description'] }}</p>
                        </div>
                    @endif

                    <!-- Инфо-блоки на светло-сером -->
                    @if(!empty($tr['location']) || !empty($tr['requirements']) || !empty($tr['included']))
                        <div class="row g-3 my-3 ev-info-grid">
                            @if(!empty($tr['location']))
                                <div class="col-md-4 col-12">
                                    <figure class="ev-info-card">
                                        <figcaption class="ev-info-title"><i class="fas fa-map-marker-alt"></i> Место</figcaption>
                                        <div class="ev-info-body">{{ $tr['location'] }}</div>
                                    </figure>
                                </div>
                            @endif
                            @if(!empty($tr['requirements']))
                                <div class="col-md-4 col-12">
                                    <figure class="ev-info-card">
                                        <figcaption class="ev-info-title"><i class="fas fa-clipboard-check"></i> Требования</figcaption>
                                        <div class="ev-info-body">{{ $tr['requirements'] }}</div>
                                    </figure>
                                </div>
                            @endif
                            @if(!empty($tr['included']))
                                <div class="col-md-4 col-12">
                                    <figure class="ev-info-card">
                                        <figcaption class="ev-info-title"><i class="fas fa-check-circle"></i> Что включено</figcaption>
                                        <div class="ev-info-body">{{ $tr['included'] }}</div>
                                    </figure>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Полное описание -->
                    @if(!empty($tr['full_description']))
                        <div class="dz-post-text ev-bodytext">
                            {!! nl2br(e($tr['full_description'])) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="container my-8">
            <!-- Галерея в стиле Gutenberg + лайтбокс -->
            @if(!empty($event->gallery) && count($event->gallery))
                <div class="ev-gallery-wrap" id="gallery">
                    <figure class="wp-container-5 wp-block-gallery-3 wp-block-gallery has-nested-images columns-6 is-cropped alignwide">
                        @foreach($event->gallery as $g)
                            @php
                                $img = preg_match('/^https?:\/\//i', $g->image) ? $g->image : asset('storage/'.$g->image);
                                $alt = $g->alt ?? ($tr['title'] ?? 'gallery');
                            @endphp
                            <figure class="wp-block-image size-large ev-fig" style="aspect-ratio:1/1; max-height:210px;">
                                <a href="{{ $img }}" class="ev-glink" title="{{ $alt }}">
                                    <img src="{{ $img }}" alt="{{ $alt }}" onerror="this.onerror=null;this.src='{{ asset('/storage/placeholders/600x400.svg') }}'">
                                </a>
                            </figure>
                        @endforeach
                    </figure>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Модальное окно регистрации (Bootstrap 5) --}}
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content ev-modal">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Запись на мероприятие</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <form method="POST" action="#">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Имя</label>
            <input type="text" name="name" class="form-control" required placeholder="Ваше имя">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required placeholder="you@example.com">
          </div>
          <div class="mb-3">
            <label class="form-label">Телефон</label>
            <input type="tel" name="phone" class="form-control" required placeholder="+372">
          </div>
          <input type="hidden" name="event_id" value="{{ $event->id ?? '' }}">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn ev-cta ev-cta-neutral" data-bs-dismiss="modal">Отмена</button>
          <button type="submit" class="btn ev-cta ev-cta-primary">Отправить заявку</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
/* ---------- Токены ---------- */
:root{
  --ev-radius: 12px;
  --ev-soft: #fafafa; --ev-soft-2:#f5f7f9; --ev-border:#e9ecef;
  --ev-text:#222; --ev-muted:#6b7280;
  --ev-primary:var(--primary); --ev-primary-600: var(--primary-600);
  --ev-danger-bg:#ffe3e3; --ev-danger:#8a1c1c; --ev-danger-b:#ffc9c9;
  --ev-warn-bg:#fff3cd; --ev-warn:#856404; --ev-warn-b:#ffeaa7;
  --ev-succ-bg:#d4edda; --ev-succ:#155724; --ev-succ-b:#cde9d6;
}

/* ---------- Full-bleed hero ---------- */
.ev-hero-full{
  width:100vw; position:relative; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw;
  overflow:hidden;
  max-height: 60vh;
}
.ev-hero-full img{
  width:100%; height:52vh; min-height:260px; object-fit:cover; display:block;
}
@media (max-width:576px){ .ev-hero-full img{ height:40vh; } }

/* ---------- Заголовок/мета ---------- */
.ev-card{ border-radius: var(--ev-radius); overflow:hidden; }
.ev-post-header{ padding: 18px 12px 6px; }
.ev-title{ font-size: clamp(22px, 2.4vw, 38px); line-height:1.18; color:var(--ev-text); margin-bottom:10px; text-align:center; }
.ev-meta ul{ display:flex; gap:14px; flex-wrap:wrap; padding-left:0; margin:0; }
.ev-meta li{ list-style:none; color: var(--ev-muted); font-size:14px; display:flex; align-items:center; gap:6px; }
.ev-meta-center ul{ justify-content:center; text-align:center; }

/* ---------- Тэги остатка мест ---------- */
.ev-tag{display:inline-block;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700;border:1px solid transparent;}
.ev-tag-success{ background:var(--ev-succ-bg); color:var(--ev-succ); border-color:var(--ev-succ-b); }
.ev-tag-warn{ background:var(--ev-warn-bg); color:var(--ev-warn); border-color:var(--ev-warn-b); }
.ev-tag-danger{ background:var(--ev-danger-bg); color:var(--ev-danger); border-color:var(--ev-danger-b); }

/* ---------- CTA (кнопки/статусы) ---------- */
.ev-cta{
  display:inline-flex; align-items:center; justify-content:center;
  min-height:46px; padding:10px 20px; border-radius:999px; font-weight:800;
  text-decoration:none; border:1px solid transparent; transition:.18s ease-in-out;
  box-shadow: 0 6px 16px rgba(0,0,0,.06);
}
.ev-cta-primary{ background:var(--ev-primary); color:#fff; border-color:var(--ev-primary-600); }
.ev-cta-primary:hover{ transform: translateY(-1px); filter:brightness(.97); }
.ev-cta-neutral{ background:#eef1f4; color:#374151; border-color:#e5e7eb; }
.ev-cta-danger{ background:var(--ev-danger-bg); color:var(--ev-danger); border-color:var(--ev-danger-b); }
.ev-cta-past{ background:#e2e3e5; color:#383d41; border-color:#d7d8da; }
.ev-cta-block-sm{ }
@media (max-width: 576px){ .ev-cta-block-sm{ width:100%; } }

/* Ряд для статуса "Мест нет" + контакты */
.ev-cta-row{ display:flex; align-items:center; justify-content:center; gap:14px; flex-wrap:wrap; }
.ev-contacts{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.ev-contact{
  display:inline-flex; align-items:center; gap:6px;
  padding:8px 12px; border:1px solid var(--ev-border); border-radius:999px;
  color:#374151; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,.04); font-weight:600; font-size:14px;
}
.ev-contact:hover{ text-decoration:none; filter:brightness(.98); }

/* ---------- Лид/тело ---------- */
.ev-lead p{ font-size:16px; color:#374151; margin: 12px 0 10px; text-align:center; }
.ev-bodytext{ color:#2f2f2f; }
.ev-bodytext p{ margin-bottom:12px; }

/* ---------- Инфо-блоки ---------- */
.ev-info-grid{ margin-top:8px; }
.ev-info-card{
  display:block; margin:0; background: var(--ev-soft);
  border:1px solid var(--ev-border); border-radius: var(--ev-radius);
  padding:16px 14px; overflow:hidden;
}
.ev-info-title{ font-weight:800; margin-bottom:8px; color:#111; display:flex; align-items:center; gap:8px; }
.ev-info-body{ color:#4b5563; line-height:1.5; }

/* ---------- Галерея Gutenberg ---------- */
.alignwide{max-width:1200px;margin-left:auto;margin-right:auto;}
.ev-fig{ border-radius:10px; overflow:hidden; background:#f6f6f6; }
.ev-fig img{ width:100%; height:100%; display:block; object-fit:cover; aspect-ratio:1/1; }
.ev-fig a::after{content:"";position:absolute;inset:0;transition:.2s;background:rgba(0,0,0,0);}
.ev-fig a:hover::after{background:rgba(0,0,0,.06);}

/* ---------- Модалка ---------- */
.ev-modal{ border-radius:16px; overflow:hidden; }
.ev-modal .modal-header{ border-bottom:1px solid var(--ev-border); }
.ev-modal .modal-footer{ border-top:1px solid var(--ev-border); }
.ev-modal .form-control{ border-radius:10px; }
</style>
@endsection

@push('scripts')
<script>
  jQuery(function($){
    // Лайтбокс галереи
    $('.wp-block-gallery').magnificPopup({
      delegate: 'a.ev-glink',
      type: 'image',
      gallery: { enabled: true },
      removalDelay: 150,
      fixedContentPos: true
    });

    // Фокус на имя при открытии модалки
    $('#registerModal').on('shown.bs.modal', function () {
      $(this).find('input[name="name"]').trigger('focus');
    });
  });
</script>
@endpush
