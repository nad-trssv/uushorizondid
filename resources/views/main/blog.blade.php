@extends('layouts.front')

@section('content')
<div class="page-content bg-white mt-4">
    <section class="content-inner-1">
        <div class="container">
            <div class="row loadmore-content">
                @foreach($posts as $post)
                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="dz-card style-1 overlay-shine dz-img-effect zoom m-b30 w-100 d-flex flex-column">
                            <div class="dz-media" style="height: 250px; overflow: hidden;">
                                <a href="{{ route('blog.show', $post->slug) }}" style="height: 250px; display: block;">
                                    <img src="/storage/{{ $post->image ?? '/placeholders/600x400.svg' }}" alt="{{ $post->title }}" style="height: 250px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                            <div class="dz-info d-flex flex-column flex-grow-1">
                                <div class="dz-meta">
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);">
                                                <i class="flaticon-calendar-date"></i> 
                                                {{ \Carbon\Carbon::parse($post->published_at)->format('d M Y') }}
                                            </a>
                                        </li>
                                        <li class="dz-comment">
                                            <a href="javascript:void(0);">
                                                <i class="flaticon-chat-bubble"></i> 
                                                {{ $post->comments_count() ?? 0 }}
                                            </a>
                                        </li>
                                    </ul>
                                </div> 
                                <h5 class="dz-title">
                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                </h5>
                                <p class="flex-grow-1">{{ Str::limit($post->description, 120) }}</p>
                                <div class="mt-auto">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-primary btn-hover-2">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center m-t10">
                {{ $posts->links('vendor.pagination.custom') }}
            </div>
        </div>
    </section>
</div>
<style>

.dz-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5em;
}
</style>
@endsection