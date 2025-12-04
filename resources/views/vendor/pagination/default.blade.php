@if ($paginator->hasPages())
    <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-700">
            Menampilkan
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            sampai
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-medium">{{ $paginator->total() }}</span>
            data
        </div>
        
        <div class="flex space-x-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                    &laquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    &laquo;
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-1">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 bg-blue-600 text-white rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    &raquo;
                </a>
            @else
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                    &raquo;
                </span>
            @endif
        </div>
    </div>
@endif