@extends('layout.admin.layout')

@php
    $model = $items;
    $routeBase = $routeBase ?? 'admin.penawaran';
    $listRoute = $listRoute ?? $routeBase . '.index';
    $title = $title ?? 'Penawaran';
    $subTitle = $subTitle ?? $title;
    $isAccWorkflowList = $isAccWorkflowList ?? false;
    $statusScope = $statusScope ?? null;
    $showCreateButton = $showCreateButton ?? true;
    $searchTerm = trim(request('search', ''));
    $highlightTerm = function ($value) use ($searchTerm) {
        $value = $value ?? '-';

        if ($searchTerm === '' || $value === '-') {
            return e($value);
        }

        $escapedTerm = preg_quote($searchTerm, '/');

        return preg_replace("/($escapedTerm)/i", '<mark>$1</mark>', e($value));
    };
@endphp

@section('title', $title)

@section('content')
<div class="card h-100 p-0 radius-8">
    <div class="card-header border-bottom bg-base py-12 px-16 d-flex align-items-center flex-wrap gap-2 justify-content-between">
        <h5 class="card-title mb-0">{{ $subTitle }}</h5>
        @if ($showCreateButton)
        <div class="d-flex gap-2">
            @can($routeBase . '.create')
            <a href="{{ route($routeBase . '.create') }}"
                class="btn btn-primary text-xs btn-sm px-8 py-8 radius-6 d-flex align-items-center gap-1">
                <iconify-icon icon="ic:baseline-plus" class="icon-sm line-height-1"></iconify-icon>
                <span>Create</span>
            </a>
            @endcan
        </div>
        @endif
    </div>

    <div class="card-body p-16">
        <!-- Filter & Search -->
        <form method="GET" action="{{ route($listRoute) }}"
            class="d-flex align-items-center flex-wrap gap-2 mb-16 justify-content-end">
            <select name="per_page" class="form-select form-select-sm w-auto ps-8 py-4 radius-8 h-32-px"
                onchange="this.form.submit()">
                <option {{ request('per_page')==10 ? 'selected' : '' }}>10</option>
                <option {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
                <option {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                <option {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
            </select>
            <select name="status" class="form-select form-select-sm w-auto ps-8 py-4 radius-8 h-32-px"
                onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="draft_1" {{ request('status') === 'draft_1' ? 'selected' : '' }}>Draft 1</option>
                <option value="acc_1" {{ request('status') === 'acc_1' ? 'selected' : '' }}>Project Berjalan</option>
                <option value="acc_2" {{ request('status') === 'acc_2' ? 'selected' : '' }}>Final</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}"
                class="h-32-px w-auto border border-gray-300 rounded px-2 text-sm" placeholder="Search">
            <button type="submit"
                class="bg-base h-32-px w-32-px d-flex align-items-center justify-content-center radius-8">
                <iconify-icon icon="ion:search-outline" class="icon-sm"></iconify-icon>
            </button>
        </form>

        <!-- Table -->
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0 text-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Lingkup</th>
                        <th>No. Laporan</th>
                        <th>Nasabah</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($model as $key => $item)
                    <tr>
                        <td>{{ ($model->currentPage() - 1) * $model->perPage() + $key + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{!! $highlightTerm($item->kepada_no_lingkup ?? '-') !!}</div>
                        @php
                            $formattedDate = $item->kepada_tgl_lingkup
                                ? \Carbon\Carbon::parse($item->kepada_tgl_lingkup)->format('d M Y')
                                : '-';
                        @endphp
                            <div class="text-xs text-muted">{!! $highlightTerm($formattedDate) !!}</div>
                        </td>
                        <td>
                            @php
                                $isFinal = $item->status === 'acc_2';
                                $canEditLaporan = auth()->user()->can($routeBase . '.edit');
                                $laporanDisabled = (!$isFinal || !$canEditLaporan) ? 'disabled' : '';
                                $laporanDateValue = $item->laporan_tanggal
                                    ? \Illuminate\Support\Carbon::parse($item->laporan_tanggal)->format('Y-m-d')
                                    : '';
                            @endphp
                            <form action="{{ route($routeBase . '.laporan', $item->id) }}" method="POST"
                                class="laporan-inline-form d-flex flex-column gap-2" data-initial='@json([
                                    'nomor' => $item->laporan_nomor ?? '',
                                    'tanggal' => $laporanDateValue,
                                ])'>
                                @csrf
                                @method('PATCH')
                                <input type="text" name="laporan_nomor" class="form-control form-control-sm"
                                    placeholder="No. Laporan"
                                    value="{{ $item->laporan_nomor ?? '' }}"
                                    {{ $laporanDisabled }}>
                                <input type="date" name="laporan_tanggal" class="form-control form-control-sm"
                                    value="{{ $laporanDateValue }}"
                                    {{ $laporanDisabled }}>
                                <button type="submit" class="btn btn-sm btn-primary laporan-submit-btn d-none" {{ $laporanDisabled }}>
                                    Simpan
                                </button>
                                @if(!$isFinal)
                                    <small class="text-muted text-xs">Isi saat status Final</small>
                                @endif
                            </form>
                        </td>
                        <td>
                            <div class="fw-semibold">{!! $highlightTerm($item->nasabah_nama ?? $item->kepada_nama ?? '-') !!}</div>
                            <div class="text-xs text">
                                {!! $highlightTerm($item->nasabah_kode_pos ?? $item->kepada_kode_pos ?? '-') !!}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusLabel = match ($item->status) {
                                    'acc_1' => 'Project Berjalan',
                                    'acc_2' => 'Final',
                                    default => 'Draft 1',
                                };
                                $statusClass = match ($item->status) {
                                    'acc_2' => 'bg-primary-subtle text-primary-600',
                                    'acc_1' => 'bg-success-subtle text-success-600',
                                    default => 'bg-warning-subtle text-warning-600',
                                };

                                $nextStatus = match ($item->status) {
                                    'draft_1' => 'acc_1',
                                    'acc_1' => 'acc_2',
                                    default => null,
                                };
                                $nextLabel = match ($nextStatus) {
                                    'acc_1' => 'Set ACC 1',
                                    'acc_2' => 'Set ACC 2',
                                    default => null,
                                };
                            @endphp
                            <div class="d-flex flex-column gap-2">
                                <span class="badge {{ $statusClass }} px-12 py-6">{{ $statusLabel }}</span>
                                @can($routeBase . '.approval')
                                    @if($nextStatus)
                                        <form action="{{ route($routeBase . '.approve', $item->id) }}" method="POST" class="m-0 status-approve-form" data-status-label="{{ $nextLabel }}">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                                {{ $nextLabel }}
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                        <td>
                            @php
                                $ownerEmail = $item->owner->email ?? '-';
                                $ownerName = $item->owner->name ?? '-';
                            @endphp
                            <div class="fw-semibold">{!! $highlightTerm($ownerEmail) !!}</div>
                            <div class="text-xs text-muted">{!! $highlightTerm($ownerName) !!}</div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-8 justify-content-center">
                                <a href="{{ route($routeBase . '.show', $item->id) }}"
                                    class="bg-info-subtle text-info-600 bg-hover-info-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle"
                                    title="Detail Penawaran">
                                    <iconify-icon icon="ph:eye" class="icon-sm"></iconify-icon>
                                </a>
                                @can($routeBase . '.edit')
                                <a href="{{ route($routeBase . '.edit', $item->id) }}"
                                    class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="lucide:edit" class="icon-sm"></iconify-icon>
                                </a>
                                @endcan
                                @can($routeBase . '.delete')
                                <form action="{{ route($routeBase . '.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                        onclick="deleteData(event, this)">
                                        <iconify-icon icon="fluent:delete-24-regular" class="icon-sm"></iconify-icon>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text py-24">Belum ada data Penawaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-16">
            <span class="text-sm">Showing {{ $model->count() }} of {{ $model->total() }} entries</span>
            <x-admin.pagination :paginator="$model" />
        </div>
    </div>
</div>
<x-admin.sweetalert />
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.status-approve-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const statusLabel = form.dataset.statusLabel || 'ubah status';

                Swal.fire({
                    title: 'Konfirmasi Status',
                    text: `Apakah Anda yakin ingin ${statusLabel}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.laporan-inline-form').forEach(function (form) {
            const submitBtn = form.querySelector('.laporan-submit-btn');
            if (!submitBtn) {
                return;
            }

            const initial = (() => {
                try {
                    return JSON.parse(form.dataset.initial || '{}');
                } catch (error) {
                    return {};
                }
            })();

            const nomorInput = form.querySelector('input[name="laporan_nomor"]');
            const tanggalInput = form.querySelector('input[name="laporan_tanggal"]');

            const toggleButton = () => {
                const nomorValue = nomorInput ? nomorInput.value : '';
                const tanggalValue = tanggalInput ? tanggalInput.value : '';
                const changed = (nomorValue ?? '') !== (initial.nomor ?? '') ||
                    (tanggalValue ?? '') !== (initial.tanggal ?? '');

                submitBtn.classList.toggle('d-none', !changed);
            };

            toggleButton();

            [nomorInput, tanggalInput].forEach(function (input) {
                if (input) {
                    input.addEventListener('input', toggleButton);
                    input.addEventListener('change', toggleButton);
                }
            });
        });
    });
</script>
@endpush
