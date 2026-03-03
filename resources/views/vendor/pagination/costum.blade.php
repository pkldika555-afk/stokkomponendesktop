{{-- resources/views/vendor/pagination/custom.blade.php --}}
@if ($paginator->hasPages())
<div class="flex items-center justify-between px-6 py-4 border-t border-slate-800/60">

    {{-- Info --}}
    <p class="font-mono-custom text-xs text-slate-500 hidden sm:block">
        Menampilkan
        <span class="text-slate-300">{{ $paginator->firstItem() }}</span>
        –
        <span class="text-slate-300">{{ $paginator->lastItem() }}</span>
        dari
        <span class="text-slate-300">{{ $paginator->total() }}</span>
        data
    </p>

    {{-- Pages --}}
    <div class="flex items-center gap-1 mx-auto sm:mx-0">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-700 cursor-not-allowed select-none">
                <i class="ri-arrow-left-s-line text-base"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 border border-transparent hover:border-slate-700 transition-all duration-150">
                <i class="ri-arrow-left-s-line text-base"></i>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-8 h-8 flex items-center justify-center font-mono-custom text-xs text-slate-600 select-none">
                    ···
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg font-mono-custom text-xs font-medium text-white select-none"
                              style="background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%); box-shadow: 0 0 14px rgba(99,102,241,0.35);">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg font-mono-custom text-xs text-slate-400 hover:text-white hover:bg-slate-800 border border-transparent hover:border-slate-700 transition-all duration-150">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 border border-transparent hover:border-slate-700 transition-all duration-150">
                <i class="ri-arrow-right-s-line text-base"></i>
            </a>
        @else
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-700 cursor-not-allowed select-none">
                <i class="ri-arrow-right-s-line text-base"></i>
            </span>
        @endif

    </div>
</div>
@endif