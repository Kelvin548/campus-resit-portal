@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-wrap items-center gap-1">
        {{-- Previous page --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="Previous" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-400">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous" class="rounded-lg border border-brand-200 px-3 py-2 text-sm font-medium text-brand-700 hover:bg-brand-50">Previous</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span aria-disabled="true" class="px-2 py-2 text-sm text-slate-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="rounded-lg border border-brand-600 bg-brand-600 px-3 py-2 text-sm font-semibold text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rounded-lg border border-brand-200 px-3 py-2 text-sm font-medium text-brand-700 hover:bg-brand-50" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next" class="rounded-lg border border-brand-200 px-3 py-2 text-sm font-medium text-brand-700 hover:bg-brand-50">Next</a>
        @else
            <span aria-disabled="true" aria-label="Next" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-400">Next</span>
        @endif
    </nav>
@endif
