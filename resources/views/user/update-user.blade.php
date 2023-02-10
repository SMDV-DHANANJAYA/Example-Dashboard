@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
            <li class="breadcrumb-item active">Update User</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('update-user-web') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" value="{{ $user->id }}" name="id" required>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>First Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ $user->first_name }}" name="first_name" required>
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
                            <input type="text" class="form-control" value="{{ $user->last_name }}" name="last_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Email <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ $user->email }}" name="email" required>
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
                            <input type="tel" class="form-control" value="{{ $user->mobile }}" name="mobile">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Date of Birth</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" value="{{ explode(" ",$user->birthday)[0] }}" name="dob">
                            @error('dob')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Address</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="{{ $user->address }}" name="address">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Emergency Contact Number</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="tel" class="form-control" value="{{ $user->emergency_contact_number }}" name="ecn">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Emergency Contact Relationship</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="{{ $user->emergency_contact_relationship }}" name="ecr">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Photo ID</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('photo_id') is-invalid @enderror" id="customFile1" name="photo_id">
                                <label class="custom-file-label" for="customFile1">Choose file</label>
                                @error('photo_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Police Check</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('police_check') is-invalid @enderror" id="customFile2" name="police_check">
                                <label class="custom-file-label" for="customFile2">Choose file</label>
                                @error('police_check')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>WWCC</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('wwcc') is-invalid @enderror" id="customFile3" name="wwcc">
                                <label class="custom-file-label" for="customFile3">Choose file</label>
                                @error('wwcc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>State <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <select class="custom-select @error('state') is-invalid @enderror" name="state">
                                <option disabled>Select State</option>
                                <option @if($user->state == \App\Models\User::DE_ACTIVE) selected @endif value=0>De Active</option>
                                <option @if($user->state == \App\Models\User::ACTIVE) selected @endif value=1>Active</option>
                            </select>
                            @error('state')
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
