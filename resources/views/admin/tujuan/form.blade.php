@extends('layout.admin.layout')

@php
$routeBase = 'admin.tujuan';
$model = isset($tujuan) ? $tujuan : null;

$title = $model ? 'Change' : 'Create';
$subTitle = $title;
@endphp

@section('title', $title)

@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card mt-24">
            <div class="card-body p-24">
                <form id="form"
                    action="{{ $model ? route($routeBase  . '.update', $model->id) : route($routeBase . '.store') }}"
                    method="POST" class="d-flex flex-column gap-20">

                    @csrf
                    @if($model)
                        @method('PUT')
                    @endif

                    <x-admin.form-input label="Nama" name="name" type="text" 
                        value="{{ old('name', $model->name ?? '') }}" placeholder="Masukkan nama" />

                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <a href="{{ route($routeBase . '.index') }}"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Back</a>
                        <button id="submit" type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                            {{ $title }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
