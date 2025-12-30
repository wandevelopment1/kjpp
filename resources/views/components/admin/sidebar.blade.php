<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div>
        <a href="#" class="sidebar-logo" target="_blank">
            <img src="{{ asset('storage/' . ui_value('web-setting', 'logo')) }}" alt="site logo" class="light-logo">
            <img src="{{ asset('storage/' . ui_value('web-setting', 'logo_white')) }}" alt="site logo"
                class="dark-logo">
            <img src="{{ asset('storage/' . ui_value('web-setting', 'icon')) }}" alt="site icon" class="logo-icon">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('admin.dashboard.index') }}">
                    <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="sidebar-menu-group-title">Pengaturan</li>

            {{-- UI Config --}}
            @php
            $cUiConfigGroup = app(App\Models\UiConfigGroup::class)
            ->whereHas('configs')
            ->orderBy('order')
            ->get(['title', 'slug', 'id']);
            @endphp
            @if (auth()->user()->canAny(['admin.ui-config-group.index', 'admin.ui-config.index']))
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:cog-outline" class="menu-icon"></iconify-icon>
                    <span>UI Config</span>
                </a>
                <ul class="sidebar-submenu">
                    <!-- @can('admin.ui-config-group.index')
                    <li>
                        <a href="{{ route('admin.ui-config-group.index') }}">
                            <iconify-icon icon="mdi:folder-cog-outline" class="menu-icon"></iconify-icon>
                            <span>Config Groups</span>
                        </a>
                    </li>
                    @endcan -->
                    @can('admin.ui-config.index')
                    <!-- <li>
                        <a href="{{ route('admin.ui-config.index') }}">
                            <iconify-icon icon="mdi:format-list-checks" class="menu-icon"></iconify-icon>
                            <span>Config Items</span>
                        </a>
                    </li> -->
                    @foreach ($cUiConfigGroup as $item)
                    <li>
                        <a href="{{ route('admin.ui-config.show', $item->slug) }}">
                            <iconify-icon icon="mdi:cog" class="menu-icon"></iconify-icon>
                            <span>{{ $item->title }}</span>
                        </a>
                    </li>
                    @endforeach
                    @endcan
                </ul>
            </li>
            @endif

            {{-- User & Role --}}
            @if (auth()->user()->canAny(['admin.user.index', 'admin.role.index']))
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:account-group" class="menu-icon"></iconify-icon>
                    <span>User & Role</span>
                </a>
                <ul class="sidebar-submenu">
                    @can('admin.user.index')
                    <li>
                        <a href="{{ route('admin.user.index') }}">
                            <iconify-icon icon="mdi:account-multiple" class="menu-icon"></iconify-icon>
                            <span>Kelola User</span>
                        </a>
                    </li>
                    @endcan
                    @can('admin.role.index')
                    <li>
                        <a href="{{ route('admin.role.index') }}">
                            <iconify-icon icon="mdi:account-key" class="menu-icon"></iconify-icon>
                            <span>Kelola Role</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            {{-- Penawaran --}}
            @can('admin.penawaran.index')
            <li>
                <a href="{{ route('admin.penawaran.index') }}">
                    <iconify-icon icon="mdi:file-document-edit-outline" class="menu-icon"></iconify-icon>
                    <span>Penawaran</span>
                </a>
            </li>
            @endcan

            {{-- Upload Tahap Akhir --}}
            @can('admin.tahap-akhir-upload.index')
            <li>
                <a href="{{ route('admin.tahap-akhir-upload.index') }}">
                    <iconify-icon icon="mdi:file-upload-outline" class="menu-icon"></iconify-icon>
                    <span>Upload Tahap Akhir</span>
                </a>
            </li>
            @endcan

            {{-- Master Data --}}
            @php
            $penanggungJawabPermissions = [
                'admin.penanggung-jawab.companies.index',
                'admin.penanggung-jawab.penanggung-penilai.index',
                'admin.penanggung-jawab.penilai.index',
                'admin.penanggung-jawab.reviewers.index',
                'admin.penanggung-jawab.inspeksi.index',
            ];
            $penggunaLaporanPermissions = [
                'admin.pengguna-laporan.pts.index',
                'admin.pengguna-laporan.nama.index',
                'admin.pengguna-laporan.jenis-pengguna.index',
                'admin.pengguna-laporan.jenis-industri.index',
            ];
            $kepadaPermissions = [
                'admin.kepada-kab-kota.index',
                'admin.kepada-provinsi.index',
            ];
            $nasabahPermissions = [
                'admin.status-kepemilikan.index',
                'admin.bidang-usaha.index',
            ];
            $penilaianPermissions = [
                'admin.tujuan.index',
                'admin.jenis-laporan.index',
                'admin.nilai.index',
                'admin.jenis-jasa.index',
                'admin.tipe-properti.index',
                'admin.pendekatan-penilaian.index',
                'admin.metode-penilaian.index',
            ];
            $obyekPenilaianPermissions = [
                'admin.obyek.index',
                'admin.kepemilikan.index',
            ];
            @endphp
            @if (auth()->user()->canAny(array_merge($penanggungJawabPermissions, $penggunaLaporanPermissions, $kepadaPermissions, $nasabahPermissions, $penilaianPermissions, $obyekPenilaianPermissions)))
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:database-outline" class="menu-icon"></iconify-icon>
                    <span>Master Data</span>
                </a>
                <ul class="sidebar-submenu">
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:account-group-outline" class="menu-icon"></iconify-icon>
                            <span>Penanggung Jawab</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @can('admin.penanggung-jawab.companies.index')
                            <li>
                                <a href="{{ route('admin.penanggung-jawab.companies.index') }}">
                                    <iconify-icon icon="mdi:office-building" class="menu-icon"></iconify-icon>
                                    <span>Nama Perusahaan</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.penanggung-jawab.penanggung-penilai.index')
                            <li>
                                <a href="{{ route('admin.penanggung-jawab.penanggung-penilai.index') }}">
                                    <iconify-icon icon="mdi:account-tie" class="menu-icon"></iconify-icon>
                                    <span>Penanggung Jawab Penilai</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.penanggung-jawab.penilai.index')
                            <li>
                                <a href="{{ route('admin.penanggung-jawab.penilai.index') }}">
                                    <iconify-icon icon="mdi:account-badge" class="menu-icon"></iconify-icon>
                                    <span>Penilai</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.penanggung-jawab.reviewers.index')
                            <li>
                                <a href="{{ route('admin.penanggung-jawab.reviewers.index') }}">
                                    <iconify-icon icon="mdi:account-eye" class="menu-icon"></iconify-icon>
                                    <span>Reviewer</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.penanggung-jawab.inspeksi.index')
                            <li>
                                <a href="{{ route('admin.penanggung-jawab.inspeksi.index') }}">
                                    <iconify-icon icon="mdi:magnify-scan" class="menu-icon"></iconify-icon>
                                    <span>Inspeksi</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:clipboard-text" class="menu-icon"></iconify-icon>
                            <span>Pengguna Laporan</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @can('admin.pengguna-laporan.pts.index')
                            <li>
                                <a href="{{ route('admin.pengguna-laporan.pts.index') }}">
                                    <iconify-icon icon="mdi:office-building-outline" class="menu-icon"></iconify-icon>
                                    <span>PT</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.pengguna-laporan.nama.index')
                            <li>
                                <a href="{{ route('admin.pengguna-laporan.nama.index') }}">
                                    <iconify-icon icon="mdi:account-box-outline" class="menu-icon"></iconify-icon>
                                    <span>Nama</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.pengguna-laporan.jenis-pengguna.index')
                            <li>
                                <a href="{{ route('admin.pengguna-laporan.jenis-pengguna.index') }}">
                                    <iconify-icon icon="mdi:account-cog-outline" class="menu-icon"></iconify-icon>
                                    <span>Jenis Pengguna</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.pengguna-laporan.jenis-industri.index')
                            <li>
                                <a href="{{ route('admin.pengguna-laporan.jenis-industri.index') }}">
                                    <iconify-icon icon="mdi:factory" class="menu-icon"></iconify-icon>
                                    <span>Jenis Industri</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:account-arrow-right-outline" class="menu-icon"></iconify-icon>
                            <span>Kepada (Penerbit SPK)</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @can('admin.kepada-kab-kota.index')
                            <li>
                                <a href="{{ route('admin.kepada-kab-kota.index') }}">
                                    <iconify-icon icon="mdi:city" class="menu-icon"></iconify-icon>
                                    <span>Kab./Kota</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.kepada-provinsi.index')
                            <li>
                                <a href="{{ route('admin.kepada-provinsi.index') }}">
                                    <iconify-icon icon="mdi:map-outline" class="menu-icon"></iconify-icon>
                                    <span>Provinsi</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:briefcase-account" class="menu-icon"></iconify-icon>
                            <span>Nasabah / Klien</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @can('admin.status-kepemilikan.index')
                            <li>
                                <a href="{{ route('admin.status-kepemilikan.index') }}">
                                    <iconify-icon icon="mdi:shield-account" class="menu-icon"></iconify-icon>
                                    <span>Status Kepemilikan</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.bidang-usaha.index')
                            <li>
                                <a href="{{ route('admin.bidang-usaha.index') }}">
                                    <iconify-icon icon="mdi:chart-box-outline" class="menu-icon"></iconify-icon>
                                    <span>Bidang Usaha</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:clipboard-list-outline" class="menu-icon"></iconify-icon>
                            <span>Penilaian</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @can('admin.tujuan.index')
                            <li>
                                <a href="{{ route('admin.tujuan.index') }}">
                                    <iconify-icon icon="mdi:target" class="menu-icon"></iconify-icon>
                                    <span>Tujuan</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.jenis-laporan.index')
                            <li>
                                <a href="{{ route('admin.jenis-laporan.index') }}">
                                    <iconify-icon icon="mdi:file-document-outline" class="menu-icon"></iconify-icon>
                                    <span>Jenis Laporan</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.nilai.index')
                            <li>
                                <a href="{{ route('admin.nilai.index') }}">
                                    <iconify-icon icon="mdi:scale-balance" class="menu-icon"></iconify-icon>
                                    <span>Nilai</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.jenis-jasa.index')
                            <li>
                                <a href="{{ route('admin.jenis-jasa.index') }}">
                                    <iconify-icon icon="mdi:handshake-outline" class="menu-icon"></iconify-icon>
                                    <span>Jenis Jasa</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.tipe-properti.index')
                            <li>
                                <a href="{{ route('admin.tipe-properti.index') }}">
                                    <iconify-icon icon="mdi:home-city-outline" class="menu-icon"></iconify-icon>
                                    <span>Tipe Properti</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.pendekatan-penilaian.index')
                            <li>
                                <a href="{{ route('admin.pendekatan-penilaian.index') }}">
                                    <iconify-icon icon="mdi:route" class="menu-icon"></iconify-icon>
                                    <span>Pendekatan Penilaian</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.metode-penilaian.index')
                            <li>
                                <a href="{{ route('admin.metode-penilaian.index') }}">
                                    <iconify-icon icon="mdi:tools" class="menu-icon"></iconify-icon>
                                    <span>Metode Penilaian</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:content-paste-search-outline" class="menu-icon"></iconify-icon>
                            <span>Obyek Penilaian</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @can('admin.obyek.index')
                            <li>
                                <a href="{{ route('admin.obyek.index') }}">
                                    <iconify-icon icon="mdi:shape-outline" class="menu-icon"></iconify-icon>
                                    <span>Obyek</span>
                                </a>
                            </li>
                            @endcan
                            @can('admin.kepemilikan.index')
                            <li>
                                <a href="{{ route('admin.kepemilikan.index') }}">
                                    <iconify-icon icon="mdi:shield-home-outline" class="menu-icon"></iconify-icon>
                                    <span>Kepemilikan</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                </ul>
            </li>
            @endif

        </ul>
    </div>
</aside>