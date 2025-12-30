@extends('layout.admin.layout')

@php
$routeBase = $routeBase ?? '';
$title = $title ?? 'Pengguna Laporan';
$model = $model ?? null;
$formTitle = $model ? 'Edit ' . $title : 'Create ' . $title;
$extraFields = $extraFields ?? [];
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

                    @foreach ($extraFields as $field)
                        @php
                            $type = $field['type'] ?? 'text';
                            $name = $field['name'] ?? '';
                            $label = $field['label'] ?? 'Field';
                            $placeholder = $field['placeholder'] ?? '';
                            $value = old($name, $model[$name] ?? '');
                        @endphp
                        @if ($type === 'textarea')
                            <div class="d-flex flex-column gap-8">
                                <label class="text-sm fw-semibold" for="{{ $name }}">{{ $label }}</label>
                                <textarea id="{{ $name }}" name="{{ $name }}" class="form-control"
                                    placeholder="{{ $placeholder }}">{{ $value }}</textarea>
                            </div>
                        @else
                            <x-admin.form-input :label="$label" :name="$name" :type="$type"
                                :value="$value" :placeholder="$placeholder" />
                        @endif
                    @endforeach

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
