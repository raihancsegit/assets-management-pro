<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('meta-title', 'Assets Management Pro')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Asset Management Pro" name="description" />
        <meta content="sagor.touch@gmail.com" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/custom.css')}}" id="custom-style" rel="stylesheet" type="text/css" />
        @yield('styles')
    </head>

    <body>
        <div id="layout-wrapper">
            <x-dashboard-top-nav />

            <x-dashboard-menu />

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenujs/metismenujs.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/eva-icons/eva.min.js')}}"></script>

        <!-- <script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script> -->

        @yield('scripts')

        <script src="{{asset('assets/js/balance.js')}}"></script>
        <script src="{{asset('assets/js/app.js')}}"></script>

    </body>
</html>
