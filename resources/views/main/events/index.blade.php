@extends('layouts.front')

@section('title', __('seo.events.page_title'))
@section('meta_title', __('seo.events.meta_title'))
@section('meta_description', __('seo.events.meta_description'))
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
                    <li class="breadcrumb-item active" aria-current="page">Мероприятия</li>
                </ul>
            </nav>
        </div>
    </div>
    <section class="content-inner-1">
        <div class="container">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('events.calendar') }}" class="btn btn-outline-primary w-auto">
                    <i class="flaticon-calendar-date text-primary me-2"></i> Календарь мероприятий
                </a>
            </div>
            <div class="row justify-content-center loadmore-content">
                @foreach($events as $index => $event)
                @php
                    $locale = app()->getLocale();
                    $translation = collect($event->translations)->firstWhere('language_id', $locale) ?? collect($event->translations)->first();
                    $title = $translation['title'] ?? 'Без названия';
                    $description = $translation['short_description'] ?? '';
                    $image = $event->image ? "/storage/{$event->image}" : '/placeholders/600x400.svg';
                    $date = \Carbon\Carbon::parse($event->start_time)->format('d M Y');
                    $slug = $event->slug;

                    $now = \Carbon\Carbon::now();
                    $registrationOpen = $event->registration_deadline && $now->lte($event->registration_deadline);
                    $eventPassed = $now->gt($event->end_time);
                    
                    // Используем метод countParticipants() вместо confirmed_participants_count
                    $participantsCount = $event->countParticipants();
                    $hasSpots = $participantsCount < $event->max_participants;
                    $remainingSpots = $event->max_participants - $participantsCount;
                    $fewSpots = $remainingSpots <= 3 && $remainingSpots > 0;

                    $colClass = ($index + 1) % 5 === 0 ? 'col-xl-12 col-lg-12' : 'col-xl-6 col-lg-8';
                    $url = route('events.show', ['slug' => $slug]);
                @endphp

                    <!-- Десктопная версия -->
                    <div class="{{ $colClass }} mb-4 d-none d-md-block">
                        <div class="dz-card style-1 blog-half overlay-shine dz-img-effect zoom m-b30">
                            <div class="dz-media">
                                <a href="{{ $url }}">
                                    <img src="{{ $image }}" alt="{{ $title }}" 
                                         class="event-image" 
                                         onerror="this.onerror=null;this.src='/storage/placeholders/600x400.svg';">
                                </a>
                            </div>
                            <div class="dz-info d-flex flex-column">

                                <h5 class="dz-title title-limit mt-1 mb-2">
                                    <a href="{{ $url }}" class="event-title-link">{{ $title }}</a>
                                </h5>
                                <p class="desc-limit flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($description), 140, '...') }}</p>
                                <div class="dz-meta">
                                    <ul>
                                        <li>
                                            <i class="flaticon-calendar-date text-primary me-1"></i> 
                                            <span class="meta-text">{{ $date }}</span>
                                        </li>
                                        <li>
                                            <i class="flaticon-clock text-warning me-1"></i>
                                            <span class="meta-text">
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} – 
                                                {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                            </span>
                                        </li>
                                    </ul>
                                </div> 
                                <div class="d-flex justify-content-start gap-2 align-items-center mb-2">
                                    <!-- Уведомление о малом количестве мест -->
                                    @if($fewSpots && $registrationOpen && $hasSpots)
                                        <div class="few-spots-alert">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Осталось всего {{ $remainingSpots }} {{ $remainingSpots == 1 ? 'место' : ($remainingSpots < 5 ? 'места' : 'мест') }}
                                        </div>
                                    @endif
                                    <div class="price-text">
                                        <i class="flaticon-price-tag text-success me-1"></i> 
                                        <span class="meta-text">
                                            {!! $event->price > 0 ? number_format($event->price, 2) . ' €' : '<span>Бесплатно!</span>' !!}
                                        </span>
                                    </div>
                                </div>

                                <div class="read-btn mt-3">
                                    @if($eventPassed)
                                        <a href="{{ $url }}#gallery" class="btn btn-gray w-100">Смотреть как прошло</a>
                                    @elseif(!$registrationOpen)
                                        <a href="{{ $url }}" class="btn btn-gray w-100">Регистрация закрыта</a>
                                    @elseif(!$hasSpots)
                                        <a href="{{ $url}}" class="btn btn-attention w-100">Мест нет</a>
                                    @else
                                        <a href="{{ $url }}" class="btn btn-primary btn-hover-2 w-100">Записаться</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Мобильная версия -->
                    <div class="col-12 d-md-none">
                        <div class="mobile-event-card">
                            <a href="{{ $url }}" class="mobile-event-link {{ !$registrationOpen || $eventPassed || !$hasSpots ? 'disabled-link' : '' }}">
                                <div class="mobile-event-content">
                                    <div class="mobile-event-image">
                                        <img src="{{ $image }}" alt="{{ $title }}" 
                                             onerror="this.onerror=null;this.src='/storage/placeholders/600x400.svg';">
                                    </div>
                                    <div class="mobile-event-info">

                                        <h6 class="mobile-event-title">{{ $title }}</h6>
                                        <p class="mobile-event-desc">{{ strip_tags($description) }}</p>
                                        <div class="mobile-event-meta">
                                            <span class="mobile-event-date">
                                                <i class="flaticon-calendar-date text-primary me-1"></i>{{ $date }}
                                            </span>

                                            <div class="mobile-event-time">
                                                <i class="flaticon-clock text-warning me-1"></i>
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} – 
                                                {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <!-- Верхние две "кнопки"-бейджа -->
                                            <div class="action-chips">
                                                @if($fewSpots && $registrationOpen && $hasSpots)
                                                    <span class="chip chip-warning" title="Осталось мест">
                                                        Осталось {{ $remainingSpots }} {{ $remainingSpots == 1 ? 'место' : ($remainingSpots < 5 ? 'места' : 'мест') }}
                                                    </span>
                                                @endif
                                        
                                                <span class="chip {{ $event->price > 0 ? 'chip-success' : 'chip-info' }}">
                                                    {!! $event->price > 0 ? number_format($event->price, 2) . ' €' : 'Бесплатно' !!}
                                                </span>
                                            </div>
                                        
                                            <!-- Нижняя основная кнопка (компактная) -->
                                            <div class="mobile-cta">
                                                @if($eventPassed)
                                                    <a href="{{ $url }}#gallery" class="btn-mobile btn-neutral w-100">Смотреть как прошло</a>
                                                @elseif(!$registrationOpen)
                                                    <span class="btn-mobile btn-neutral w-100 disabled">Регистрация закрыта</span>
                                                @elseif(!$hasSpots)
                                                    <span class="btn-mobile btn-attention w-100 disabled">Мест нет</span>
                                                @else
                                                    <a href="{{ $url }}" class="btn-mobile btn-primary w-100">Записаться</a>
                                                @endif
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center m-t10">
                {{ $events->links('vendor.pagination.custom') }}
            </div>
        </div>
    </section>
</div>

<style>
.mobile-event-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 12px;
    box-shadow: none;
}

