@extends('layout.admin.layout')

@php
    use Illuminate\Support\Str;

    $resource = 'uiConfigGroup';
    $routeBase = 'admin.ui-config-group'; // hasil: config-group
    $model = isset($$resource) ? $$resource : null;
    $title = $model ? 'Ubah' : 'Tambah';
    $subTitle = $title;

    $script = '<script>
        const fileInput = document.getElementById("upload-file");
        const imagePreview = document.getElementById("uploaded-img__preview");
        const uploadedImgContainer = document.querySelector(".uploaded-img");
        
        fileInput.addEventListener("change", (e) => {
            if (e.target.files.length) {
                const src = URL.createObjectURL(e.target.files[0]);
                imagePreview.src = src;
                uploadedImgContainer.classList.remove("d-none");
            }
        });
    </script>';
@endphp


@section('content')
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="card mt-24">
            <div class="card-body p-24">
                <form id="form"
                    action="{{ $model ? route($routeBase  . '.update', $model->id) : route($routeBase . '.store') }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    class="d-flex flex-column gap-20">
                    
                    @csrf
                    @if($model)
                        @method('PUT')
                    @endif

                    

                    <!-- Judul -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-neutral-900">Judul</label>
                        <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $model->title ?? '') }}">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>  
                    

                    <!-- Tombol -->
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <a href="{{ route($routeBase . '.index') }}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                        <button id="submit" type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                            {{ $model ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
