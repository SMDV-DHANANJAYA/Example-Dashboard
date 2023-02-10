<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payroll</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            vertical-align: top;
        }

        h4 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
            margin-top: 0;
            display: block;
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0;
            margin-inline-end: 0;
        }

        .text-center {
            text-align: center;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            display: table;
            text-indent: initial;
            border-spacing: 2px;
        }

        .bg-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <table class="table-bordered text-center">
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
        <tbody>
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
</body>
</html>
