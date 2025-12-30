@extends('layout.admin.layout')

@php
$routeBase = 'admin.tahap-akhir-upload';
$model = isset($tahapAkhirUpload) ? $tahapAkhirUpload : null;

$title = $model ? 'Perbarui File Tahap Akhir' : 'Upload File Tahap Akhir';
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
                    method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-20">

                    @csrf
                    @if($model)
                        @method('PUT')
                    @endif

                    <div class="alert alert-info" role="alert">
                        <strong>Petunjuk:</strong> Unggah file Excel tahap akhir dalam format <code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code> dengan ukuran maksimum 10MB.
                        Jika Anda memperbarui, file lama akan digantikan otomatis.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="form-control" required>
                        @error('excel_file')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($model)
                    <div class="bg-neutral-50 border rounded-3 p-3">
                        <div class="d-flex flex-column gap-1 text-sm">
                            <div><strong>Nama File:</strong> {{ $model->original_name }}</div>
                            <div><strong>Terakhir Diunggah:</strong> {{ optional($model->updated_at)->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @endif

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
