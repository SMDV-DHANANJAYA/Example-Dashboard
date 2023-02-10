@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Locations</li>
        </ol>
        <div class="d-flex justify-content-between">
            <a href="{{ route('add-location') }}" class="btn btn-sm btn-outline-primary mb-4"><i class="fa fa-map-pin"></i>&nbsp;&nbsp;Add New Location</a>
            @include('components.search')

        </div>

        <div class="row">
            <div class="col-12">
                @if($locations->isNotEmpty())
                    <div id="map" class="card mb-3" style="height: 500px;"></div>
                    <table class="table table-hover table-responsive-md table-responsive-sm">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                        @foreach ($locations as $location)
                            <tr>
                                <td>{{ $location->name }}</td>
                                <td>{{ $location->address }}</td>
                                <td>{{ $location->created_at->format('Y-m-d h:i A') }}</td>
                                <td>
                                    <div class="row">
                                        <a href="{{ route('change-location-state',['id' => $location->id]) }}" class="btn btn-sm btn-outline-secondary mr-1" data-toggle="tooltip" data-placement="bottom" title="@if($location->state == \App\Models\Location::ACTIVE) Disable @else Active @endif"><i class="fas fa-fw @if($location->state == \App\Models\Location::ACTIVE) fa-lock-open @else fa-lock text-danger @endif"></i></a>
                                        <a href="{{ route('view-location',['id' => $location->id]) }}" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fas fa-eye fa-fw"></i></a>
                                        <a href="{{ route('update-location',['id' => $location->id]) }}" class="btn btn-sm btn-outline-warning mr-1" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="fas fa-pen fa-fw"></i></a>
                                        <button onclick="deleteLocation('{{ route('delete-location',['id' => $location->id]) }}')" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash fa-fw"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-custom">
                        {{ $locations->links() }}
                    </div>
                    <script>
                        let locations = [
                            @forEach($locations as $location)
                                ["{{ $location->name }}","{{ $location->latitude }}","{{ $location->longitude }}",{{ $location->state }}],
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
                    </script>
                    @include('components.google-map-script')
                @else
                    @include('components.list-empty',['name' => 'Locations'])
                @endif
            </div>
        </div>
    </div>
@endsection
