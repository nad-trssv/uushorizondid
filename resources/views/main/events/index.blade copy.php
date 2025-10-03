@extends('layouts.front')

@section('content')
<div class="page-content bg-white">
    <section class="content-inner-1">
        <div class="container mt-4">
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
                    
                    // Определяем статус для мобильной версии
                    $mobileStatus = '';
                    $statusClass = '';
                    if (!$hasSpots) {
                        $mobileStatus = 'Мест нет';
                        $statusClass = 'status-full';
                    } elseif ($registrationOpen && $hasSpots) {
                        $mobileStatus = 'Записаться';
                        $statusClass = 'status-open';
                    }
                @endphp

                    <!-- Десктопная версия -->
                    <div class="{{ $colClass }} mb-4 d-none d-md-block">
                        <div class="dz-card style-1 blog-half overlay-shine dz-img-effect zoom m-b30">
                            <div class="dz-media">
                                <a href="{{ route('home') }}">
                                    <img src="{{ $image }}" alt="{{ $title }}" 
                                         class="event-image" 
                                         onerror="this.onerror=null;this.src='/storage/placeholders/600x400.svg';">
                                </a>
                            </div>
                            <div class="dz-info d-flex flex-column">
                                <div class="dz-meta">
                                    <ul>
                                        <li>
                                            <i class="flaticon-calendar-date text-primary me-1"></i> 
                                            <span class="meta-text">{{ $date }}</span>
                                        </li>
                                        <li>
                                            <i class="flaticon-price-tag text-success me-1"></i> 
                                            <span class="meta-text">
                                                {!! $event->price > 0 ? number_format($event->price, 2) . ' €' : '<span class="text-primary">Бесплатно!</span>' !!}
                                            </span>
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

                                <!-- Уведомление о малом количестве мест -->
                                @if($fewSpots && $registrationOpen && $hasSpots)
                                    <div class="few-spots-alert mb-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Осталось всего {{ $remainingSpots }} {{ $remainingSpots == 1 ? 'место' : ($remainingSpots < 5 ? 'места' : 'мест') }}
                                    </div>
                                @endif
                                <h5 class="dz-title title-limit mt-1 mb-2">
                                    <a href="{{ route('home') }}" class="event-title-link">{{ $title }}</a>
                                </h5>
                                <p class="desc-limit flex-grow-1">{{ strip_tags($description) }}</p>

                                <div class="read-btn mt-3">
                                    @if($eventPassed)
                                        <a href="{{ route('home') }}#gallery" class="btn btn-gray w-100">Смотреть как прошло</a>
                                    @elseif(!$registrationOpen)
                                        <span class="btn btn-gray w-100 disabled">Регистрация закрыта</span>
                                    @elseif(!$hasSpots)
                                        <span class="btn btn-danger w-100 disabled">Мест нет</span>
                                    @else
                                        <a href="{{ route('home') }}" class="btn btn-primary btn-hover-2 w-100">Записаться</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Мобильная версия -->
                    <div class="col-12 d-md-none">
                        <div class="mobile-event-card">
                            <a href="{{ route('home') }}" class="mobile-event-link {{ !$registrationOpen || $eventPassed || !$hasSpots ? 'disabled-link' : '' }}">
                                <div class="mobile-event-content">
                                    <div class="mobile-event-image">
                                        <img src="{{ $image }}" alt="{{ $title }}" 
                                             onerror="this.onerror=null;this.src='/storage/placeholders/600x400.svg';">
                                        @if($mobileStatus)
                                            <div class="mobile-event-status {{ $statusClass }}">
                                                {{ $mobileStatus }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mobile-event-info">
                                        <div class="mobile-event-meta">
                                            <span class="mobile-event-date">
                                                <i class="flaticon-calendar-date text-primary me-1"></i>{{ $date }}
                                            </span>

                                            <div class="mobile-event-time">
                                                <i class="flaticon-clock text-warning me-1"></i>
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} – 
                                                {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                            </div>
                                        
                                            <!-- Уведомление о малом количестве мест для мобильной версии -->
                                            @if($fewSpots && $registrationOpen && $hasSpots)
                                                <div class="mobile-few-spots">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    Осталось {{ $remainingSpots }} {{ $remainingSpots == 1 ? 'место' : ($remainingSpots < 5 ? 'места' : 'мест') }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="mobile-event-price">
                                            {!! $event->price > 0 ? number_format($event->price, 2) . ' €' : '<span class="text-primary">Бесплатно!</span>' !!}
                                        </span>
                                        <h6 class="mobile-event-title">{{ $title }}</h6>
                                        <p class="mobile-event-desc">{{ strip_tags($description) }}</p>
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
/* Мобильная версия */
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
    padding: 12px;
    position: relative;
}

.mobile-event-link.disabled-link {
    opacity: 0.7;
    pointer-events: none;
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
    min-width: 0; /* Для правильной работы text-overflow */
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
    color: #28a745;
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

/* Стили для статуса события */
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

.status-open {
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

/* Улучшения для очень маленьких экранов */
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

/* Десктопная версия */
.d-md-block .event-image {
    width: 100%;
    height: auto;
}

/* Уведомление о малом количестве мест для десктопной версии */
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

/* Уведомление о малом количестве мест для мобильной версии */
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
</style>
@endsection