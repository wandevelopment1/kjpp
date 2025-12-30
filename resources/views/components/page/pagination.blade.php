<ul class="pagination m-b30">
    {{-- Previous Page --}}
    @if ($paginator->onFirstPage())
        <li><a href="javascript:;">«</a></li>
    @else
        <li><a href="{{ $paginator->previousPageUrl() }}">«</a></li>
    @endif

    {{-- Pagination Elements --}}
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $range = [];

        // Always show first page
        $range[] = 1;

        // Show pages around current page
        $start = max(2, $current - 2);
        $end = min($last - 1, $current + 2);

        for ($i = $start; $i <= $end; $i++) {
            $range[] = $i;
        }

        // Always show last page if not already included
        if ($last > 1 && !in_array($last, $range)) {
            $range[] = $last;
        }

        // Remove duplicates and sort
        $range = array_unique($range);
        sort($range);
    @endphp

    @foreach ($range as $page)
        @if ($page == 1 && $current > 4)
            <li class="{{ $paginator->currentPage() == $page ? 'active' : '' }}">
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            </li>
            @if ($range[1] != 2)
                <li><span>...</span></li>
            @endif
        @elseif ($page == $last && $current < $last - 3)
            @if ($range[count($range) - 2] != $last - 1)
                <li><span>...</span></li>
            @endif
            <li class="{{ $paginator->currentPage() == $page ? 'active' : '' }}">
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            </li>
        @else
            <li class="{{ $paginator->currentPage() == $page ? 'active' : '' }}">
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            </li>
        @endif
    @endforeach

    {{-- Next Page --}}
    @if ($paginator->hasMorePages())
        <li><a href="{{ $paginator->nextPageUrl() }}">»</a></li>
    @else
        <li><a href="javascript:;">»</a></li>
    @endif
</ul>
