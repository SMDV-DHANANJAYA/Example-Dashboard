@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
            <li class="breadcrumb-item active">Update User Password</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('update-user-password-save') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" value="{{ $user->id }}" name="id" required>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Full Name</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="{{ $user->full_name }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Email</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
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
