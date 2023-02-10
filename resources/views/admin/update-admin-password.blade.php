@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('admins') }}">Admins</a></li>
            <li class="breadcrumb-item active">Update Admin Password</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('save-update-admin-password') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" value="{{ $admin->id }}" name="id" required>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Full Name</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="text" class="form-control" value="{{ $admin->full_name }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Email</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="email" class="form-control" value="{{ $admin->email }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Password <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" minlength="8" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Confirm Password <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" minlength="8" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-md-2 col-sm-12">
                        </div>
                        <div class="col-md-10 col-sm-12">
                            @include('components.form-button')
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
