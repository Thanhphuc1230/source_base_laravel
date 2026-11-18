@if ($paginator->hasPages())
    @php
        $isEn = (app()->getLocale() === 'en');
        $prevText = $isEn ? 'Previous' : 'Trước';
        $nextText = $isEn ? 'Next' : 'Sau';
        $prevAria = $isEn ? 'Previous page' : 'Trang trước';
        $nextAria = $isEn ? 'Next page' : 'Trang sau';
    @endphp
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 select-none w-full">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center h-9 sm:h-10 px-3 sm:px-4 text-xs font-semibold text-charcoal-400 bg-cream/70 border border-border-subtle/80 rounded-lg cursor-not-allowed opacity-60" aria-disabled="true" aria-label="{{ $prevAria }}">
                <i class="fa-solid fa-chevron-left text-[10px] mr-1.5 opacity-60"></i>
                <span>{{ $prevText }}</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-9 sm:h-10 px-3 sm:px-4 text-xs font-semibold text-charcoal bg-white border border-border-subtle rounded-lg hover:border-charcoal hover:bg-beige-warm/60 transition-all duration-200 active:scale-95 shadow-xs" aria-label="{{ $prevAria }}">
                <i class="fa-solid fa-chevron-left text-[10px] mr-1.5"></i>
                <span>{{ $prevText }}</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs font-semibold text-charcoal-400 select-none">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs font-bold text-white bg-charcoal border border-charcoal rounded-lg shadow-xs select-none" aria-current="page">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-xs font-semibold text-charcoal bg-white border border-border-subtle rounded-lg hover:border-charcoal hover:bg-beige-warm/60 transition-all duration-200 active:scale-95 shadow-xs">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-9 sm:h-10 px-3 sm:px-4 text-xs font-semibold text-charcoal bg-white border border-border-subtle rounded-lg hover:border-charcoal hover:bg-beige-warm/60 transition-all duration-200 active:scale-95 shadow-xs" aria-label="{{ $nextAria }}">
                <span>{{ $nextText }}</span>
                <i class="fa-solid fa-chevron-right text-[10px] ml-1.5"></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center h-9 sm:h-10 px-3 sm:px-4 text-xs font-semibold text-charcoal-400 bg-cream/70 border border-border-subtle/80 rounded-lg cursor-not-allowed opacity-60" aria-disabled="true" aria-label="{{ $nextAria }}">
                <span>{{ $nextText }}</span>
                <i class="fa-solid fa-chevron-right text-[10px] ml-1.5 opacity-60"></i>
            </span>
        @endif
    </nav>
@endif
