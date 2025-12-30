<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link rounded-pill mx-1" style="background-color:#49af45;color:#fff;border-color:#49af45;opacity:.65">
                    <i class="fas fa-angle-left"></i>
                </span>
            </li>
        @else
            <li class="page-item">
                <button class="page-link rounded-pill mx-1" wire:click="previousPage" style="background-color:#49af45;color:#fff;border-color:#49af45">
                    <i class="fas fa-angle-left"></i>
                </button>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                <button class="page-link rounded-pill mx-1" wire:click="gotoPage({{ $page }})" 
                   style="{{ $paginator->currentPage() == $page ? 'background-color:#49af45;color:#fff;border-color:#49af45' : 'background-color:transparent;color:#49af45;border:1px solid #49af45' }}">
                    {{ $page }}
                </button>
            </li>
        @endforeach
            
        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <button class="page-link rounded-pill mx-1" wire:click="nextPage" style="background-color:#49af45;color:#fff;border-color:#49af45">
                    <i class="fas fa-angle-right"></i>
                </button>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link rounded-pill mx-1" style="background-color:#49af45;color:#fff;border-color:#49af45;opacity:.65">
                    <i class="fas fa-angle-right"></i>
                </span>
            </li>
        @endif
    </ul>
</nav>
