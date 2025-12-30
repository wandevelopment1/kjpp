@extends('layout.admin.layout')
@php
$title = 'User Profle';
$subTitle = 'User Profile';
$script ='<script>
    // ======================== Upload Image Start =====================
                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $("#imagePreview").css("background-image", "url("+ e.target.result + ")");
                                $("#imagePreview").hide();
                                $("#imagePreview").fadeIn(650);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                    $("#imageUpload").change(function() {
                        readURL(this);
                    });
                    // ======================== Upload Image End =====================

                    // ================== Password Show Hide Js Start ==========
                    function initializePasswordToggle(toggleSelector) {
                        $(toggleSelector).on("click", function() {
                            $(this).toggleClass("ri-eye-off-line");
                            var input = $($(this).attr("data-toggle"));
                            if (input.attr("type") === "password") {
                                input.attr("type", "text");
                            } else {
                                input.attr("type", "password");
                            }
                        });
                    }
                    // Call the function
                    initializePasswordToggle(".toggle-password");
                    // ========================= Password Show Hide Js End ===========================
</script>';
@endphp

@section('content')
<div class="row gy-4">
    <div class="col-lg-6">
        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
            <div class="pb-24 ms-16 mb-24 me-16  ">
                <div class="mt-24">
                    <ul>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">
                                Full Name
                            </span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{$user->name}}
                            </span>
                        </li>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">
                                Email
                            </span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{$user->email}}
                            </span>
                        </li>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">
                                NIK
                            </span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{$user->nik}}
                            </span>
                        </li>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">
                                Pekerjaan
                            </span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{$user->pekerjaan}}
                            </span>
                        </li>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">
                                Alamat
                            </span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{$user->alamat}}
                            </span>
                        </li>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">
                                Bank
                            </span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{$user->bank}}
                            </span>
                        </li>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">
                                Rekening
                            </span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{$user->rekening}}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body p-24">
                <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab"
                            aria-controls="pills-edit-profile" aria-selected="true">
                            Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab"
                            aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                            Security
                        </button>
                    </li>

                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                        aria-labelledby="pills-edit-profile-tab" tabindex="0">
                        <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                        <!-- Upload Image Start -->
                        {{-- <div class="mb-24 mt-16">
                            <div class="avatar-upload">
                                <div
                                    class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                    <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                    <label for="imageUpload"
                                        class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                        <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                    </label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview">
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!-- Upload Image End -->
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="name"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name
                                            <span class="text-danger-600">*</span></label>
                                        <input type="text" name="name" class="form-control radius-8" id="name"
                                            placeholder="Enter Full Name" value="{{ $user->name }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="email"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                                class="text-danger-600">*</span></label>
                                        <input type="email" name="email" class="form-control radius-8" id="email"
                                            placeholder="Enter email address" value="{{ $user->email }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <button type="button"
                                    class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel"
                        aria-labelledby="pills-change-passwork-tab" tabindex="0">
                        <form action="{{ route('admin.profile.changePassword') }}" method="POST">
                            @csrf
                            <div class="mb-20">
                                <label for="current-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Current Password
                                    <span class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" name="current_password" class="form-control radius-8"
                                        id="current-password" placeholder="Enter Current Password*">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#current-password"></span>
                                </div>
                                @error('current_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-20">
                                <label for="your-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">New
                                    Password <span class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" name="new_password" class="form-control radius-8"
                                        id="your-password" placeholder="Enter New Password*">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#your-password"></span>
                                </div>
                                @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-20">
                                <label for="confirm-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Confirmed Password
                                    <span class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" name="confirm_password" class="form-control radius-8"
                                        id="confirm-password" placeholder="Confirm Password*">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#confirm-password"></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <button type="button"
                                    class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection