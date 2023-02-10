@extends('layouts.dashboard')

@section('content')
    @if(count($current_users) || count($other_users))
        @include('components.update-user-location-model')
    @endif
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('locations') }}">Locations</a></li>
            <li class="breadcrumb-item active">View Location</li>
        </ol>
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>ERROR !!! </strong> {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div id="map" class="card mb-2" style="height: 500px;"></div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="place_name" class="form-control" value="{{ $location->name }}" name="name" disabled>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" id="place_address" class="form-control" value="{{ $location->address }}" name="address" disabled>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" class="form-control" value="@if($location->state == \App\Models\Location::DEACTIVE) De Active @else Active @endif" disabled>
                </div>
                <a href="{{ route('update-location',['id' => $location->id]) }}" class="btn btn-sm btn-outline-success mr-1">Update Location</a>
                <button onclick="deleteLocation('{{ route('delete-location',['id' => $location->id]) }}')" class="btn btn-sm btn-outline-danger">Delete Location</button>
            </div>
        </div>
        <br>
        @if(count($current_users))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <span class="h5">Current Users</span>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-responsive-sm table-responsive-md">
                                <tr>
                                    <td>Name</td>
                                    <td>Date</td>
                                    <td>Start Time</td>
                                    <td>End Time</td>
                                    <td>Area (m)</td>
                                    <td>Actions</td>
                                </tr>
                                @foreach($current_users as $current_user)
                                    <tr>
                                        <td>{{ $current_user->user->full_name }}</td>
                                        <td>
                                            @switch($current_user->type)
                                                @case(\App\Models\UserLocations::ONETIME)
                                                    {{ $current_user->date }}
                                                    @break
                                                @case(\App\Models\UserLocations::CUSTOMDAYS)
                                                    {{ \App\Models\User::getWorkingDays($current_user->date) }}
                                                    @break
                                                @case(\App\Models\UserLocations::EVERYDAY)
                                                    <i class="fas fa-sync fa-fw"></i>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ date_format(new DateTime($current_user->start_time),'h:i A') }}</td>
                                        <td>{{ date_format(new DateTime($current_user->end_time),'h:i A') }}</td>
                                        <td>{{ $current_user->area }}</td>
                                        <td>
                                            <div class="row">
                                                <button onclick="manageUserLocation('update',{{ $current_user->user_id }},{{ $location->id }},{{ $current_user->id }},'{{ $current_user->date }}',{{ $current_user->type }},'{{ $current_user->start_time }}','{{ $current_user->end_time }}',{{ $current_user->area }})" class="btn btn-sm btn-outline-warning mr-1" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="fas fa-pen fa-fw"></i></button>
                                                <button onclick="deleteUserLocation('{{ route('delete-user-location',['id' => $current_user->id]) }}')" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash fa-fw"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="pagination-custom">
                                {{ $current_users->links() }}
                            </div>
                        </div>
                    </div>
                    <script>
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
                    </script>
                </div>
            </div>
        @else
            @include('components.list-empty',['name' => 'Current Users'])
        @endif
        @if(count($other_users))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <span class="h5">Assign New Users</span>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-responsive-sm table-responsive-md">
                                <tr>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Mobile</td>
                                    <td>Actions</td>
                                </tr>
                                @foreach($other_users as $other_user)
                                    <tr>
                                        <td>{{ $other_user->first_name }} {{ $other_user->last_name }}</td>
                                        <td>{{ $other_user->email }}</td>
                                        <td>{{ $other_user->mobile }}</td>
                                        <td>
                                            <button onclick="manageUserLocation('add',{{ $other_user->id }},{{ $location->id }})" class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="bottom" title="Add"><i class="fas fa-plus fa-fw"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="pagination-custom">
                                {{ $other_users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include('components.list-empty',['name' => 'Other Users'])
        @endif
    </div>
    <script>
        let location_marker = { lat: {{ $location->latitude }}, lng: {{ $location->longitude }} };

        function initMap() {

            let map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: location_marker,
                streetViewControl: false,
            });

            let infowindow = new google.maps.InfoWindow();

            let marker = new google.maps.Marker({
                position: new google.maps.LatLng(location_marker.lat, location_marker.lng),
                label: "{{ $location->name }}"[0],
                draggable: false,
                animation: google.maps.Animation.DROP,
                icon: "{{ $location->state }}" === '0' ? 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png' : null,
                optimized: true,
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent("{{ $location->name }}");
                    infowindow.open(map, marker);
                }
            })(marker));
        }

        function deleteLocation(link){
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

        @if(count($current_users) || count($other_users))
            function manageUserLocation(state,user_id,location_id,id,day = null,type = null,start_time = null,end_time = null,area = null){
                if(state == "add"){
                    document.getElementById("manage-user-location-form").reset();
                    $('#dates').css('display','none').prop('required',false);
                    $('#date').css('display','block').prop('required',true);
                    $('#user-id').val(user_id);
                    $('#location-id').val(location_id);
                    $('#id').prop('required',false).val('');
                    $('#form-text').text("New User");
                    $('#manage-user-location-form').attr('action', '{{ route('add-user-location') }}');
                }
                else if (state == "update"){
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
                }
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
@endsection
