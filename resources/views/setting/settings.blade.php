@extends('layouts.dashboard')

@section('content')
    <div class="modal fade card" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Odel FS - Notification Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-danger">&times;</span>
                    </button>
                    <br>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('images/qr_img.png') }}" alt="QR Code" class="w-100 h-100 img-fluid">
                </div>
                <div class="modal-footer" style="align-items: center;justify-content: center">
                    <span>Scan this qr code to join telegram group (Admin only)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item active">Settings</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <table class="table table-borderless">
                    <form action="{{ route('setting') }}" method="POST">
                        <thead>
                            <tr>
                                <td style="border-bottom: 1px solid black">Options</td>
                                <td style="border-bottom: 1px solid black">Notification State (On/Off)</td>
                                <td style="border-bottom: 1px solid black">Push Notifications (On/Off)</td>
                                <td style="border-bottom: 1px solid black">
                                    Telegram Notifications (On/Off)
                                    <br>
                                    - Admin only&nbsp;&nbsp;<span data-toggle="tooltip" title="{{ 'Scan this qr code to join ' . strtolower(env('app_name')) . ' telegram notification group' }}"><i class="fas fa-qrcode text-primary fa-lg" style="cursor:pointer;" type="button" data-toggle="modal" data-target="#qrModal"></i></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="pb-2"></td>
                            </tr>
                        </thead>
                        @csrf
                        @foreach($settings as $setting)
                            @if($setting->name == \App\Models\Setting::ANDROID_VERSION || $setting->name == \App\Models\Setting::IOS_VERSION)
                                <tr>
                                    <td>Mobile {{ ucwords(strtolower(str_replace("_"," ",$setting->name))) }}  <span class="text-danger">*</span></td>
                                    <td>
                                        <input type="text" name="{{ $setting->name }}" class="form-control-sm @error($setting->name) is-invalid @enderror" value="{{ $setting->value }}" required>
                                        @error($setting->name)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </td>
                                    <td colspan="2"></td>
                                    <td>
                                        <i class="fa fa-info-circle" data-toggle="tooltip" title="{{ $setting->description }}"></i>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        {{ ucwords(strtolower(str_replace("_"," ",$setting->name))) }}
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" name="{{ $setting->name }}" id="{{ $setting->name }}" @if($setting->value) checked @endif onchange="manageInputStatesChild('{{ $setting->name }}')">
                                            <span class="slider round"></span>
                                        </label>
                                        @switch($setting->name)
                                            @case(\App\Models\Setting::CLEAR_ATTENDANCES_AUTO)
                                                <span class="ml-2 text-danger" style="cursor: pointer" onclick="deleteAttendanceConfirm('{{ route('delete-attendance') }}')">Delete Manually</span>
                                                @break
                                            @case(\App\Models\Setting::CLEAR_NOTIFICATIONS_AUTO)
                                                <span class="ml-2 text-danger" style="cursor: pointer" onclick="deleteNotificationsConfirm('{{ route('delete-notifications') }}')">Delete Manually</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($setting->push_notification_state != "")
                                            <label class="switch">
                                                <input type="checkbox" name="{{ $setting->name.'_PUSH' }}" id="{{ $setting->name.'_PUSH' }}" @if($setting->push_notification_state) checked @endif @if(!\Illuminate\Support\Str::contains($setting->name,"_AUTO")) onchange="manageParentAndChildInputStates('#{{ $setting->name }}_PUSH','#{{ $setting->name }}_TELEGRAM','#{{ $setting->name }}')" @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        @endif
                                    </td>
                                    <td>
                                        @if($setting->telegram_notification_state != "")
                                            <label class="switch">
                                                <input type="checkbox" name="{{ $setting->name.'_TELEGRAM' }}" id="{{ $setting->name.'_TELEGRAM' }}" @if($setting->telegram_notification_state) checked @endif @if(!\Illuminate\Support\Str::contains($setting->name,"_AUTO")) onchange="manageParentAndChildInputStates('#{{ $setting->name }}_TELEGRAM','#{{ $setting->name }}_PUSH','#{{ $setting->name }}')" @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa fa-info-circle" data-toggle="tooltip" title="{{ $setting->description }}"></i>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tfoot class="mt-5">
                            <tr>
                                <td></td>
                                <td>@include('components.form-button')</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </form>
                </table>
            </div>
        </div>
    </div>
    <script>
        function deleteAttendanceConfirm(link){
            Notiflix.Confirm.show(
                'Confirm Delete',
                'Are you sure? (This option delete last quarter attendances)',
                'Yes',
                'No',
                function okCb() {
                    window.location = link;
                },
            );
        }

        function deleteNotificationsConfirm(link){
            Notiflix.Confirm.show(
                'Confirm Delete',
                'Are you sure?  (This option delete last day notifications)',
                'Yes',
                'No',
                function okCb() {
                    window.location = link;
                },
            );
        }

        function manageInputStatesChild(value){
            let push = '#'+value+'_PUSH';
            let telegram = '#'+value+'_TELEGRAM';
            if($('#'+value).is(":checked")){
                if($(push).length){
                    $(push).prop("checked", true);
                }

                if($(telegram).length){
                    $(telegram).prop("checked", true);
                }
            }
            else{
                if($(push).length){
                    $(push).prop("checked", false);
                }

                if($(telegram).length){
                    $(telegram).prop("checked", false);
                }
            }
        }

        function manageParentAndChildInputStates(clickOne,otherOne,main){
            if($(clickOne).is(":checked")){
                if(!$(main).is(":checked")){
                    $(main).prop("checked", true);
                }
            }
            else{
                if($(otherOne).length){
                    if(!$(otherOne).is(":checked")){
                        $(main).prop("checked", false);
                    }
                }
                else{
                    $(main).prop("checked", false);
                }
            }
        }
    </script>
@endsection
