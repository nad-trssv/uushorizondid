@extends('layouts.front')

@section('title', __('seo.eventCalendar.page_title'))
@section('meta_title', __('seo.eventCalendar.meta_title'))
@section('meta_description', __('seo.eventCalendar.meta_description'))
@section('og_type', 'article')
@section('canonical', request()->url())

@push('head')
    <meta name="calendar-feed" content="{{ route('events.feed') }}">
    @vite(['resources/front/events-calendar.js'])
    
    <style>
        .ev-cal-wrap {
            border: 1px solid #e9ecef;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .04);
        }
        
        .ev-cal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #e9ecef;
        }
        
        .ev-cal-legend {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 13px;
        }
        
        .ev-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            display: inline-block;
        }
        
        .ev-dot--open { background: var(--primary, #7DA640); }
        .ev-dot--closed { background: #9CA3AF; }
        .ev-dot--past { background: #94A3B8; }
        .ev-dot--full { background: #facc15; }

        /* Стили для модального окна */
        .event-detail-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            padding: 8px 0;
        }
        
        .event-detail-item i {
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .event-detail-item span {
            line-height: 1.4;
        }
        
        .modal-footer {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .modal-footer .btn {
            flex: 1;
            min-width: 120px;
        }
        
        .btn-ev-primary {
            background: var(--primary, #7DA640);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-ev-secondary {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
        }

        @media (max-width: 575px) {
            .ev-cal-head {
                padding: 12px 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .ev-cal-legend {
                font-size: 12px;
                gap: 12px;
            }
            
            .modal-footer .btn {
                flex: 1 1 calc(50% - 4px);
                min-width: auto;
            }
        }

        @media (max-width: 480px) {
            .ev-cal-legend {
                font-size: 11px;
                gap: 8px;
            }
            
            .modal-footer {
                flex-direction: column;
            }
            
            .modal-footer .btn {
                flex: none;
                width: 100%;
            }
        }

        /* FullCalendar кастомизации */
        .fc .fc-daygrid-day-number {
            color: #111;
        }
        
        .fc .fc-col-header-cell-cushion {
            color: #333;
            font-weight: 600;
        }
        
        .fc .fc-toolbar-title {
            color: #111;
        }
        
        .fc .fc-daygrid-day.fc-day-past {
            background-color: rgba(148, 163, 184, 0.08);
        }
        
        .fc .fc-button {
            font-size: 13px;
            padding: 6px 12px;
            font-weight: 500;
        }
        
        .fc-event-price {
            background: #d4edda;
            color: #155724;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
        
        .fc-event-status {
            color: #666;
            font-size: 10px;
        }
    </style>
@endpush

@section('content')
<div class="page-content bg-white">
    <!-- Хлебные крошки -->
    <div class="dz-breadcrumb-bnr">
        <div class="container">
            <nav aria-label="breadcrumb" class="breadcrumb-row">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Мероприятия</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Календарь мероприятий</li>
                </ul>
            </nav>
        </div>
    </div>

    <section class="content-inner-1">
        <div class="container">
            <div class="ev-cal-wrap">
                <div class="ev-cal-head">
                    <div class="fw-bold fs-5">Календарь мероприятий</div>
                    <div class="ev-cal-legend">
                        <span><i class="ev-dot ev-dot--open"></i> Регистрация открыта</span>
                        <span><i class="ev-dot ev-dot--full"></i> Нет мест</span>
                        <span><i class="ev-dot ev-dot--closed"></i> Регистрация закрыта</span>
                        <span><i class="ev-dot ev-dot--past"></i> Мероприятие прошло</span>
                    </div>
                </div>
                <div id="eventsCalendar"></div>
            </div>
        </div>
    </section>

    <!-- Modal: Детали мероприятия -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body" id="eventDetails">
                    <!-- Сюда JS добавит данные -->
                </div>
                <div class="modal-footer flex-column flex-md-row gap-2">
                    <a href="#" id="detailBtn" class="btn btn-ev-modern btn-ev-info w-100" target="_blank">
                        <i class="fas fa-info-circle me-1"></i> Подробнее
                    </a>
                    <button type="button" id="registerBtn" class="btn btn-ev-modern btn-ev-success w-100" style="display: none;">
                        <i class="fas fa-user-plus me-1"></i> Регистрация
                    </button>
                    <button type="button" id="contactBtn" class="btn btn-ev-modern btn-ev-warning w-100" style="display: none;">
                        <i class="fas fa-phone me-1"></i> Связаться
                    </button>
                    <button type="button" class="btn btn-ev-modern btn-ev-cancel w-100" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Закрыть
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Modal: Форма регистрации -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('events.register') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Регистрация на мероприятие</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="event_id" id="registerEventId" value="">
                        <div class="mb-3">
                            <label class="form-label">Имя *</label>
                            <input type="text" name="first_name" class="form-control" required placeholder="Ваше имя">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Фамилия *</label>
                            <input type="text" name="last_name" class="form-control" required placeholder="Ваша фамилия">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required placeholder="you@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Телефон *</label>
                            <input type="tel" name="phone" class="form-control" required placeholder="+372">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ev-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-ev-primary">Отправить заявку</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection