@extends('layout.admin.layout')

@php
$title = 'Role';
$subTitle = 'Role';
@endphp
@section('title',$title)

@section('content')
<div class="card h-100 p-0 radius-8">
    <div
        class="card-header border-bottom bg-base py-12 px-16 d-flex align-items-center flex-wrap gap-2 justify-content-between">
        <h5 class="card-title mb-0">{{$subTitle}}</h5>
        @can('admin.role.create')
        <a href="{{ route('admin.role.create') }}"
            class="btn btn-primary text-xs btn-sm px-8 py-8 radius-6 d-flex align-items-center gap-1">
            <iconify-icon icon="ic:baseline-plus" class="icon-sm line-height-1"></iconify-icon>
            <span>Tambah Role</span>
        </a>
        @endcan
    </div>

    <div class="card-body p-16">
        <form method="GET" action="{{ route('admin.role.index') }}"
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

        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0 text-sm">
                <thead>
                    <tr>
                        <th scope="col" class="text-nowrap">No</th>
                        <th scope="col" class="text-nowrap">Nama Role</th>
                        <th scope="col" class="text-nowrap">Description</th>
                        <th scope="col" class="text-center text-nowrap">Status</th>
                        <th scope="col" class="text-center text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                    <tr>
                        <td>{{ ($roles->currentPage() - 1) * $roles->perPage() + $key + 1 }}</td>

                        <td>{{ $role->name }}</td>
                        <td>
                            <p class="max-w-300-px mb-0 text-sm">{{ $role->description }}</p>
                        </td>
                        <td class="text-center">
                            @if($role->status)
                            <span
                                class="bg-success-focus text-success-600 border border-success-main px-16 py-2 radius-4 fw-medium text-xs">Active</span>
                            @else
                            <span
                                class="bg-danger-focus text-danger-600 border border-danger-main px-16 py-2 radius-4 fw-medium text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-8 justify-content-center">
                                @can('admin.role.edit')
                                <a href="{{ route('admin.role.edit', $role->id) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="lucide:edit" class="icon-sm"></iconify-icon>
                                </a>
                                @endcan
                                @can('admin.role.destroy')
                                <form action="{{ route('admin.role.destroy', $role->id) }}" method="POST"
                                    class="d-inline">
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

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-16">
            <span class="text-sm">Total {{ $roles->count() }} entries</span>
            <x-admin.pagination :paginator="$roles" />

        </div>

    </div>
</div>
@endsection