@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Admins</li>
        </ol>
        <div class="d-flex justify-content-between">
            <a href="{{ route('add-admin') }}" class="btn btn-sm btn-outline-primary mb-4"><i class="fa fa-user-secret"></i>&nbsp;&nbsp;Add New Admin</a>
            @include('components.search')

        </div>

        <div class="row">
            <div class="col-12">
                @if(count($admins))
                    <table class="table table-hover table-responsive-md table-responsive-sm">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $admin->fullname }}</td>
                                    <td>{{ $admin->mobile }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->created_at->format('Y-m-d h:i A') }}</td>
                                    <td>
                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            @if(\Illuminate\Support\Facades\Auth::id() != $admin->id)
                                                <div class="btn-group btn-group-sm mr-1" role="group" aria-label="State Change">
                                                    <a href="{{ route('change-admin-state',['id' => $admin->id]) }}" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="bottom" title="@if($admin->state == \App\Models\User::ACTIVE) Disable @else Active @endif"><i class="fas fa-fw @if($admin->state == \App\Models\User::ACTIVE) fa-lock-open @else fa-lock text-danger @endif"></i></a>
                                                </div>
                                            @endif

                                            <div class="btn-group btn-group-sm mr-1" role="group" aria-label="Update Admin">
                                                <a href="{{ route('update-admin',['id' => $admin->id]) }}" class="btn btn-outline-warning" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="fas fa-pen fa-fw"></i></a>
                                                <a href="{{ route('update-admin-password',['id' => $admin->id]) }}" class="btn btn-outline-warning" data-toggle="tooltip" data-placement="bottom" title="Update Password"><i class="fas fa-key fa-fw"></i></a>
                                            </div>

                                            @if(\Illuminate\Support\Facades\Auth::id() != $admin->id)
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Delete Admin">
                                                    <button onclick="deleteAdmin('{{ route('delete-admin',['id' => $admin->id]) }}')" class="btn btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash fa-fw"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-custom">
                        {{ $admins->links() }}
                    </div>
                    <script>
                        function deleteAdmin(link){
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
                    @include('components.list-empty',['name' => 'Admins'])
                @endif
            </div>
        </div>
    </div>
@endsection