.mobile-event-link {
    text-decoration: none;
    color: inherit;
    display: block;
    position: relative;
}

.mobile-event-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    width: 100%;
}

.mobile-event-image {
    flex: 0 0 70px;
    height: 70px;
    position: relative;
}

.mobile-event-image img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
}

.mobile-event-info {
    flex: 1;
    min-width: 0; 
}

.mobile-event-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}

.mobile-event-date {
    font-size: 11px;
    color: #666;
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.mobile-event-price {
    font-size: 11px;
    color: #1f8938;
    white-space: nowrap;
    display: block;
    margin-bottom: 4px;
}

.mobile-event-title {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.3;
    color: #333;
    margin: 0 0 4px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.mobile-event-desc {
    font-size: 12px;
    line-height: 1.3;
    color: #666;
    margin: 0 0 4px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.mobile-event-time {
    font-size: 11px;
    color: #666;
    display: flex;
    align-items: center;
}

.mobile-event-status {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 9px;
    padding: 2px 6px;
    border-radius: 8px;
    font-weight: 600;
    white-space: nowrap;
    z-index: 2;
}
.price-text {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    white-space: nowrap;
    z-index: 2;
}
.mobile-event-price {
    font-size: 9px;
    padding: 4px 8px;
    border-radius: 8px;
    font-weight: 600;
    white-space: nowrap;
    z-index: 2;
    width: max-content;
}

.status-open, .price-text, .mobile-event-price {
    background: #d4edda;
    color: #155724;
}

.status-closed {
    background: #f8d7da;
    color: #721c24;
}

.status-full {
    background: #fff3cd;
    color: #856404;
}

.status-passed {
    background: #e2e3e5;
    color: #383d41;
}

@media (max-width: 360px) {
    .mobile-event-content {
        gap: 10px;
    }
    
    .mobile-event-image {
        flex: 0 0 60px;
        height: 60px;
    }
    
    .mobile-event-image img {
        width: 60px;
        height: 60px;
        border-radius: 5px;
    }
    
    .mobile-event-title {
        font-size: 13px;
    }
    
    .mobile-event-desc {
        font-size: 11px;
    }
    
    .mobile-event-meta {
        flex-direction: row;
        align-items: flex-start;
        justify-content: start;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .mobile-event-status {
        font-size: 8px;
        padding: 1px 4px;
    }
}

.d-md-block .event-image {
    width: 100%;
    height: auto;
}

.few-spots-alert {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
    border-radius: 4px;
    padding: 6px 10px;
    font-size: 13px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    max-width: max-content;
}

.few-spots-alert i {
    font-size: 12px;
}

.mobile-few-spots {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 10px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    margin-bottom: 4px;
    max-width: max-content;
}

.mobile-few-spots i {
    font-size: 9px;
}
/* Единый стиль компактных "кнопок"-бейджей сверху */
.action-chips{
    display:flex;
    gap:6px;
    margin:6px 0 8px;
    overflow:hidden;
    flex-wrap:nowrap;
}
.chip{
    display:inline-block;
    max-width:100%;
    padding:4px 8px;
    border-radius:999px;
    font-size:10px;
    line-height:1.1;
    font-weight:600;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    border:1px solid transparent;
}
.chip-success{ background:#d4edda; color:#155724; border-color:#cde9d6; }
.chip-info{    background:#e2eafc; color:#1f3a8a; border-color:#d6defa; } 
.chip-warning{ background:#fff3cd; color:#856404; border-color:#ffeaa7; }    
.chip-attention{background:#ffe3e3; color:#8a1c1c; border-color:#ffc9c9;}     

.btn-mobile{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:100%;
    padding:8px 10px;     
    font-size:12px;           
    line-height:1.2;
    font-weight:600;
    border-radius:10px;
    border:1px solid transparent;
    text-decoration:none;
    user-select:none;
}
.btn-mobile.disabled{ pointer-events:none; opacity:.7; }

.btn-primary{ background:var(--primary); color:#fff; border-color:var(--primary); } 
.btn-neutral{ background:#f1f3f5; color:#3f3f46; border-color:#e5e7eb; } 
.btn-attention{ background:#ffe3e3; color:#8a1c1c; border-color:#ffc9c9; }

.mobile-cta{ margin-top:6px; }

.mobile-few-spots, .mobile-event-price{ display:none !important; }

@media (max-width:360px){
    .chip{ font-size:9px; padding:3px 6px; }
    .btn-mobile{ font-size:11px; padding:7px 9px; }
}
.btn-attention:hover {
    background: #ffc9c9;
    border-color: #ffb3b3;
    color: #661414;
}
</style>
@endsection