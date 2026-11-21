@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Điều hướng phân trang">
        <div class="pagination-summary">
            Hiển thị {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} / {{ $paginator->total() }} kết quả
        </div>

        <div class="pagination-links">
            @if ($paginator->onFirstPage())
                <span class="pagination-link disabled" aria-disabled="true">‹ Trước</span>
            @else
                <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Trước</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-link ellipsis" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <span class="pagination-link active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-link" href="{{ $url }}" aria-label="Trang {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Sau ›</a>
            @else
                <span class="pagination-link disabled" aria-disabled="true">Sau ›</span>
            @endif
        </div>
    </nav>
@endif
