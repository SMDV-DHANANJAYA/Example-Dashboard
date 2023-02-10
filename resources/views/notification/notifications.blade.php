@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Notifications</li>
        </ol>
        <div class="mb-4">
            <a href="{{ url()->full() }}" class="btn btn-sm btn-outline-primary mr-1" data-toggle="tooltip" data-placement="bottom" title="Refresh"><i class="fa fa-retweet"></i></a>
            @if(count($notifications))
                <button onclick="deleteNotificationsConfirm('{{ route('delete-all-notifications') }}')" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete All"><i class="fas fa-trash fa-fw"></i></button>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                @if(count($notifications))
                    @foreach($notifications as $notification)
                        <div class="mb-2 p-3 rounded"
                         @switch($notification->state)
                            @case(\App\Models\Notification::DANGER)
                                style="background-color: #E9ECEF;border: 1px solid red"
                                @break
                            @case(\App\Models\Notification::WARNING)
                                style="background-color: #E9ECEF;border: 1px solid orange"
                                @break
                            @default
                                style="background-color: #E9ECEF;"
                                @break
                        @endswitch>
                            <div>
                                {{ $notification->created_at->format('Y-m-d h:i A') }}
                            </div>
                            <hr>
                            <div>
                                {{ $notification->text }}
                            </div>
                        </div>
                    @endforeach
                    <div class="pagination-custom">
                        {{ $notifications->links() }}
                    </div>
                    <script>
                        function deleteNotificationsConfirm(link){
                            Notiflix.Confirm.show(
                                'Confirm Delete All',
                                'Are you sure? (This option delete all the notifications!! Use settings to delete daily)',
                                'Yes',
                                'No',
                                function okCb() {
                                    window.location = link;
                                },
                            );
                        }
                    </script>
                @else
                    @include('components.list-empty',['name' => 'Notifications'])
                @endif
            </div>
        </div>
    </div>
@endsection
