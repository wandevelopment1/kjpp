@extends('layout.admin.layout')

@php
$resource = 'users';
$routeBase = 'admin.user';
$model = isset($$resource) ? $$resource : null;
$isEdit = isset($users);
$title = $isEdit ? 'Edit User' : 'Create User';
$subTitle = $title;
@endphp

@section('content')
<div class="card h-100 p-0 radius-12">
    <div class="card-body p-24">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-8 col-lg-10">
                <div class="card border">
                    <div class="card-body">
                        <h6 class="text-md text-primary-light mb-16">{{$title}}</h6>

                        <form action="{{ $isEdit ? route($routeBase.'.update', $users->id) : route($routeBase.'.store') }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name"
                                       value="{{ old('name', $users->name ?? '') }}" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control"
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $users->email ?? '') }}" 
                                       required>
                                @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       {{ !$isEdit ? 'required' : '' }}>
                                @if($isEdit)
                                    <small>Leave empty to keep current password</small>
                                @endif
                                @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                    

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route($routeBase.'.index') }}" 
                                   class="btn btn-secondary">Cancel</a>
                                <button type="submit" 
                                        class="btn btn-primary">
                                    {{ $isEdit ? 'Update' : 'Create' }}
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