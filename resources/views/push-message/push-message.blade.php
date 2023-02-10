@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb mb-4 mt-4 shadow-sm">
            <li class="breadcrumb-item">Push Messages</li>
        </ol>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('push-message-send') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Select user <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <select class="form-control @error('user') is-invalid @enderror" name="user" required>
                                <option value=null disabled selected>Select User</option>
                                <option value=0>All Users</option>
                                @foreach($users as $user)
                                    <option value={{ $user->id }}>{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                            @error('user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Message Title <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" name="title" maxlength="50" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-12">
                            <label>Message Content <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="2" cols="50" maxlength="150" required>{{ old('body') }}</textarea>
                            @error('body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-md-2 col-sm-12">
                        </div>
                        <div class="col-md-10 col-sm-12">
                            @include('components.form-button',['name' => "Send"])
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
