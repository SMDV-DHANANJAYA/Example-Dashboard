@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Payroll</li>
        </ol>
        <div class="text-right">
            <div class="d-flex justify-content-end mb-4">
                <form action="{{ \Illuminate\Support\Facades\Route::currentRouteName() }}" method="GET" id="search-form">
                    @csrf
                    <div class="form-inline justify-content-center">
                        <select class="form-control-sm mr-2" name="user_id">
                            <option value=null disabled selected>Select User</option>
                            @foreach($users as $user)
                                <option value={{ $user->id }} @if(request('user_id') == $user->id) selected @endif>{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                        <select class="form-control-sm mr-2" name="month">
                            <option value="{{ \Carbon\Carbon::now()->format('F') }}">{{ \Carbon\Carbon::now()->format('F') }}</option>
                            <option value="{{ \Carbon\Carbon::now()->subMonth()->format('F') }}" @if(request('month') == \Carbon\Carbon::now()->subMonth()->format('F')) selected @endif>{{ \Carbon\Carbon::now()->subMonth()->format('F') }}</option>
                            <option value="{{ \Carbon\Carbon::now()->subMonths(2)->format('F') }}" @if(request('month') == \Carbon\Carbon::now()->subMonths(2)->format('F')) selected @endif>{{ \Carbon\Carbon::now()->subMonths(2)->format('F') }}</option>
                        </select>
                        <input type="submit" value="Search" class="btn btn-sm btn-outline-primary">
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($attendances != null)
                    <table class="table table-hover table-bordered table-responsive-md table-responsive-sm text-center">
                        <thead>
                            <tr>
                                <td colspan="8">
                                    <h4>{{ $attendances[0]->user->full_name }}'s Payroll</h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th colspan="3"><b>Location Time</b></th>
                                <th colspan="3"><b>User Time</b></th>
                            </tr>
                            <tr>
                                <th><b>Date</b></th>
                                <th><b>Location Name</b></th>
                                <th><b>Start Time</b></th>
                                <th><b>End Time</b></th>
                                <th><b>Assign Hours</b></th>
                                <th><b>Start Time</b></th>
                                <th><b>End Time</b></th>
                                <th><b>Working Time</b></th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date }}</td>
                                    <td>{{ $attendance->location_name }}</td>
                                    <td>{{ $attendance->location_start_time->format('h:i A') }}</td>
                                    <td>{{ $attendance->location_end_time->format('h:i A') }}</td>
                                    <td>{{ $attendance->location_time_count }}</td>
                                    <td>{{ $attendance->user_start_time->format('h:i:s A') }}</td>
                                    @if($attendance->is_complete)
                                        <td>{{ $attendance->user_end_time->format('h:i:s A') }}</td>
                                    @else
                                        <td>--:-- --</td>
                                    @endif
                                    @if($attendance->is_complete)
                                        <td>{{ $attendance->user_time_count }}</td>
                                    @else
                                        <td class="bg-danger">--:-- --</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><b>Total hours</b></td>
                                <td colspan="2"><b>Assign Hours Count</b></td>
                                <td><b>{{ $assignFullTimeCount }}</b></td>
                                <td colspan="2"><b>Working Hours Count</b></td>
                                <td><b>{{ $workingFullTimeCount }}</b></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="btn-group btn-group-sm mt-3" role="group" aria-label="Download Attendance">
                        <button id="btnGroupDrop1" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Download</button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ route('download-payroll-pdf',['user_id' => request('user_id'),'month' => request('month')]) }}">PDF</a>
                            <a class="dropdown-item" href="{{ route('download-payroll-excel',['user_id' => request('user_id'),'month' => request('month')]) }}">EXCEL</a>
                        </div>
                    </div>
                @else
                    @include('components.list-empty',['name' => 'Payroll'])
                @endif
            </div>
        </div>
    </div>
@endsection
