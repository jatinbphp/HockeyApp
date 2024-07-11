<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="_token" content="{!! csrf_token() !!}"/>
        <title>{{ config('app.name', 'Merchant Portal') }} | {{ $menu }}</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ URL('assets/plugins/fontawesome-free/css/all.min.css')}}" />
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ URL('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}" />
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ URL('assets/dist/css/adminlte.min.css')}}" />
        <link rel="stylesheet" href="{{ URL('assets/dist/css/custom.css')}}" />

        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/ladda-themeless.min.css">

    </head>
    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__wobble" src="{{ URL('assets/dist/img/AdminLTELogo.png')}}"  height="60" width="60" />
            </div>

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-dark">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{route('admin.dashboard')}}" class="nav-link">Dashborad</a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </a>
                    </li>
                </ul>
            </nav> 
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{route('admin.dashboard')}}" class="brand-link">
                    {{-- <img src="{{ URL('assets/dist/img/logo-black.png')}}" alt="{{ config('app.name', 'Hockey App') }}" class="brand-image" /> --}}
                    <span class="brand-text font-weight-light">Hockey App</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ URL('assets/dist/img/avatar.png')}}" class="img-circle elevation-2" alt="User Image" />
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">Super Admin</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="{{route('admin.dashboard')}}" class="nav-link @if(isset($menu) && $menu=='Dashboard') active @endif">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>

                            @php
                                $umenuCondition = isset($menu) && in_array($menu, ['Admin','Guardian']);
                                $umainMenuClasses = $umenuCondition ? 'menu-open' : '';
                                $usubMenuClasses = $umenuCondition ? 'active' : '';
                                $udisplayStyle = $umenuCondition ? 'block' : 'none';
                            @endphp

                            <li class="nav-item {{$umainMenuClasses}}">
                                <a href="javascript:void(0)" class="nav-link {{$usubMenuClasses}}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Manage Users
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{route('users.index')}}" class="nav-link @if(isset($menu) && $menu=='Admin') active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Admin</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{route('parent.index')}}" class="nav-link @if(isset($menu) && $menu=='Guardian') active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Guardian</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('category.index')}}" class="nav-link @if(isset($menu) && $menu=='Category') active @endif">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        Manage Category
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('province.index')}}" class="nav-link @if(isset($menu) && $menu=='Province') active @endif">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        Manage Province
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('school.index')}}" class="nav-link @if(isset($menu) && $menu=='School') active @endif">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>
                                        Manage School
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.logout') }}" class="nav-link">
                                    <i class="nav-icon fa fa-sign-out-alt"></i> <p class="">Log out</p>
                                </a>
                            </li>
                            
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            @yield('content')
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <strong>Copyright &copy; 2024-2025 <a href="javascript:void()">{{ config('app.name', 'Hockey App') }}</a>.</strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block"><b>Version</b> 3.2.0</div>
            </footer>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="{{ URL('assets/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{ URL('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{ URL('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL('assets/dist/js/adminlte.js')}}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ URL('assets/dist/js/demo.js')}}"></script>

        <!-- AdminLTE for datatables -->
        <script src="{{ URL('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{ URL('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ URL('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{ URL('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

        <script src="{{ URL('assets/dist/js/table-actions.js')}}?{{ time() }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/spin.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/ladda.min.js"></script>
        <script>
            $(document).ready(function() {
                Ladda.bind('button[type=submit]');
            });
        </script>
        
        <script>
            /*DISPLAY IMAGE*/
            function AjaxUploadImage(obj,id){
                var file = obj.files[0];
                var imagefile = file.type;
                var match = ["image/jpeg", "image/png", "image/jpg"];
                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
                {
                    $('#previewing'+URL).attr('src', 'noimage.png');
                    alert("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                    return false;
                } else{
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(obj.files[0]);
                }

                function imageIsLoaded(e){
                    $('#DisplayImage').css("display", "block");
                    $('#DisplayImage').css("margin-top", "1.5%");
                    $('#DisplayImage').attr('src', e.target.result);
                    $('#DisplayImage').attr('width', '150');
                }
            }
        </script>


        @yield('jquery')
    </body>
</html>