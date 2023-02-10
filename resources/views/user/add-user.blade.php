@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
            <li class="breadcrumb-item active">Add User</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('save-user') }}" method="POST" enctype="multipart/form-data">
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
                            <label>Date of Birth</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="date" class="form-control" value="{{ old('dob') }}" name="dob">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Address</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="{{ old('address') }}" name="address">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Emergency Contact Number</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="tel" class="form-control" value="{{ old('ecn') }}" name="ecn">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Emergency Contact Relationship</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="{{ old('ecr') }}" name="ecr">
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
                            <select class="custom-select @error('state') is-invalid @enderror" name="state" onchange="changeEmailState(value)" required>
                                <option disabled>Select State</option>
                                <option value={{ \App\Models\User::DE_ACTIVE }}>De Active</option>
                                <option selected value={{ \App\Models\User::ACTIVE }}>Active</option>
                            </select>
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                    <div class="form-group row">
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

    <script>
        function changeEmailState(value){
            if(value == 0){
                $("#customCheck").prop('checked', false);
                $(".custom-checkbox").hide();
            }
            else{
                $("#customCheck").prop('checked', true);
                $(".custom-checkbox").show();
            }
        }
    </script>
@endsection
