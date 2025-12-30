@extends('layout.admin.layout')
@php
$resource = 'users';
$routeBase = 'admin.user';
$model = isset($$resource) ? $$resource : null;
$title = 'User';
$subTitle = 'User';
@endphp

@section('content')
<div class="card h-100 p-0 radius-8">
    <div class="card-header border-bottom bg-base py-12 px-16 d-flex align-items-center flex-wrap gap-2 justify-content-between">
        <h5 class="card-title mb-0">{{$subTitle}}</h5>
        @can($routeBase . '.create')
        <a href="{{route($routeBase.'.create')}}" 
            class="btn btn-primary text-xs btn-sm px-8 py-8 radius-6 d-flex align-items-center gap-1">
            <iconify-icon icon="ic:baseline-plus" class="icon-sm line-height-1"></iconify-icon>
            <span>Tambah User</span>
        </a>
        @endcan
    </div>

    <div class="card-body p-16">
        <form method="GET" action="{{ route($routeBase.'.index') }}" class="d-flex align-items-center flex-wrap gap-2 mb-16 justify-content-end">
            <select name="per_page" class="form-select form-select-sm w-auto ps-8 py-4 radius-8 h-32-px"
                onchange="this.form.submit()">
                <option {{ request('per_page')==10 ? 'selected' : '' }}>10</option>
                <option {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
                <option {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                <option {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}"
                class="h-32-px w-auto border border-gray-300 rounded px-2 text-sm" placeholder="Search">

            <button type="submit" class="bg-base h-32-px w-32-px d-flex align-items-center justify-content-center radius-8">
                <iconify-icon icon="ion:search-outline" class="icon-sm"></iconify-icon>
            </button>
        </form>

        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0 text-sm">
                <thead>
                    <tr>
                        <th scope="col" class="text-nowrap">No</th>
                        <th scope="col" class="text-nowrap">Name</th>
                        <th scope="col" class="text-nowrap">Email</th>
                        <th scope="col" class="text-nowrap">Role</th>
                        <th scope="col" class="text-center text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $item)
                    <tr>
                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}</td>
                        <td>{{ \Str::limit($item->name, 40) }}</td>
                        <td>{{ \Str::limit($item->email, 40) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary-600 not-active px-18 py-11 dropdown-toggle toggle-icon"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $item->roles->first() ? $item->roles->first()->name : 'Belum Memiliki Role' }}
                                </button>
                                <ul class="dropdown-menu">
                                    @can('admin.user.edit')
                                    <li>
                                        <form action="{{ route('admin.user.syncRole', $item->id) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="roles[]" value="">
                                            <button type="submit"
                                                class="dropdown-item px-16 py-8 rounded text-danger bg-hover-neutral-100 text-hover-danger">
                                                Belum Memiliki Role
                                            </button>
                                        </form>
                                    </li>
                                    @foreach ($roles as $role)
                                        <li>
                                            <form action="{{ route('admin.user.syncRole', $item->id) }}" method="POST" id="update-role-form-{{ $item->id }}-{{ $role->id }}">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="roles[]" value="{{ $role->name }}">
                                                <button type="submit"
                                                    class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900">
                                                    {{ $role->name }}
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                    @endcan
                                </ul>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-8 justify-content-center">
                                @can('admin.user.edit')
                                <a href="{{route('admin.user.edit',$item->id)}}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="lucide:edit" class="icon-sm"></iconify-icon>
                                </a>
                                @endcan
                                @can('admin.user.delete')
                                <form action="{{ route('admin.user.destroy', $item->id) }}" method="POST" class="d-inline">
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
                        <span class="text-sm">Showing {{ $users->count() }} of {{ $users->total() }} entries</span>
            <x-admin.pagination :paginator="$users" />
        </div>
       
    </div>
</div>
@endsection