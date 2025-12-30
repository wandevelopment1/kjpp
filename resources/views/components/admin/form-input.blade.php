@props([
    'label' => '',
    'name' => '',
    'type' => 'text', // text, textarea, select, dll
    'value' => '',
    'options' => [],
    'placeholder' => '',
    'rows' => 3,
    'multiple' => false,
    'accept' => '',
    'step' => '1',
    'min' => null,
    'max' => null,
    'placeholderOption' => null, // null=default, false=hilang, string=custom
])

<div class="form-group mb-4">
    @if($label)
        <label class="form-label fw-bold text-neutral-900 mb-2">{{ $label }}</label>
    @endif

    {{-- Text --}}
    @if($type === 'text')
        <input type="text" class="form-control @error($name) is-invalid @enderror"
               name="{{ $name }}" placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}">
    
    {{-- Textarea --}}
    @elseif($type === 'textarea')
        <textarea class="form-control @error($name) is-invalid @enderror"
                  name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}">{{ old($name, $value) }}</textarea>
    
    {{-- CKEditor --}}
    @elseif($type === 'ckeditor')
       <textarea id="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
          name="{{ $name }}" rows="{{ $rows }}">{!! old($name, $value) !!}</textarea>

        <script>
            if(document.getElementById('{{ $name }}')) {
                CKEDITOR.replace('{{ $name }}', {
                    filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form'
                });
            }
        </script>
    
    {{-- File --}}
    @elseif($type === 'file')
        <input type="file" class="form-control @error($name) is-invalid @enderror"
               name="{{ $name }}" {{ $multiple ? 'multiple' : '' }} accept="{{ $accept }}"
               onchange="previewImage(this, '{{ $name }}_preview')">
        @if($value)
            <div class="mt-2">
                <img id="{{ $name }}_preview" src="{{ asset('storage/' . $value) }}" alt="Preview"
                     style="max-width:200px; max-height:200px;">
            </div>
        @else
            <img id="{{ $name }}_preview" style="display:none; max-width:200px; max-height:200px;" alt="Preview">
        @endif
        <script>
            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>

    {{-- Select --}}
    @elseif($type === 'select')
        <select name="{{ $name }}" id="{{ $name }}"
        class="form-select @error($name) is-invalid @enderror"
        {{ $multiple ? 'multiple' : '' }}>

        {{-- 🧠 logika fleksibel untuk placeholder option --}}
        @if($placeholderOption !== false)
            <option value="">
                {{ $placeholderOption ?? '-- Select --' }}
            </option>
        @endif

        @foreach($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" {{ old($name, $value) == $optValue ? 'selected' : '' }}>
                {{ $optLabel }}
            </option>
        @endforeach
    </select>

    {{-- Radio --}}
    @elseif($type === 'radio')
        <div class="d-flex gap-3">
            @foreach($options as $optValue => $optLabel)
                <div class="form-check">
                    <input class="form-check-input @error($name) is-invalid @enderror"
                           type="radio" name="{{ $name }}" id="{{ $name }}_{{ $optValue }}" value="{{ $optValue }}"
                           {{ old($name, $value) == $optValue ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $name }}_{{ $optValue }}">{{ $optLabel }}</label>
                </div>
            @endforeach
        </div>

    {{-- Date --}}
    @elseif($type === 'date')
        <input type="date" class="form-control @error($name) is-invalid @enderror"
               name="{{ $name }}" value="{{ old($name, $value) ? \Carbon\Carbon::parse(old($name, $value))->format('Y-m-d') : '' }}">

    {{-- Datetime --}}
    @elseif($type === 'datetime')
        <input type="datetime-local" class="form-control @error($name) is-invalid @enderror"
               name="{{ $name }}" value="{{ old($name, $value) }}">

    {{-- Tags input --}}
    @elseif($type === 'tags')
        <div class="input-group">
            <input type="text" class="form-control" name="{{ $name }}_input"
                   placeholder="{{ $placeholder }}" value="{{ old($name.'_input', $value) }}">
            <span class="input-group-text bg-neutral-100"> <i class="ri-price-tag-3-line"></i></span>
        </div>
        <script>
            document.querySelector('form').addEventListener('submit', function (e) {
                const tagInput = document.querySelector('input[name="{{ $name }}_input"]');
                const tags = tagInput.value.split(',').map(tag => tag.trim()).filter(tag => tag);
                for (let tag of tags) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '{{ $name }}[]';
                    input.value = tag;
                    this.appendChild(input);
                }
            });
        </script>

    {{-- Number --}}
    @elseif($type === 'number')
        <input type="number" class="form-control @error($name) is-invalid @enderror"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            step="{{ $step }}"
            @if(!is_null($min)) min="{{ $min }}" @endif
            @if(!is_null($max)) max="{{ $max }}" @endif>

    @endif

    @error($name)
    <small class="text-danger mt-1">{{ $message }}</small>
    @enderror
</div>
