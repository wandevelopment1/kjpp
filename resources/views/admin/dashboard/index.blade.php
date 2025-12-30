@extends('layout.admin.layout')

@php
    $title = 'Dashboard';
    $subTitle = 'Main';
@endphp 

@section('content')
@if(!empty($dashboardScopeLabel))
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
    <iconify-icon icon="mdi:information-outline" class="text-lg"></iconify-icon>
    <span>{{ $dashboardScopeLabel }}</span>
</div>
@endif

<div class="row gy-4">
    <div class="col-12">
        <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 g-3">
            @foreach($stats as $stat)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text text-xs text-uppercase mb-1">{{ $stat['label'] }}</p>
                                <h4 class="fw-semibold mb-0">{{ number_format($stat['value']) }}</h4>
                            </div>
                            <div class="w-44-px h-44-px rounded-circle d-flex justify-content-center align-items-center {{ $stat['accent'] }}">
                                <iconify-icon icon="{{ $stat['icon'] }}" class="text-lg"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Penawaran Terbaru</h5>
                    <p class="text-sm text mb-0">5 entri terakhir yang masuk sistem</p>
                </div>
                <a href="{{ route('admin.penawaran.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="">
                <div class="table-responsive scroll-sm">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nasabah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPenawaran as $penawaran)
                            <tr>
                                <td>{{ $penawaran->kepada_nama ?? 'Tanpa Nama' }}</td>
                                <td>
                                    @php
                                        $statusLabel = match ($penawaran->status) {
                                            'acc_1' => 'Project Berjalan',
                                            'acc_2' => 'Final',
                                            default => 'Draft 1',
                                        };
                                        $statusClass = match ($penawaran->status) {
                                            'acc_1' => 'badge bg-info-subtle text-info-600',
                                            'acc_2' => 'badge bg-success-subtle text-success-600',
                                            default => 'badge bg-secondary-subtle text-secondary-600',
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>{{ optional($penawaran->created_at)->translatedFormat('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text py-4">
                                    Belum ada penawaran yang ditampilkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
