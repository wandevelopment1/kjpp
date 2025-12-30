@extends('layout.admin.layout')
@php
$title = 'Role'.(isset($role) ? ' Edit' : ' Create');
$subTitle = $title;
@endphp
@section('title',$title)

@section('content')
<div class="card h-100 p-0 radius-12">
    <div
        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <h3 class="card-title text-md fw-medium text-secondary-light mb-0">{{ isset($role) ? 'Edit Role' : 'Tambah Role'
            }}</h3>
    </div>
    <div class="card-body p-24">
        <form action="{{ isset($role) ? route('admin.role.update', $role->id) : route('admin.role.store') }}"
            method="POST">
            @csrf
            @if(isset($role))
            @method('PUT')
            @endif

            <div class="row">
                <div class="col-12 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Role</label>
                    <input type="text" class="form-control radius-8 @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $role->name ?? '') }}" placeholder="Masukkan Nama Role" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Description</label>
                    <textarea class="form-control radius-8 @error('description') is-invalid @enderror"
                        name="description" rows="3"
                        placeholder="Masukkan Description">{{ old('description', $role->description ?? '') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status</label>
                    <div class="d-flex align-items-center flex-wrap gap-28">
                        <div class="form-check checked-success d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{
                                old('status', $role->status ?? 1) == 1 ? 'checked' : '' }}>
                            <label
                                class="form-check-label line-height-1 fw-medium text-secondary-light text-sm d-flex align-items-center gap-1"
                                for="status_active">
                                <span class="w-8-px h-8-px bg-success-600 rounded-circle"></span>
                                Active
                            </label>
                        </div>
                        <div class="form-check checked-danger d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{
                                old('status', $role->status ?? 1) == 0 ? 'checked' : '' }}>
                            <label
                                class="form-check-label line-height-1 fw-medium text-secondary-light text-sm d-flex align-items-center gap-1"
                                for="status_inactive">
                                <span class="w-8-px h-8-px bg-danger-600 rounded-circle"></span>
                                Inactive
                            </label>
                        </div>
                    </div>
                    @error('status')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>

                @php
                $groupedPermissions = [];

                foreach ($permissions as $permission) {
                // Pisah berdasarkan titik
                $parts = explode('.', $permission->name);

                if (count($parts) >= 3) {
                $mainGroup = ucwords(str_replace('-', ' ', $parts[0])); // contoh: admin
                $subGroup = ucwords(str_replace('-', ' ', $parts[1])); // contoh: user
                $groupedPermissions[$mainGroup][$subGroup][] = $permission;
                } else {
                // Fallback kalau tidak sesuai pola
                $groupedPermissions['Other']['General'][] = $permission;
                }
                }
                @endphp

                <div class="col-12 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Permissions</label>
                    <br><br>
                    @foreach($groupedPermissions as $mainGroup => $subGroups)
                    <div class="mb-20">
                        <h5 class="fw-bold text-lg text-primary mb-10">{{ $mainGroup }}</h5>

                        @foreach($subGroups as $subGroup => $permissionGroup)
                        <div class="mb-12">
                            <h6 class="fw-semibold text-md text-neutral-700 mb-8">{{ $subGroup }}</h6>
                            <div class="row">
                                @foreach($permissionGroup as $permission)
                                <div class="col-md-3 mb-3">
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input radius-4 border border-neutral-400"
                                            type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            id="permission_{{ $permission->id }}" {{ (isset($role) &&
                                            $role->permissions->contains($permission->id)) ? 'checked' : '' }}>
                                        <label class="form-check-label text-secondary-light"
                                            for="permission_{{ $permission->id }}">
                                            {{ ucwords(str_replace(['-', '_'], ' ', Str::afterLast($permission->name,
                                            '.'))) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                    </div>
                    @endforeach

                    @error('permissions')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-20">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Approver Penawaran</label>
                    <p class="text-muted text-sm mb-12">Pilih akun berizin approval yang menerima permintaan dari role
                        ini. Kosongkan jika memakai alur default.</p>
                    <div class="row">
                        @foreach($approverCandidates as $approver)
                        <div class="col-md-4 mb-3">
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" name="approvers[]"
                                    value="{{ $approver->id }}" id="approver_{{ $approver->id }}"
                                    {{ in_array($approver->id, old('approvers', $selectedApprovers ?? [])) ? 'checked' : '' }}>

                                <label class="form-check-label" for="approver_{{ $approver->id }}">
                                    <span class="d-block fw-semibold">{{ $approver->name }}</span>
                                    <small class="text-muted">{{ $approver->email }}</small>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('approvers')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <a href="{{ route('admin.role.index') }}"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            Batal
                        </a>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection