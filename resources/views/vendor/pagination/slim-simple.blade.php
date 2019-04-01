@if ($paginator->hasPages())
    <div class="ui pagination menu">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="btn btn-sm btn-outline-secondary mg-l-5 disabled"> <span>&laquo;</span> </a>
        @else
            <a class="btn btn-sm btn-outline-secondary mg-l-5" href="{{ $paginator->previousPageUrl() }}" rel="prev"> <span>&laquo;</span> </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="icon item disabled">{{ $element }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="btn btn-sm btn-secondary mg-l-5" href="{{ $url }}">{{ $page }}</a>
                    @else
                        <a class="btn btn-sm btn-outline-secondary mg-l-5" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="btn btn-sm btn-outline-secondary mg-l-5" href="{{ $paginator->nextPageUrl() }}" rel="next"><span>&raquo;</span></a>
        @else
            <a class="btn btn-sm btn-outline-secondary mg-l-5 disabled"><span>&raquo;</span></a>
        @endif
    </div>
@endif
