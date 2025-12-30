{{-- resources/views/components/pagination.blade.php --}}
@props(['paginator'])

@if ($paginator->hasPages())
    <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">
        {{-- First & Previous --}}
        @if($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                    d-flex align-items-center justify-content-center h-40-px">First</span>
            </li>
            <li class="page-item disabled">
                <span class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                    d-flex align-items-center justify-content-center h-40-px">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->url(1) }}" 
                   class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                   d-flex align-items-center justify-content-center h-40-px">First</a>
            </li>
            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                   d-flex align-items-center justify-content-center h-40-px">Previous</a>
            </li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($paginator->getUrlRange(max($paginator->currentPage() - 2, 1), min($paginator->currentPage() + 2, $paginator->lastPage())) as $page => $url)
            <li class="page-item">
                <a href="{{ $url }}" 
                   class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                   d-flex align-items-center justify-content-center h-40-px w-40-px 
                   {{ $page == $paginator->currentPage() ? 'border-primary-400 text-primary-600' : '' }}">
                   {{ $page }}
                </a>
            </li>
        @endforeach

        {{-- Next & Last --}}
        @if($paginator->hasMorePages())
            <li class="page-item">
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                   d-flex align-items-center justify-content-center h-40-px">Next</a>
            </li>
            <li class="page-item">
                <a href="{{ $paginator->url($paginator->lastPage()) }}" 
                   class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                   d-flex align-items-center justify-content-center h-40-px">Last</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                    d-flex align-items-center justify-content-center h-40-px">Next</span>
            </li>
            <li class="page-item disabled">
                <span class="page-link bg-base border text-secondary-light fw-medium radius-8 border-0 px-16 py-8 
                    d-flex align-items-center justify-content-center h-40-px">Last</span>
            </li>
        @endif
    </ul>
@endif
