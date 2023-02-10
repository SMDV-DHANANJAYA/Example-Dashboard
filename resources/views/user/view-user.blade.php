@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
            <li class="breadcrumb-item active">View User</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-sm-12 col-md-2">
                        <label>First Name</label>
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <input type="text" class="form-control" value="{{ $user->first_name }}" disabled>
                    </div>
                </div>
                @if($user->last_name != null)
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Last Name</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="text" class="form-control" value="{{ $user->last_name }}" disabled>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <div class="col-sm-12 col-md-2">
                        <label>Email</label>
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                </div>
                @if($user->mobile != null)
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Mobile</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="tel" class="form-control" value="{{ $user->mobile }}" disabled>
                        </div>
                    </div>
                @endif
                @if($user->birthday != null)
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Date of Birth</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="date" class="form-control" value="{{ explode(" ",$user->birthday)[0] }}" disabled>
                        </div>
                    </div>
                @endif
                @if($user->address != null)
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Address</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="text" class="form-control" value="{{ $user->address }}" disabled>
                        </div>
                    </div>
                @endif
                @if($user->emergency_contact_number != null)
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Emergency Contact Number</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="tel" class="form-control" value="{{ $user->emergency_contact_number }}" disabled>
                        </div>
                    </div>
                @endif
                @if($user->emergency_contact_relationship != null)
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-2">
                            <label>Emergency Contact Relationship</label>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <input type="text" class="form-control" value="{{ $user->emergency_contact_relationship }}" disabled>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <div class="col-sm-12 col-md-2">
                        <label>State</label>
                    </div>
                    <div class="col-sm-12 col-md-10">
                        <input type="text" class="form-control" value="@if($user->state == \App\Models\User::DE_ACTIVE) De Active @else Active @endif" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container text-center d-flex justify-content-around">
                @if($user->photo_id_path != null)
                    <div class="col col-sm-12 col-md-3">
                        <label>Photo ID</label>
                        <a href="{{ $user->photo_id_url }}" target="_blank"><img src="{{ $user->photo_id_url }}" class="card w-100 img-fluid" style="height: 70%" alt=""></a>
                    </div>
                @endif
                @if($user->police_check_path != null)
                    <div class="col col-sm-12 col-md-3">
                        <label>Police Check</label>
                        <a href="{{ $user->police_check_url }}" target="_blank"><img src="{{ $user->police_check_url }}" class="card w-100 img-fluid" style="height: 70%" alt=""></a>
                    </div>
                @endif
                @if($user->wwcc_path != null)
                    <div class="col col-sm-12 col-md-3">
                        <label>WWCC</label>
                        <a href="{{ $user->wwcc_url }}" target="_blank"><img src="{{ $user->wwcc_url }}" class="card w-100 img-fluid" style="height: 70%" alt=""></a>
                    </div>
                @endif
            </div>
        </div>
        <br>
        <div class="btn-group btn-group-sm mr-1" role="group" aria-label="View User">
            <button id="btnGroupDrop1" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">View</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="{{ route('view-user-locations',['id' => $user->id]) }}">Locations</a>
                <a class="dropdown-item" href="{{ route('attendances',['user_id' => $user->id]) }}">Attendance</a>
                <a class="dropdown-item" href="{{ route('payrolls',['user_id' => $user->id]) }}">Payroll</a>
            </div>
        </div>
        <a href="{{ route('update-user',['id' => $user->id]) }}" class="btn btn-sm btn-outline-success mr-1">Update</a>
        <a href="{{ route('update-user-password',['id' => $user->id]) }}" class="btn btn-sm btn-outline-warning mr-1">Update Password</a>
        <button onclick="deleteUser('{{ route('delete-user',['id' => $user->id]) }}')" class="btn btn-sm btn-outline-danger">Delete</button>
    </div>

    <script>
        function deleteUser(link){
            Notiflix.Confirm.show(
                'Confirm Delete',
                'Are you sure?',
                'Yes',
                'No',
                function okCb() {
                    window.location = link;
                },
            );
        }
    </script>
@endsection
