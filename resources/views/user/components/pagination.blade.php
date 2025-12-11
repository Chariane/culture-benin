@if ($paginator->hasPages())
<nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
    <div class="-mt-px flex w-0 flex-1">
        @if ($paginator->onFirstPage())
        <span class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-300">
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Précédent
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" 
           class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Précédent
        </a>
        @endif
    </div>

    <div class="hidden md:-mt-px md:flex">
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center border-t-2 border-primary-500 px-4 pt-4 text-sm font-medium text-primary-600">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" 
                           class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>

    <div class="-mt-px flex w-0 flex-1 justify-end">
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" 
           class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
            Suivant
            <svg class="ml-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        @else
        <span class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-300">
            Suivant
            <svg class="ml-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </span>
        @endif
    </div>
</nav>
@endif