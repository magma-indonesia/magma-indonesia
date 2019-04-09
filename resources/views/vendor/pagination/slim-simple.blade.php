@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="btn btn-primary disabled">@lang('pagination.previous')</span></li>
        @else
            <li class="page-item"><a class="btn btn-primary" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="btn btn-primary" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li>
        @else
            <li class="page-item disabled"><span class="btn btn-primary disabled">@lang('pagination.next')</span></li>
        @endif
    </ul>
@endif
