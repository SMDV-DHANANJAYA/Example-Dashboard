@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Users</li>
        </ol>
        <div class="d-flex justify-content-between">
            <a href="{{ route('add-user') }}" class="btn btn-sm btn-outline-primary mb-4"><i class="fa fa-user-plus"></i>&nbsp;&nbsp;Add New User</a>
            @include('components.search')

        </div>

        <div class="row">
            <div class="col-12">
                @if($users->isNotEmpty())
                    <table class="table table-hover table-responsive-md table-responsive-sm">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Created at</th>
                            <th>State</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d h:i A') }}</td>
                                    <td>
                                        @if($user->login_state == \App\Models\User::LOGIN)
                                            <span class="text-success" style="font-size: 10px" data-toggle="tooltip" data-placement="bottom" title="Online"><i class="fa fa-circle"></i></span>
                                        @else
                                            <span class="text-danger" style="font-size: 10px" data-toggle="tooltip" data-placement="bottom" title="Offline"><i class="fa fa-circle"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group btn-group-sm mr-1" role="group" aria-label="State Change">
                                                <a href="{{ route('change-user-state',['id' => $user->id]) }}" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="bottom" title="@if($user->state == \App\Models\User::ACTIVE) Disable @else Active @endif"><i class="fas fa-fw @if($user->state == \App\Models\User::ACTIVE) fa-lock-open @else fa-lock text-danger @endif"></i></a>
                                            </div>


                                            <div class="btn-group btn-group-sm mr-1" role="group" aria-label="View User">
                                                <button id="btnGroupDrop1" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-fw fa-eye"></i></button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a class="dropdown-item" href="{{ route('view-user-profile',['id' => $user->id]) }}">Profile</a>
                                                    <a class="dropdown-item" href="{{ route('view-user-locations',['id' => $user->id]) }}">Locations</a>
                                                    <a class="dropdown-item" href="{{ route('attendances',['user_id' => $user->id]) }}">Attendance</a>
                                                    <a class="dropdown-item" href="{{ route('payrolls',['user_id' => $user->id]) }}">Payroll</a>
                                                </div>
                                            </div>

                                            <div class="btn-group btn-group-sm mr-1" role="group" aria-label="Update User">
                                                <a href="{{ route('update-user',['id' => $user->id]) }}" class="btn btn-outline-warning" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="fas fa-pen fa-fw"></i></a>
                                                <a href="{{ route('update-user-password',['id' => $user->id]) }}" class="btn btn-outline-warning" data-toggle="tooltip" data-placement="bottom" title="Update Password"><i class="fas fa-key fa-fw"></i></a>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Delete User">
                                                <button onclick="deleteUser('{{ route('delete-user',['id' => $user->id]) }}')" class="btn btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-custom">
                        {{ $users->links() }}
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
                @else
                    @include('components.list-empty',['name' => 'Users'])
                @endif
            </div>
        </div>
    </div>
@endsection
