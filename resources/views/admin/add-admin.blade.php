@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('admins') }}">Admins</a></li>
            <li class="breadcrumb-item active">Add Admin</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('save-admin') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>First Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" name="first_name" required>
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Last Name</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="{{ old('last_name') }}" name="last_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Email <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Mobile</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="tel" class="form-control" value="{{ old('mobile') }}" name="mobile">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Password <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" minlength="8" required>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Confirm Password <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
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
