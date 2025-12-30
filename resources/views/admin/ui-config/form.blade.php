@extends('layout.admin.layout')

@php
use Illuminate\Support\Str;

$resource = 'uiConfig';
$routeBase = 'admin.ui-config'; // hasil: config-group
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
                    method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-20">

                    @csrf
                    @if($model)
                    @method('PUT')
                    @endif



                    <!-- Type -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-neutral-900">Type</label>
                        <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                            <option value="text_field" {{ old('type', $model->type ?? '') == 'text_field' ? 'selected' :
                                '' }}>Text Field</option>
                            <option value="text_area" {{ old('type', $model->type ?? '') == 'text_area' ? 'selected' :
                                '' }}>Text Area</option>
                            <option value="ckeditor" {{ old('type', $model->type ?? '') == 'ckeditor' ? 'selected' : ''
                                }}>CKEditor</option>
                            <option value="image" {{ old('type', $model->type ?? '') == 'image' ? 'selected' : ''
                                }}>Image</option>
                            <option value="file" {{ old('type', $model->type ?? '') == 'file' ? 'selected' : '' }}>File
                            </option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div class="mb-3" id="value-wrapper">
                        <label class="form-label fw-bold text-neutral-900">Value</label>
                        {{-- isi diganti via JS --}}
                    </div>

                    @if (!empty($model->type) && in_array($model->type, ['image','file']) && !empty($model->value))
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $model->value) }}" target="_blank">
                            @if($model->type === 'image')
                            <img src="{{ asset('storage/' . $model->value) }}" alt="Current Image" style="height: 100px;">
                            @else
                            Download File Saat Ini
                            @endif
                        </a>
                    </div>
                    @endif


                    <!-- Key -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-neutral-900">Key</label>
                        <input type="text" class="form-control @error('key') is-invalid @enderror" name="key" id="key"
                            value="{{ old('key', $model->key ?? '') }}" required>
                        @error('key')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Label -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-neutral-900">Label</label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror" name="label"
                            id="label" value="{{ old('label', $model->label ?? '') }}" required>
                        @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <!-- Group -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-neutral-900">Group</label>
                        <select class="form-select @error('ui_config_group_id') is-invalid @enderror"
                            name="ui_config_group_id" required>
                            <option value="">Pilih Group</option>
                            @foreach ($uiConfigGroups as $group)
                            <option value="{{ $group->id }}" {{ old('ui_config_group_id', $model->ui_config_group_id ??
                                '') == $group->id ? 'selected' : '' }}>
                                {{ $group->title }}
                            </option>
                            @endforeach
                        </select>
                        @error('ui_config_group_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="dev" value="1">




                    <!-- Tombol -->
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <a href="{{ route($routeBase . '.index') }}"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                        <button id="submit" type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                            {{ $model ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateValueField() {
        const type = document.getElementById('type').value;
        const wrapper = document.getElementById('value-wrapper');

        // Hapus field lama (dan destroy CKEditor kalau ada)
        if (CKEDITOR.instances['value']) {
            CKEDITOR.instances['value'].destroy(true);
        }
        wrapper.querySelectorAll('input, textarea').forEach(el => el.remove());

        if (type === 'ckeditor') {
            const textarea = document.createElement('textarea');
            textarea.name = 'value';
            textarea.id = 'value';
            textarea.className = 'form-control';
            textarea.rows = 6;
            textarea.value = @json(old('value', $model->value ?? ''));
            wrapper.appendChild(textarea);

            // Aktifkan CKEditor 4 dengan upload gambar
            setTimeout(() => {
                CKEDITOR.replace('value', {
                    filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form'
                });
            }, 50);
        } else if (type === 'text_field') {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'value';
            input.id = 'value';
            input.className = 'form-control';
            input.value = @json(old('value', $model->value ?? ''));
            wrapper.appendChild(input);
        } else if (type === 'text_area') {
            const textarea = document.createElement('textarea');
            textarea.name = 'value';
            textarea.id = 'value';
            textarea.className = 'form-control';
            textarea.rows = 4;
            textarea.value = @json(old('value', $model->value ?? ''));
            wrapper.appendChild(textarea);
        } else if (type === 'image' || type === 'file') {
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'value';
            input.id = 'value';
            input.className = 'form-control';
            if (type === 'image') input.accept = 'image/*';
            wrapper.appendChild(input);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('type').addEventListener('change', updateValueField);
        updateValueField(); // load pertama
    });
</script>


@endsection