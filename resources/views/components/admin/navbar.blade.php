<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="text-primary-light">
                    Welcome, {{ Auth::user()->name }}!
                </div>
                <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

                @php
                    $pendingCount = isset($pendingPenawaranApprovals) ? $pendingPenawaranApprovals->count() : 0;
                    $recentApprovedCount = isset($recentApprovedPenawaran) ? $recentApprovedPenawaran->count() : 0;
                @endphp
                @can('admin.penawaran.approval')
                <button type="button" class="notification-trigger" data-bs-toggle="modal" data-bs-target="#penawaranNotificationModal"
                    title="Penawaran menunggu ACC">
                    <iconify-icon icon="solar:bell-bing-bold-duotone" class="icon text-xl"></iconify-icon>
                    @if($pendingCount > 0)
                    <span class="badge-dot">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                    @endif
                </button>
                @elseif($recentApprovedCount > 0)
                <button type="button" class="notification-trigger" data-bs-toggle="modal" data-bs-target="#penawaranApprovedNotificationModal"
                    title="Penawaran Anda telah ACC">
                    <iconify-icon icon="solar:bell-bing-bold-duotone" class="icon text-xl"></iconify-icon>
                    <span class="badge-dot">{{ $recentApprovedCount > 9 ? '9+' : $recentApprovedCount }}</span>
                </button>
                @endif

                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                        <img src="https://img.icons8.com/?size=100&id=83190&format=png&color=808080" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">
                                    {{ Auth::user()->name }}
                                </h6>
                                
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                          <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="{{ route('admin.profile.index') }}">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My
                                    Profile
                                </a>
                            </li>
                        <ul class="to-top-list">
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="{{route('admin.logout')}}">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div><!-- Profile dropdown end -->
            </div>
        </div>
    </div>
</div>

@can('admin.penawaran.approval')
<div class="modal fade" id="penawaranNotificationModal" tabindex="-1" aria-labelledby="penawaranNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" id="penawaranNotificationModalLabel">Penawaran Menunggu ACC</h5>
                    <p class="text-sm text-muted mb-0">Daftar penawaran dengan status Draft 1.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($pendingCount === 0)
                <div class="text-center py-24">
                    <iconify-icon icon="solar:bell-bing-linear" class="text-4xl text-muted mb-12"></iconify-icon>
                    <p class="text-muted mb-0">Tidak ada penawaran yang perlu di ACC.</p>
                </div>
                @else
                <div class="list-group list-group-flush">
                    @foreach ($pendingPenawaranApprovals as $penawaran)
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <div class="fw-semibold">{{ $penawaran->kepada_no_spk ?? 'No. SPK tidak tersedia' }}</div>
                            <div class="text-muted text-sm">{{ $penawaran->nasabah_nama ?? $penawaran->kepada_nama ?? 'Nasabah tidak tersedia' }}</div>
                            <div class="text-xs text-muted">Updated {{ optional($penawaran->updated_at)->diffForHumans() }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.penawaran.edit', $penawaran->id) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                            <form action="{{ route('admin.penawaran.approve', $penawaran->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Set ACC 1</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('admin.penawaran.index') }}" class="btn btn-primary">Lihat Penawaran</a>
            </div>
        </div>
    </div>
</div>
@endcan

@cannot('admin.penawaran.approval')
    @if(isset($recentApprovedPenawaran) && $recentApprovedPenawaran->isNotEmpty())
    <div class="modal fade" id="penawaranApprovedNotificationModal" tabindex="-1" aria-labelledby="penawaranApprovedNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title" id="penawaranApprovedNotificationModalLabel">Penawaran Anda Disetujui</h5>
                        <p class="text-sm text-muted mb-0">Daftar penawaran milik Anda yang sudah ACC oleh admin.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group list-group-flush">
                        @foreach ($recentApprovedPenawaran as $penawaran)
                        <div class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <div class="fw-semibold">{{ $penawaran->kepada_no_spk ?? 'No. SPK tidak tersedia' }}</div>
                                <div class="text-muted text-sm">{{ $penawaran->nasabah_nama ?? $penawaran->kepada_nama ?? 'Nasabah tidak tersedia' }}</div>
                                <div class="text-xs text-muted">Disetujui {{ optional($penawaran->approved_at)->diffForHumans() }}</div>
                            </div>
                            <a href="{{ route('admin.penawaran.show', $penawaran->id) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endcannot