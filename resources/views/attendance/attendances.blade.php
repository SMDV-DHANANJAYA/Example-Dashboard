@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Attendances</li>
        </ol>
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ url()->full() }}" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" data-placement="bottom" title="Refresh"><i class="fa fa-retweet"></i></a>
            <form action="{{ \Illuminate\Support\Facades\Route::currentRouteName() }}" method="GET" id="search-form">
                @csrf
                <div class="form-inline justify-content-end">
                    <select class="form-control-sm mr-2" name="user_id">
                        <option value=null disabled>Select User</option>
                        <option value=0 @if(request('user_id') == 0) selected @endif>All Users</option>
                        @foreach($users as $user)
                            <option value={{ $user->id }} @if(request('user_id') == $user->id) selected @endif>{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                    <select class="form-control-sm mr-2" name="location_name">
                        <option value=null disabled>Select Location</option>
                        <option value="all" @if(request('location_name') == "all") selected @endif>All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->name }}" @if(request('location_name') == $location->name) selected @endif>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-control-sm mr-2" name="month">
                        <option value=null disabled>Select Month</option>
                        <option value="all" @if(request('month') == "all") selected @endif>All Months</option>
                        <option value="{{ \Carbon\Carbon::now()->format('F') }}" @if(request('month') == \Carbon\Carbon::now()->format('F')) selected @endif>{{ \Carbon\Carbon::now()->format('F') }}</option>
                        <option value="{{ \Carbon\Carbon::now()->subMonth()->format('F') }}" @if(request('month') == \Carbon\Carbon::now()->subMonth()->format('F')) selected @endif>{{ \Carbon\Carbon::now()->subMonth()->format('F') }}</option>
                        <option value="{{ \Carbon\Carbon::now()->subMonths(2)->format('F') }}" @if(request('month') == \Carbon\Carbon::now()->subMonths(2)->format('F')) selected @endif>{{ \Carbon\Carbon::now()->subMonths(2)->format('F') }}</option>
                    </select>
                    <input type="submit" value="Search" class="btn btn-sm btn-outline-primary">
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                @if(count($attendances))
                    <table class="table table-hover table-responsive-md table-responsive-sm">
                        <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Location Name</th>
                            <th>Date</th>
                            <th>Assign Time</th>
                            <th>User Time</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->user->full_name }}</td>
                                <td>{{ $attendance->location_name }}</td>
                                <td>{{ explode(" ",$attendance->date)[0] }}</td>
                                <td>
                                    {{ $attendance->location_start_time->format('h:i A') }} <b>-</b> {{ $attendance->location_end_time->format('h:i A') }}
                                </td>
                                <td>
                                    {{ $attendance->user_start_time->format('h:i:s A') }} <b>-</b> @if($attendance->user_end_time != null ) {{ $attendance->user_end_time->format('h:i:s A') }} @else --:-- -- @endif
                                </td>
                                <td>
                                    @if($attendance->is_complete)
                                        <button class="btn btn-sm btn-success" disabled>Completed</button>
                                    @else
                                        <button class="btn btn-sm btn-danger" disabled>Not Completed</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-custom">
                        {{ $attendances->links() }}
                    </div>
                @else
                    @include('components.list-empty',['name' => 'Attendance'])
                @endif
            </div>
        </div>
    </div>
@endsection
