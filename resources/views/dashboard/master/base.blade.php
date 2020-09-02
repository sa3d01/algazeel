<html>
<head>
    <title>{{config('app.name')}} - @yield('title','لوحة التحكم')</title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="template language" name="keywords">
    <meta content="http://sa3d01.com" name="author">
    <meta content="Admin dashboard html template" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="{{asset('panel/favicon.png')}}" rel="shortcut icon">
    <link href="{{asset('panel/apple-touch-icon.png')}}" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet" type="text/css">
    <link href="{{asset('panel/bower_components/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('panel/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('panel/bower_components/dropzone/dist/dropzone.css')}}" rel="stylesheet">
    <link href="{{asset('panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('panel/bower_components/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet">
    <link href="{{asset('panel/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css')}}" rel="stylesheet">
    <link href="{{asset('panel/bower_components/slick-carousel/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('panel/css/main.css?version=4.4.0')}}" rel="stylesheet">
    @yield('style')
</head>

<body class="menu-position-side menu-side-left full-screen with-content-panel">
    <div class="all-wrapper with-side-panel solid-bg-all">
        <div class="layout-w">
            <div class="menu-mobile menu-activated-on-click color-scheme-dark">
                <div class="mm-logo-buttons-w">
                    <a class="mm-logo" href="{{route('admin.home')}}"><img src="{{asset('panel/img/logo.png')}}"><span>لوحة التحكم</span></a>
                    <div class="mm-buttons">
                        <div class="content-panel-open">
                            <div class="os-icon os-icon-grid-circles"></div>
                        </div>
                        <div class="mobile-menu-trigger">
                            <div class="os-icon os-icon-hamburger-menu-1"></div>
                        </div>
                    </div>
                </div>
                <div class="menu-and-user">
                    <div class="logged-user-w">
                        <div class="avatar-w">
                            <img alt="" src="{{Auth::user()->image}}">
                        </div>
                        <div class="logged-user-info-w">
                            <div class="logged-user-name">
                                {{Auth::user()->name}}
                            </div>
                            <div class="logged-user-role">
                                Administrator
                            </div>
                        </div>
                    </div>
                   @include('dashboard.master.mobile-main-menu')
                </div>
            </div>
            <!--------------------
            END - Mobile Menu
            --------------------><!--------------------
            START - Main Menu
            -------------------->
            <div class="menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-compact sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link">
                <div class="logo-w">
                    <a class="logo" href="{{route('admin.home')}}">
                        <div class="logo-element"></div>
                        <div class="logo-label">
                            لوحة التحكم
                        </div>
                    </a>
                </div>
                <div class="logged-user-w avatar-inline">
                    <div class="logged-user-i">
                        <div class="avatar-w">
                            <img alt="" src="{{Auth::user()->image}}">
                        </div>
                        <div class="logged-user-info-w">
                            <div class="logged-user-name">
                                {{auth()->user()->name}}
                            </div>
                            <div class="logged-user-role">
                                Administrator
                            </div>
                        </div>
                        <div class="logged-user-toggler-arrow">
                            <div class="os-icon os-icon-chevron-down"></div>
                        </div>
                        <div class="logged-user-menu color-style-bright">
                            <div class="logged-user-avatar-info">
                                <div class="avatar-w">
                                    <img alt="" src="{{Auth::user()->image}}">
                                </div>
                                <div class="logged-user-info-w">
                                    <div class="logged-user-name">
                                        {{auth()->user()->name}}
                                    </div>
                                    <div class="logged-user-role">
                                        Administrator
                                    </div>
                                </div>
                            </div>
                            <div class="bg-icon">
                                <i class="os-icon os-icon-wallet-loaded"></i>
                            </div>
                            <ul>
                                <li>
                                    <a href="{{route('admin.profile',Auth::user()->id)}}"><i class="os-icon os-icon-user-male-circle2"></i><span>الملف الشخصى</span></a>
                                </li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <a href="javascript:;" onclick="parentNode.submit();">
                                            <i type="submit" class="os-icon os-icon-signs-11"></i><span>تسجيل خروج</span>
                                        </a>
                                    </form>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                @include('dashboard.master.main-menu')
            </div>
            <div class="content-w">
                @if(isset($title))
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.home')}}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            {{$title}}
                        </li>
                    </ul>
                @endif
                @yield('content')
            </div>
        </div>
        @include('dashboard.master.custom-buttons')
        <div class="display-type"></div>
    </div>
    {{--script--}}
    <script src="{{asset('panel/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/notify/notify.js')}}"></script>
    <script src="{{asset('panel/bower_components/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/moment/moment.js')}}"></script>
    <script src="{{asset('panel/bower_components/chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/jquery-bar-rating/dist/jquery.barrating.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap-validator/dist/validator.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('panel/bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/dropzone/dist/dropzone.js')}}"></script>
    <script src="{{asset('panel/bower_components/editable-table/mindmup-editabletable.js')}}"></script>
    <script src="{{asset('panel/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/tether/dist/js/tether.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/slick-carousel/slick/slick.min.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/util.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/alert.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/button.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/carousel.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/collapse.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/dropdown.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/modal.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/tab.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/tooltip.js')}}"></script>
    <script src="{{asset('panel/bower_components/bootstrap/js/dist/popover.js')}}"></script>
    <script src="{{asset('panel/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('panel/js/demo_customizer.js?version=4.4.0')}}"></script>
    <script src="{{asset('panel/js/main.js?version=4.4.0')}}"></script>
    @include('dashboard.master.alerts')
{{--    <script>--}}
{{--        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){--}}
{{--            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),--}}
{{--            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)--}}
{{--        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');--}}

{{--        ga('create', 'UA-XXXXXXX-9', 'auto');--}}
{{--        ga('send', 'pageview');--}}
{{--    </script>--}}
    @yield('script')
</body>
</html>
