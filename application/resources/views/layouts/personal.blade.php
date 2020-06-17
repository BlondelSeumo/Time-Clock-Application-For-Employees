<!doctype html>
<!--
* Workday - A time clock application for employees
* Email: official.codefactor@gmail.com
* Version: 1.1
* Author: Brian Luna
* Copyright 2020 Codefactor
-->
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/images/img/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/images/img/favicon-32x32.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('/assets/images/img/favicon.ico') }}">
        
        @yield('meta')

        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/semantic-ui/semantic.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/DataTables/datatables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/vendor/flag-icon-css/css/flag-icon.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}">
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="{{ asset('/assets/vendor/html5shiv/html5shiv.min.js') }}></script>
            <script src="{{ asset('/assets/vendor/respond/respond.min.js') }}"></script>
        <![endif]-->

        @yield('styles')
    </head>
    <body>

        <div class="wrapper">
        
        <nav id="sidebar" class="active">
            <div class="sidebar-header bg-lightblue">
                <div class="logo">
                <a href="/" class="simple-text">
                    <img src="{{ asset('/assets/images/img/logo.png') }}">
                </a>
                </div>
            </div>

            <ul class="list-unstyled components">
                <li class="">
                    <a href="{{ url('personal/dashboard') }}">
                        <i class="ui icon sliders horizontal"></i>
                        <p>{{ __("Dashboard") }}</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('personal/attendance/view') }}">
                        <i class="ui icon clock outline"></i>
                        <p>{{ __("My Attendances") }}</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('personal/schedules/view') }}">
                        <i class="ui icon calendar alternate outline"></i>
                        <p>{{ __("My Schedules") }}</p>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('personal/leaves/view') }}">
                        <i class="ui icon calendar plus outline"></i>
                        <p>{{ __("My Leave") }}</p>
                    </a>
                </li>
                <li>
                    <a href="{{ url('personal/settings') }}">
                        <i class="ui icon cog"></i>
                        <p>{{ __("Settings") }}</p>
                    </a>
                </li>
            </ul>
        </nav>

        <div id="body" class="active">
            <nav class="navbar navbar-expand-lg navbar-light bg-lightblue">
                <div class="container-fluid">

                    <button type="button" id="slidesidebar" class="ui icon button btn-light-outline">
                        <i class="ui icon bars"></i> <span class="toggle-sidebar-menu">{{ __('Menu') }}</span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto navmenu">
                            <li class="nav-item">
                                <div class="ui pointing link dropdown item" tabindex="0">
                                    <i class="ui icon flag"></i> <span class="navmenutext uppercase">{{ env('APP_LOCALE', 'en') }}</span>
                                    <i class="dropdown icon"></i>
                                    <div class="menu" tabindex="-1">
                                      <a href="{{ url('lang/en') }}" class="item"><i class="flag-icon flag-icon-us"></i>English</a>
                                      <a href="{{ url('lang/es') }}" class="item"><i class="flag-icon flag-icon-es"></i>Español</a>
                                      <a href="{{ url('lang/fr') }}" class="item"><i class="flag-icon flag-icon-fr"></i>Français</a>
                                      <a href="{{ url('lang/de') }}" class="item"><i class="flag-icon flag-icon-de"></i>Deutsch</a>
                                      <a href="{{ url('lang/jp') }}" class="item"><i class="flag-icon flag-icon-jp"></i>日本語</a>
                                      <a href="{{ url('lang/in') }}" class="item"><i class="flag-icon flag-icon-in"></i>Hindi</a>
                                      <a href="{{ url('lang/it') }}" class="item"><i class="flag-icon flag-icon-it"></i>Italian</a>
                                      <a href="{{ url('lang/kr') }}" class="item"><i class="flag-icon flag-icon-kr"></i>한국말</a>
                                      <a href="{{ url('lang/my') }}" class="item"><i class="flag-icon flag-icon-my"></i>Malay</a>
                                      <a href="{{ url('lang/nl') }}" class="item"><i class="flag-icon flag-icon-nl"></i>Dutch</a>
                                      <a href="{{ url('lang/ph') }}" class="item"><i class="flag-icon flag-icon-ph"></i>Filipino</a>
                                      <a href="{{ url('lang/pt') }}" class="item"><i class="flag-icon flag-icon-pt"></i>Português</a>
                                    </div>
                              </div>
                            </li>
                            <li class="nav-item">
                                <div class="ui pointing link dropdown item" tabindex="0">
                                    <i class="ui icon linkify"></i> <span class="navmenutext uppercase">{{ __("Quick Access") }}</span>
                                    <i class="dropdown icon"></i>
                                    <div class="menu" tabindex="-1">
                                      <a href="{{ url('clock') }}" target="_blank" class="item"><i class="ui icon clock outline"></i> {{ __("Clock In/Out") }}</a>
                                      <div class="divider"></div>
                                      <a href="{{ url('personal/profile/view') }}" target="_blank" class="item"><i class="ui icon user outline"></i> {{ __("My Profile") }}</a>
                                    </div>
                              </div>
                            </li>
                            <li class="nav-item">
                               <div class="ui pointing link dropdown item" tabindex="0">
                                    <i class="ui icon user outline"></i><span class="navmenutext">@isset(Auth::user()->name) {{ Auth::user()->name }} @endisset</span>
                                    <i class="dropdown icon"></i>
                                    <div class="menu" tabindex="-1">
                                      <a href="{{ url('personal/update-user') }}" class="item"><i class="ui icon user"></i> {{ __("Update User") }}</a>
                                      <a href="{{ url('personal/update-password') }}" class="item"><i class="ui icon lock"></i> {{ __("Change Password") }}</a>
                                      <div class="divider"></div>
                                      <a href="{{ url('logout') }}" class="item"><i class="ui icon power"></i> {{ __("Logout") }}</a>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content">
                @yield('content')
            </div>

            <input type="hidden" id="_url" value="{{url('/')}}">
            <script>
                var y = '@isset($var){{$var}}@endisset';
            </script>
        </div>
    </div>

    <script src="{{ asset('/assets/vendor/jquery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/semantic-ui/semantic.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/script.js') }}"></script>
    @if ($success = Session::get('success'))
    <script>
        $(document).ready(function() {
            $.notify({icon: 'ti-check',message: "{{ $success }}"},{type: 'success',timer: 600});
        });
    </script>
    @endif

    @if ($error = Session::get('error'))
    <script>
        $(document).ready(function() {
            $.notify({icon: 'ti-close',message: "{{ $error }}"},{type: 'danger',timer: 600});
        });
    </script>
    @endif

    @yield('scripts')

    </body>
</html>