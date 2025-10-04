@extends('layouts.front')

@section('title', $pageTitle)
@section('meta_title', $pageTitle)
@section('meta_description', $metaDesc)
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
                    <li class="breadcrumb-item"><a href="{{ route('blog.index', [], false) }}">Новости</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $tr['title'] ?? 'Без названия' }}</li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="content-inner overflow-hidden">
        <div class="min-container">

            {{-- Сообщение об успехе --}}
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Ошибки валидации --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- blog start -->
            <div class="blog-single dz-card">

                <div class="post-header">
                    <h1 class="dz-title">{{ $post->title }}</h1>
                    <div class="dz-meta">
                        <ul>
                            <li class="dz-user">
                                <a href="javascript:void(0);">
                                    <i class="flaticon-user"></i>
                                    By <span>{{ $post->user?->name }}</span>
                                </a>
                            </li>
                            <li class="dz-date">
                                <a href="javascript:void(0);">
                                    <i class="flaticon-calendar-date"></i>
                                    {{ optional($post->published_at)->format('d M Y') }}
                                </a>
                            </li>
                            <li class="dz-comment">
                                <a href="javascript:void(0);">
                                    <i class="flaticon-chat-bubble"></i>
                                    {{ $post->comments?->count() ?? 0 }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                @php
                    $imagePath = $post->image ? '/storage/'.$post->image : asset('assets/images/no-image.jpg');
                @endphp

                <div class="dz-media alignfullwide">
                    <img src="{{ $imagePath }}" alt="{{ $post->title }}" style="height: 500px; width: 100%; object-fit: cover;">
                </div>

                <div class="dz-info">
                    <div class="dz-post-text">
                        <div class="section-head style-1">
                            <h2 class="title">{{ $post->title }}</h2>
                        </div>
                        <div class="text-conte">
                            {{-- описание поста (с переводом через аксессор) --}}
                            {!! nl2br(e($post->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Комментарии --}}
            <div class="clear" id="comment-list">
                <div class="comments-area" id="comments">
                    <h4 class="comments-title">
                        Комментарии ({{ $post->comments?->count() ?? 0 }})
                    </h4>

                    <div class="clearfix">
                        {{-- список комментариев без аватаров --}}
                        @if($post->comments && $post->comments->count())
                            <ul class="comment-list">
                                @foreach($post->comments as $comment)
                                    <li class="comment">
                                        <div class="comment-body">
                                            <div class="comment-author vcard">
                                                <cite class="fn">{{ $comment->name }}</cite>
                                                @if(!empty($comment->rating))
                                                    <span class="ms-2 rating-display">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $comment->rating)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="ms-1">({{ $comment->rating }}/5)</span>
                                                    </span>
                                                @endif
                                                <span class="ms-2 text-muted">
                                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i') }}
                                                </span>
                                            </div>
                                            <p class="font-14 mb-0">{{ $comment->content }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Пока нет комментариев. Будьте первым!</p>
                        @endif

                        {{-- Форма добавления комментария (все поля обязательны) --}}
                        <div class="comment-respond style-1" id="respond">
                            <h4 class="comment-reply-title" id="reply-title">
                                Оставить комментарий
                            </h4>

                            <form class="comment-form" id="commentform" method="post" action="{{ route('blog.comment.store', $post->slug) }}">
                                @csrf

                                <p class="comment-form-author">
                                    <label for="author">Имя <span class="required">*</span></label>
                                    <input type="text" name="name" id="author" value="{{ old('name') }}" placeholder="Ваше имя" required>
                                </p>

                                {{-- Рейтинг: обязательные звёзды --}}
                                <p class="comment-form-rating">
                                    <label for="rating">Оценка <span class="required">*</span></label>

                                    <div class="rating" role="radiogroup" aria-label="Оценка">
                                        {{-- Радио инпуты (доступность + required) --}}
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input
                                                type="radio"
                                                id="star{{ $i }}"
                                                name="rating"
                                                value="{{ $i }}"
                                                @checked(old('rating') == $i)
                                                required
                                            >
                                            <label class="star" for="star{{ $i }}" data-value="{{ $i }}" aria-label="{{ $i }} звёзд">
                                                <i class="far fa-star" aria-hidden="true"></i>
                                            </label>
                                        @endfor
                                    </div>

                                    {{-- Покажем ошибку прямо под рейтингом, если есть --}}
                                    @error('rating')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </p>

                                <p class="comment-form-comment">
                                    <label for="comment">Комментарий <span class="required">*</span></label>
                                    <textarea rows="6" name="content" id="comment" placeholder="Текст комментария" required>{{ old('content') }}</textarea>
                                </p>

                                <p class="form-submit">
                                    <button type="submit" class="btn btn-primary" id="submit">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Отправить
                                    </button>
                                </p>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rating = document.querySelector('.rating');
    if (!rating) return;

    const labels = rating.querySelectorAll('.star');
    const inputs = rating.querySelectorAll('input[name="rating"]');

    // функция закраски n звёзд
    function paint(n) {
        labels.forEach(label => {
            const v = Number(label.dataset.value);
            const icon = label.querySelector('i');
            if (v <= n) {
                label.classList.add('filled');
                icon.classList.remove('far'); 
                icon.classList.add('fas');
            } else {
                label.classList.remove('filled');
                icon.classList.add('far'); 
                icon.classList.remove('fas');
            }
        });
    }

    // при наведении — подсвечиваем
    labels.forEach(label => {
        label.addEventListener('mouseenter', () => paint(Number(label.dataset.value)));
        label.addEventListener('click', (e) => {
            const v = Number(label.dataset.value);
            const input = rating.querySelector('#star' + v);
            if (input) input.checked = true;
            paint(v);
        });
    });

    // при уходе курсора — возвращаемся к выбранной оценке
    rating.addEventListener('mouseleave', () => {
        const checked = rating.querySelector('input[name="rating"]:checked');
        paint(checked ? Number(checked.value) : 0);
    });

    // инициализация (с old('rating'))
    const initiallyChecked = rating.querySelector('input[name="rating"]:checked');
    paint(initiallyChecked ? Number(initiallyChecked.value) : 0);
});
</script>
@endpush