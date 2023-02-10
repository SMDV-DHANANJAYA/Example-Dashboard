@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('admins') }}">Admins</a></li>
            <li class="breadcrumb-item active">Update Admin</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('save-update-admin') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" value="{{ $admin->id }}" name="id" required>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>First Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ $admin->first_name }}" name="first_name" required>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Last Name</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="text" class="form-control" value="{{ $admin->last_name }}" name="last_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Email <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ $admin->email }}" name="email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Mobile</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="tel" class="form-control" value="{{ $admin->mobile }}" name="mobile">
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
