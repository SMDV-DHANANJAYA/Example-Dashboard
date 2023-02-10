@extends('layouts.dashboard')

@section('content')
    @if($userLocations->isNotEmpty())
        @include('components.update-user-location-model')
    @endif
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
            <li class="breadcrumb-item active">User Locations</li>
        </ol>
        <div class="row">
            <div class="col-12">
                @if($userLocations->isNotEmpty())
                    <div id="map" class="card mb-3" style="height: 500px;"></div>
                    <table class="table table-hover table-responsive-md table-responsive-sm">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <td>Date</td>
                            <td>Start Time</td>
                            <td>End Time</td>
                            <td>Area (m)</td>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                        @foreach ($userLocations as $task)
                            <tr>
                                <td>{{ $task->location->name }}</td>
                                <td>
                                    @switch($task->type)
                                        @case(\App\Models\UserLocations::ONETIME)
                                            {{ $task->date }}
                                            @break
                                        @case(\App\Models\UserLocations::CUSTOMDAYS)
                                            {{ \App\Models\User::getWorkingDays($task->date) }}
                                            @break
                                        @case(\App\Models\UserLocations::EVERYDAY)
                                            <i class="fas fa-sync fa-fw"></i>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ date_format(new DateTime($task->start_time),'h:i A') }}</td>
                                <td>{{ date_format(new DateTime($task->end_time),'h:i A') }}</td>
                                <td>{{ $task->area }}</td>
                                <td>
                                    <div class="row">
                                        <button onclick="manageUserLocation({{ $task->user_id }},{{ $task->location_id }},{{ $task->id }},'{{ $task->date }}',{{ $task->type }},'{{ $task->start_time }}','{{ $task->end_time }}',{{ $task->area }})" class="btn btn-sm btn-outline-warning mr-1" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="fas fa-pen fa-fw"></i></button>
                                        <button onclick="deleteUserLocation('{{ route('delete-user-location',['id' => $task->id]) }}')" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash fa-fw"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-custom">
                        {{ $userLocations->links() }}
                    </div>
                    <script>
                        let locations = [
                                @forEach($userLocations as $task)
                                    ["{{ $task->location->name }}","{{ $task->location->latitude }}","{{ $task->location->longitude }}",{{ $task->location->state }}],
                                @endforeach
                        ];

                        function initMap() {

                            let map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 10,
                                center: { lat: -37.81411134670382, lng: 144.96459455605378 },
                                streetViewControl: false,
                            });

                            let infowindow = new google.maps.InfoWindow();

                            let i,marker;

                            for (i = 0; i < locations.length; i++) {
                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                    label: locations[i][0][0],
                                    draggable: false,
                                    animation: google.maps.Animation.DROP,
                                    icon: locations[i][3] === {{ \App\Models\Location::DEACTIVE }} ? 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png' : null,
                                    optimized: true,
                                    map: map
                                });

                                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                    return function() {
                                        infowindow.setContent(locations[i][0]);
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));
                            }
                        }

                        function deleteUserLocation(link){
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

                        @if($userLocations->isNotEmpty())
                            function manageUserLocation(user_id,location_id,id,day = null,type = null,start_time = null,end_time = null,area = null){
                                let day_value = day == "" ? "multi" : day;
                                changeWorkType(type,day_value);
                                $('#user-id').val(user_id);
                                $('#location-id').val(location_id);
                                $('#start-time').val(start_time.split(" ")[1]);
                                $('#end-time').val(end_time.split(" ")[1]);
                                $('#area').val(area);
                                $('#id').prop('required',true).val(id);
                                $('#form-text').text("Update User");
                                $('#manage-user-location-form').attr('action', '{{ route('update-user-location') }}');
                                $('#user-location-model').modal('show');
                            }

                            function changeWorkType(state,date = null){
                                switch (state){
                                    case {{ \App\Models\UserLocations::ONETIME }}:
                                        $('#custom-day').prop("checked", false);
                                        $('#every-day').prop("checked", false);
                                        $('#one-day').prop("checked", true);
                                        $('#dates').css('display','none').prop('required',false);
                                        $('#date').css('display','block').prop('required',true);
                                        if(date != null){
                                            $('#date').val(date);
                                            $('#dates').val('');
                                        }
                                        break;
                                    case {{ \App\Models\UserLocations::CUSTOMDAYS }}:
                                        $('#one-day').prop("checked", false);
                                        $('#every-day').prop("checked", false);
                                        $('#custom-day').prop("checked", true);
                                        $('#date').css('display','none').prop('required',false);
                                        $('#dates').css('display','block').prop('required',true);
                                        if(date != null){
                                            $('#dates').val(date.split('|'));
                                        }
                                        break;
                                    case {{ \App\Models\UserLocations::EVERYDAY }}:
                                        $('#custom-day').prop("checked", false);
                                        $('#one-day').prop("checked", false);
                                        $('#every-day').prop("checked", true);
                                        $('#dates').css('display','none').prop('required',false);
                                        $('#date').css('display','none').prop('required',false);
                                        if(date != null){
                                            $('#date').val('');
                                            $('#dates').val('');
                                        }
                                        break;
                                }
                            }
                        @endif
                    </script>
                    @include('components.google-map-script')
                @else
                    @include('components.list-empty',['name' => 'User Locations'])
                @endif
            </div>
        </div>
    </div>
@endsection
