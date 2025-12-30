@extends('layout.admin.layout')

@php
    $routeBase = 'admin.tahap-akhir-upload';
@endphp

@section('title', 'Detail File Tahap Akhir')

@section('content')
<div class="card radius-12 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">{{ $upload->original_name }}</h5>
            <p class="mb-0 text-muted text-sm">Diunggah {{ optional($upload->created_at)->translatedFormat('d F Y H:i') }} oleh {{ $upload->uploader->name ?? 'Sistem' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route($routeBase . '.download', $upload->id) }}"
                class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2 px-12 py-8">
                <iconify-icon icon="mdi:download" class="icon-sm"></iconify-icon>
                <span>Unduh File</span>
            </a>
            <a href="{{ route($routeBase . '.index') }}"
                class="btn btn-light btn-sm d-inline-flex align-items-center gap-2 px-12 py-8">
                <iconify-icon icon="mdi:arrow-left" class="icon-sm"></iconify-icon>
                <span>Kembali</span>
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(empty($sheets))
            <div class="text-center text-muted py-5">
                <iconify-icon icon="mdi:file-alert" class="text-3xl mb-2"></iconify-icon>
                <p class="mb-0">Tidak ada data untuk ditampilkan.</p>
            </div>
        @else
            <ul class="nav nav-tabs" id="sheetTabs" role="tablist">
                @foreach($sheets as $index => $sheet)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="sheet-tab-{{ $index }}" data-bs-toggle="tab"
                        data-bs-target="#sheet-{{ $index }}" type="button" role="tab"
                        aria-controls="sheet-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                        {{ $sheet['name'] }}
                    </button>
                </li>
                @endforeach
            </ul>
            <div class="tab-content mt-3" id="sheetTabsContent">
                @foreach($sheets as $index => $sheet)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="sheet-{{ $index }}" role="tabpanel"
                    aria-labelledby="sheet-tab-{{ $index }}">
                    <div class="table-responsive scroll-sm shadow-sm border rounded-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    @foreach(array_keys($sheet['rows'][0]) as $header)
                                        <th class="text-nowrap px-3 py-2">{{ trim($header) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sheet['rows'] as $row)
                                    <tr>
                                        @foreach($row as $value)
                                            <td class="px-3 py-2">{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
            <p class="text-muted text-sm mt-3 mb-0">Menampilkan maksimal 200 baris pertama per sheet.</p>
        @endif
    </div>
</div>
@endsection
