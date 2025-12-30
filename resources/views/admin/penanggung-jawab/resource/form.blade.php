@extends('layout.admin.layout')

@php
$routeBase = $routeBase ?? '';
$title = $title ?? 'Data';
$model = $model ?? null;
$formTitle = $model ? 'Edit ' . $title : 'Create ' . $title;
@endphp

@section('title', $formTitle)

@section('content')
<div class="row gy-4">
    <div class="col-lg-8 offset-lg-2">
        <div class="card mt-24">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ $formTitle }}</h5>
            </div>
            <div class="card-body p-24">
                <form id="form"
                    action="{{ $model ? route($routeBase . '.update', $model->id) : route($routeBase . '.store') }}"
                    method="POST" class="d-flex flex-column gap-20">

                    @csrf
                    @if($model)
                        @method('PUT')
                    @endif

                    <x-admin.form-input label="Nama" name="name" type="text"
                        value="{{ old('name', $model->name ?? '') }}" placeholder="Masukkan nama" />

                    <x-admin.form-input label="No. MAPPI" name="no_mappi" type="text"
                        value="{{ old('no_mappi', $model->no_mappi ?? '') }}" placeholder="Masukkan No. MAPPI" />

                    <x-admin.form-input label="No. Izin Penilai" name="no_izin_penilai" type="text"
                        value="{{ old('no_izin_penilai', $model->no_izin_penilai ?? '') }}" placeholder="Masukkan No. Izin Penilai" />

                    <x-admin.form-input label="No. RMK" name="no_rmk" type="text"
                        value="{{ old('no_rmk', $model->no_rmk ?? '') }}" placeholder="Masukkan No. RMK" />

                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <a href="{{ route($routeBase . '.index') }}"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Back</a>
                        <button id="submit" type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                            {{ $model ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
