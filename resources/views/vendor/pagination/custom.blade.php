@if ($paginator->hasPages())
    <ul class="pagination m-b15">
        {{-- Предыдущая страница --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link prev"><i class="fas fa-chevron-left"></i></span></li>
        @else
            <li class="page-item">
                <a class="page-link prev" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        @endif

        {{-- Номера страниц --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link"><span>{{ $page }}</span></span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}"><span>{{ $page }}</span></a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Следующая страница --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link next" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled"><span class="page-link next"><i class="fas fa-chevron-right"></i></span></li>
        @endif
    </ul>
@endif
