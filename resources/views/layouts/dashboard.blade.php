<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="author" content="donvishwa.vd@gmail.com" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

        <!-- Header Style -->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
        <!-- Header Script -->
        <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/all.min.js') }}"></script>
        <script src="{{ asset('js/script.js') }}" defer></script>
        <script src="{{ asset('js/notiflix-aio-3.2.5.min.js') }}"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark shadow">
            <a class="navbar-brand" href="{{ route('home') }}">{{ strtoupper(config('app.name', 'Laravel')) }}</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <ul class="navbar-nav ml-auto mr-3">
                <li class="nav-item" style="position: relative">
                    <div class="nav-link">
                        <i class="fas fa-bell fa-fw text-white bell hand"></i>
                        <span class="badge bell hand" style="position: absolute;top: -1px;right: -5px;background-color: red;color: white;display: none" id="notification-count"></span>
                    </div>
                    <div class="notifications" id="box">
                        <h2>Notifications</h2>
                        <div id="notification-list"></div>
                        <div class="text-center p-2">
                            <a href="{{ route('notifications-all') }}" class="text-danger small" style="text-decoration: none">View all</a>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <div class="dropdown show dropleft">
                        <span class="nav-link dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-power-off fa-fw text-danger"></i></span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <span class="dropdown-item" style="cursor: pointer" onclick="document.getElementById('logout-form').submit()">Logout</span>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            @include('layouts.component.sidenav')
            <div id="layoutSidenav_content">
                <main>
                    @yield('content')
                </main>
                <br><br>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="text-center small">
                            <div class="text-muted">{{ strtoupper(config('app.name', 'Laravel')) }} &copy {{ date('Y') }} - (<span class="text-danger">{{ \Illuminate\Support\Facades\Auth::user()->full_name }}</span>)</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </body>
    <script>
        @if(session()->has('state'))
            @if(session()->get('state'))
                Notiflix.Notify.success("{{ session()->get('message') }}");
            @else
                Notiflix.Notify.failure("{{ session()->get('message') }}");
            @endif
        @endif

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

            let down = false;

            $('.bell').click(function(e){
                if(down){
                    $('#box').css('display','none');
                    down = false;
                }
                else{
                    $('#box').css('display','block');
                    down = true;
                }
            });

            setInterval(function (){
                $.get("{{ route('notifications-short') }}", function(data, status){
                    if(status == "success"){
                        if(data.count > 0){
                            $('#notification-count').text(data.count);
                            $('#notification-count').css('display','block');
                        }
                        else{
                            $('#notification-count').css('display','none');
                        }

                        if(data.notifications.length > 0){
                            $('#notification-list').empty();
                            for(let i = 0; i < data.notifications.length; i++){
                                $('#notification-list').append('<div class="notifications-item"><div class="text"><h6>'+ data.notifications[i].created_at +'</h6><p>'+ data.notifications[i].text +'</p></div></div>');
                            }
                        }
                    }
                });
            },10000);
        });
    </script>
</html>
