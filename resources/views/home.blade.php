@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="pt-3 pb-5 text-center">
            <span class="text-success display-4">{{ date('l, F d, Y') }}</span>
        </div>
        <div class="row justify-content-center">
            @include('components.status-card',[
                'title' => 'Admins',
                'count' => $admins,
                'icon' => 'fa-user-secret',
                'link' => route('admins')
            ])

            @include('components.status-card',[
                'title' => 'Users',
                'count' => $users,
                'icon' => 'fa-user',
                'link' => route('users')
            ])

            @include('components.status-card',[
                'title' => 'Locations',
                'count' => $locations,
                'icon' => 'fa-map',
                'link' => route('locations')
            ])
        </div>
    </div>
@endsection
