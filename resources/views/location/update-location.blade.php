@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('locations') }}">Locations</a></li>
            <li class="breadcrumb-item active">Update Location</li>
        </ol>
        <div class="d-flex justify-content-center">
            <div class="pac-card col-6" id="pac-card" style="padding-top: 0.6rem !important;">
                <input class="form-control" id="pac-input" type="text" placeholder="Enter a location" style="width:100%;display: none">
            </div>
        </div>
        <div id="map" class="card mb-2" style="height: 500px;@if($errors->has('latitude') || $errors->has('longitude')) border: 1px solid red @endif"></div>
        @if($errors->has('latitude') || $errors->has('longitude'))
            <span class="invalid-feedback mb-3" role="alert" style="display: block !important;">
                <strong>Please select a place</strong>
            </span>
        @endif
        <div class="row">
            <div class="col-12 mt-3">
                <form action="{{ route('save-update-location') }}" method="POST" id="edit_location_form">
                    @csrf

                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" id="place_name" class="form-control @error('name') is-invalid @enderror" value="{{ $location->name }}" name="name" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Address <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" id="place_address" class="form-control @error('address') is-invalid @enderror" value="{{ $location->address }}" name="address" required>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>State <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <select class="custom-select @error('state') is-invalid @enderror" name="state">
                                <option disabled>Select State</option>
                                <option @if($location->state == \App\Models\Location::DEACTIVE) selected @endif value={{ \App\Models\Location::DEACTIVE }}>De Active</option>
                                <option @if($location->state == \App\Models\Location::ACTIVE) selected @endif value={{ \App\Models\Location::ACTIVE }}>Active</option>
                            </select>
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" class="form-control" value="{{ $location->id }}" name="id" required>
                    <input type="hidden" id="latitude" class="form-control" name="latitude" value="{{ $location->latitude }}">
                    <input type="hidden" id="longitude" class="form-control" name="longitude" value="{{ $location->longitude }}">
                    <div class="form-group row mt-4">
                        <div class="col-md-2 col-sm-12">
                        </div>
                        <div class="col-md-10 col-sm-12">
                            @include('components.form-button',['reset' => true])
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        let map;
        let isAddMarker = false;
        let marker;

        let location_marker = { lat: {{ $location->latitude }}, lng: {{ $location->longitude }} };

        function reset_form(){
            placeMarkerAndPanTo(location_marker.lat,location_marker.lng);
            document.getElementById("place_name").value = "{{ $location->name }}";
            document.getElementById("place_address").value = "{{ $location->address }}";
            document.getElementById("latitude").value = "{{ $location->latitude }}";
            document.getElementById("longitude").value = "{{ $location->longitude }}";
        }

        function initMap() {

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: location_marker,
                streetViewControl: false,
            });

            let card = document.getElementById('pac-card');

            let input = document.getElementById('pac-input');
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(card);
            let autocomplete = new google.maps.places.Autocomplete(input);

            placeMarkerAndPanTo(location_marker.lat,location_marker.lng);

            setTimeout(function (){
                input.style.display = "block";
            },1000);

            map.addListener("click", (e) => {
                let lat = e.latLng.lat();
                let lng = e.latLng.lng();
                placeMarkerAndPanTo(lat,lng);
            });

            autocomplete.addListener('place_changed',function() {
                let place = autocomplete.getPlace();
                let lat = place.geometry.location.lat();
                let lng = place.geometry.location.lng();
                placeMarkerAndPanTo(lat,lng);
                document.getElementById("place_name").value = input.value;
                document.getElementById("place_address").value = place.formatted_address;
                input.value = "";
            });
        }

        function placeMarkerAndPanTo(lat,lng) {
            if(isAddMarker){
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                draggable: false,
                animation: google.maps.Animation.DROP,
                optimized: true,
                map: map
            });

            document.getElementById("edit_location_form").reset();
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            map.panTo({ lat: lat, lng: lng});

            isAddMarker = true;
        }
    </script>
    @include('components.google-map-script')
@endsection
