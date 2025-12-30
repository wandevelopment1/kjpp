@extends('layout.admin.layout')

@php
$model = $items;
$routeBase = 'admin.pendekatan-penilaian';
$title = 'PendekatanPenilaian';
$subTitle = 'PendekatanPenilaian';
@endphp

@section('title', $title)

@section('content')
<div class="card h-100 p-0 radius-8">
    <div class="card-header border-bottom bg-base py-12 px-16 d-flex align-items-center flex-wrap gap-2 justify-content-between">
        <h5 class="card-title mb-0">{{ $subTitle }}</h5>
        <div class="d-flex gap-2">
            @can($routeBase . '.create')
            <a href="{{ route($routeBase . '.create') }}"
                class="btn btn-primary text-xs btn-sm px-8 py-8 radius-6 d-flex align-items-center gap-1">
                <iconify-icon icon="ic:baseline-plus" class="icon-sm line-height-1"></iconify-icon>
                <span>Create</span>
            </a>
            @endcan
        </div>
    </div>

    <div class="card-body p-16">
        <!-- Filter & Search -->
        <form method="GET" action="{{ route($routeBase . '.index') }}"
            class="d-flex align-items-center flex-wrap gap-2 mb-16 justify-content-end">
            <select name="per_page" class="form-select form-select-sm w-auto ps-8 py-4 radius-8 h-32-px"
                onchange="this.form.submit()">
                <option {{ request('per_page')==10 ? 'selected' : '' }}>10</option>
                <option {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
                <option {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                <option {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}"
                class="h-32-px w-auto border border-gray-300 rounded px-2 text-sm" placeholder="Search">
            <button type="submit"
                class="bg-base h-32-px w-32-px d-flex align-items-center justify-content-center radius-8">
                <iconify-icon icon="ion:search-outline" class="icon-sm"></iconify-icon>
            </button>
        </form>

        <!-- Table -->
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0 text-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model as $key => $item)
                    <tr>
                        <td>{{ ($model->currentPage() - 1) * $model->perPage() + $key + 1 }}</td>
                        <td>{{ $item->name ?? '-' }}</td>

                        <td class="text-center">
                            <div class="d-flex align-items-center gap-8 justify-content-center">
                                @can($routeBase . '.edit')
                                <a href="{{ route($routeBase . '.edit', $item->id) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="lucide:edit" class="icon-sm"></iconify-icon>
                                </a>
                                @endcan
                                @can($routeBase . '.delete')
                                <form action="{{ route($routeBase . '.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                        onclick="deleteData(event, this)">
                                        <iconify-icon icon="fluent:delete-24-regular" class="icon-sm"></iconify-icon>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-16">
            <span class="text-sm">Showing {{ $model->count() }} of {{ $model->total() }} entries</span>
            <x-admin.pagination :paginator="$model" />
        </div>
    </div>
</div>
@endsection
