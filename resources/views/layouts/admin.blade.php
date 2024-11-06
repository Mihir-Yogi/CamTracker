<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="surfside media" />

    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}">
    <link rel="stylesheet" href="font/fonts.css">
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack("styles")
</head>



<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">

                <!-- <div id="preload" class="preload-container">
    <div class="preloading">
        <span></span>
    </div>
</div> -->

                <div class="section-menu-left">
                    <div class="box-logo">
                        <div class="icon"><i class="fa-solid fa-bus" style="margin-left: 15px;font-size: 50px;"></i><span style="font-size:30px;">CIMS</span></div>
                        <a href="{{route('admin.index')}}" id="site-logo-inner">
                            <img class="" id="logo_header" alt="" src="{{asset('images/logo/logo.png')}}"
                                data-light="{{asset('images/logo/logo.png')}}" data-dark="{{asset('images/logo/logo.png')}}">
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Main Home</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.index')}}" class="">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Dashboard</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                            <ul class="menu-list">

                            <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="fa-solid fa-plus"></i></div>
                                        <div class="text">CREATE</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.combos.create')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-plus-minus"></i></div>
                                                <div class="text">Add NVR/DVR/HDD</div>
                                            </a>
                                        </li>
                                        
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.cctvs.create') }}" class="">
                                            <div class="icon"><i class="fa-solid fa-plus-minus"></i></div>
                                                <div class="text">Add CCTV</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('status_reports.create')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-plus-minus"></i></div>
                                                <div class="text">Add Transaction</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>



                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="fa-solid fa-eye"></i></div>
                                        <div class="text">VIEW/UPDATE</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.combos.index')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                                <div class="text">View NVR/DVR/HDD</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.nvrs.index')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                                <div class="text">NVRs</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.dvrs.index')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                                <div class="text">DVRs</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.hdds.index')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                                <div class="text">HDDs</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.cctvs.index')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                                <div class="text">CCTVs</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('status_reports.index')}}" class="">
                                            <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                                <div class="text">Transactions</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a href="{{route('failed.devices')}}" class="">
                                        <div class="icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                                        <div class="text">Replacment History</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('admin.users')}}" class="">
                                        <div class="icon"><i class="fa-solid fa-user"></i></div>
                                        <div class="text">Users</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('admin.settings')}}" class="">
                                        <div class="icon"><i class="icon-settings"></i></div>
                                        <div class="text">Settings</div>
                                    </a>
                                </li>

                                <li class="menu-item">

                                <form method="POST" action="{{route('logout')}}" id="logout-form">
                                    @csrf
                                    <a href="{{route('logout')}}" class="" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <div class="icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                                        <div class="text">Log out</div>
                                    </a>
                                </form>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>
                <div class="section-content-right">

                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="index-2.html">
                                    <img class="" id="logo_header_mobile" alt="" src="{{asset('images/logo/logo.png')}}"
                                        data-light="{{asset('images/logo/logo.png')}}" data-dark="{{asset('images/logo/logo.png')}}"
                                        data-width="154px" data-height="52px" data-retina="{{asset('images/logo/logo.png')}}">
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>


                                <form class="form-search flex-grow">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Search here..." id="search-input" class="show-search" name="name"
                                            tabindex="2" value="" aria-required="true" required="" autocomplete="off">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button class="" type="submit"><i class="icon-search"></i></button>
                                    </div>
                                    <div class="box-content-search" >
                                        <ul id="box-contnt-search">

                                        </ul>
                                    </div>
                                </form>

                            </div>
                            <div class="header-grid">

                            <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                @php
                                                    use Illuminate\Support\Facades\Auth;
                                                    // Get the authenticated user
                                                    $user = Auth::user();
                                                @endphp

                                                <div class="flex flex-column">
                                                    <span class="body-title mb-2"><i class="fa-solid fa-user" style="margin-right: 15px;"></i>  {{ $user->name }} <i class="fa-solid fa-caret-down"></i></span>
                                                    <span class="text-tiny" style="margin-left: 35px;">{{ $user->utype === 'ADM' ? 'Admin' : 'User' }}</span>
                                                </div>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <form method="POST" action="{{route('logout')}}" id="logout-form">
                                                    @csrf
                                                    <a href="{{route('logout')}}" class="user-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                    <div class="icon">
                                                        <i class="icon-log-out"></i>
                                                    </div>
                                                    <div class="body-title-2">Log out</div>
                                                    </a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="main-content">

                    @yield('content')

                        <div class="bottom-page">
                            <div class="body-text">© 2024 CIMS</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>   
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
        @stack("scripts")
</body>
</html>