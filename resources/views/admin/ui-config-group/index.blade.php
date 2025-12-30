@extends('layout.admin.layout')

@php
    use Illuminate\Support\Str;

    $resource = 'uiConfigGroup'; // Misalnya facility, culinary, etc
    $routeBase = 'admin.ui-config-group'; // output: config-groups
    $title = 'UI Config Group';
    $subTitle = $title;
    $collection = ${Str::plural($resource)} ?? collect();

    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('title', $title)
@section('content')
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">{{ $title }}</h5>
        <div class="d-flex gap-3">
            @can($routeBase . '.create')
            <a href="{{ route($routeBase . '.create') }}" class="btn btn-primary">
                
                Tambah
            </a>
            @endcan
               @can($routeBase . '.sort')
            <a href="{{ route($routeBase . '.sort') }}" class="btn btn-info">
                Urutan
            </a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collection as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <h6 class="text-md mb-0 fw-medium flex-grow-1">{{ $item->title }}</h6>
                            </div>
                        </td>
                        <td>
                            @can($routeBase . '.edit')
                            <a href="{{ route($routeBase . '.edit', $item->id) }}"
                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                <iconify-icon icon="lucide:edit"></iconify-icon>
                            </a>
                            @endcan
                            @can($routeBase . '.delete')
                            <form action="{{ route($routeBase . '.destroy', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <a href="javascript:void(0)"
                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                    onclick="deleteData(event, this)">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                </a>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
