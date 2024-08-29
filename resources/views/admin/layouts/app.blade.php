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
        <!-- <link rel="stylesheet" href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}" />
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/adminlte.min.css')}}?{{ time() }}" />
        <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/custom.css')}}" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.3/themes/base/jquery-ui.min.css" />

        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/ladda-themeless.min.css">
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datepicker/bootstrap-datepicker.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/summernote/summernote.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/css/select2.min.css')}}">

        
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.css"> -->


    </head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__wobble" src="{{ URL::asset('assets/dist/img/AdminLTELogo.png')}}"  height="60" width="60" />
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
                    {{-- <img src="{{ URL::asset('assets/dist/img/logo-black.png')}}" alt="{{ config('app.name', 'Hockey App') }}" class="brand-image" /> --}}
                    <span class="brand-text font-weight-light">Hockey App</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ URL::asset('assets/dist/img/avatar.png')}}" class="img-circle elevation-2" alt="User Image" />
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">Super Admin</a>
                        </div>
                    </div> -->
                

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item has-treeview {{ isset($menu) && $menu == 'Edit Profile' ? 'menu-open' : '' }}" style="border-bottom: 1px solid #4f5962; margin-bottom: 4.5%;">

                            <a href="#" class="nav-link {{ isset($menu) && $menu == 'Edit Profile' ? 'active' : '' }}">
                                <img src="{{ !empty(Auth::user()->image) && file_exists(Auth::user()->image) ? asset(Auth::user()->image) : url('assets/admin/dist/img/no-image.png') }}" 
                                    class="img-circle elevation-2" 
                                    
                                    style="width: 2.1rem; margin-right: 1.5%;">

                                <p style="padding-right: 6.5%;">
                                @php
                                    $userName = 'Guest'; // Default to 'Guest' if the user is not logged in

                                    // Check if user_id exists in the session and fetch the user's name
                                    if (session()->has('user_id')) {
                                        $user = \App\Models\User::find(session('user_id'));
                                        if ($user) {
                                            $userName = ucfirst($user->firstname).' '.ucfirst($user->lastname);
                                        }
                                    }
                                @endphp
                                {{ $userName }}

                                <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('profile_update.edit', ['profile_update' => session('user_id')]) }}"
                                class="nav-link {{ isset($menu) && $menu == 'Edit Profile' ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-pencil-alt"></i>
                                        <p class="text-warning">Edit Profile</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

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
                                    <i class="nav-icon fa fa-sitemap"></i>
                                    <p>
                                        Manage Category
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('province.index')}}" class="nav-link @if(isset($menu) && $menu=='Province') active @endif">
                                    <i class="nav-icon fa fa-map-marker-alt"></i>
                                    <p>
                                        Manage Province
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('school.index')}}" class="nav-link @if(isset($menu) && $menu=='School') active @endif">
                                    <i class="nav-icon fa fa-school"></i>
                                    <p>
                                        Manage School
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('skill.index')}}" class="nav-link @if(isset($menu) && $menu=='Skill') active @endif">
                                    <i class="nav-icon fas fa-lightbulb"></i>
                                    <p>
                                        Manage Skill
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('sponsors.index')}}" class="nav-link @if(isset($menu) && $menu=='Sponsors') active @endif">
                                    <i class="nav-icon fas fa-handshake"></i>
                                    <p>
                                        Manage Sponsors
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('skill-review.index')}}" class="nav-link @if(isset($menu) && $menu=='SkillReview') active @endif">
                                    <i class="nav-icon fa fa-clipboard-check"></i>
                                    <p>
                                        Skill Review
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('ranking.index')}}" class="nav-link @if(isset($menu) && $menu=='Rankings') active @endif">
                                    <i class="nav-icon fa fa-medal"></i>
                                    <p>
                                        Rankings
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('consent.index')}}" class="nav-link @if(isset($menu) && $menu=='Consent Message') active @endif">
                                    <i class="nav-icon fa fa-check-circle"></i>
                                    <p>
                                        Consent Message
                                    </p>
                                </a>
                            </li> 

                            <li class="nav-item">
                                <a href="{{route('cms_page.index')}}" class="nav-link @if(isset($menu) && $menu=='CMS') active @endif">
                                    <i class="nav-icon fa fa-file-alt"></i>
                                    <p>
                                        CMS Pages
                                    </p>
                                </a>
                            </li>  

                            <li class="nav-item">
                                <a href="{{route('notification.index')}}" class="nav-link @if(isset($menu) && $menu=='Notification') active @endif">
                                    <i class="nav-icon fa fa-bell"></i>
                                    <p>
                                        Notification
                                    </p>
                                </a>
                            </li> 

                            <li class="nav-item">
                                <a href="{{route('email-templates.index')}}" class="nav-link @if(isset($menu) && $menu=='Email Template') active @endif">
                                    <i class="nav-icon fa fa-envelope"></i>
                                    <p>
                                        Email Templates
                                    </p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{route('fees.index')}}" class="nav-link @if(isset($menu) && $menu=='Fees') active @endif">
                                    <i class="nav-icon fa fa-dollar-sign"></i>
                                    <p>
                                        Fees
                                    </p>
                                </a>
                            </li>  

                            <li class="nav-item">
                                <a href="{{route('contactus.index')}}" class="nav-link @if(isset($menu) && $menu=='Contact Us') active @endif">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>
                                        Contact Us
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

            <!-- Modal -->
            <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="commonModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <!-- Modal content -->
                    </div>
                </div>
            </div>

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
        <script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{ URL::asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL::asset('assets/dist/js/adminlte.js')}}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ URL::asset('assets/dist/js/demo.js')}}"></script>
        <script src="{{ URL::asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>


        <!-- AdminLTE for datatables -->

        <script src="{{ URL::asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{ URL::asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ URL::asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{ URL::asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>


        <script src="{{ URL::asset('assets/dist/js/table-actions.js')}}?{{ time() }}"></script>
        <script src="{{ URL::asset('assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{ URL::asset('assets/plugins/summernote/summernote.min.js')}}"></script>       
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/spin.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/ladda.min.js"></script>       
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/js/bootstrap-select.js"></script> -->


        <script>
            $(document).ready(function() {
                Ladda.bind('button[type=submit]');
                $('.select2').select2();

                /*Datepicker*/
                $('.datepicker').datepicker({
                    format: 'yyyy-m-d',
                    autoclose: true,
                });

                $('select .selectDropdown').selectpicker();

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