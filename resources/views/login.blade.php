<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ config('app.name', 'Hockey App') }} | Log in</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ URL('assets/plugins/fontawesome-free/css/all.min.css')}}" />
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{ URL('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}" />
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ URL('assets/dist/css/adminlte.min.css')}}" />
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="{{ URL::to('/login') }}">
                        <h3>Hockey App</h3>
                    </a>
                </div>
                <div class="card-body">
                    @include("admin.common.error")
                    <p class="login-box-msg">Sign in to start your session</p>
                    <form class="" role="form" method="POST" action="{{ route('user.login') }}">
                        {{ csrf_field() }}
                        <div class="input-group {{ $errors->has('username') ? ' has-error' : '' }} has-feedback mb-3">
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @if ($errors->has('username'))
                                <span class="text-danger w-100">{{ $errors->first('username') }}</span>
                            @endif
                        </div>
                        <div class="input-group {{ $errors->has('password') ? ' has-error' : '' }} has-feedback mb-3">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @if ($errors->has('password'))
                                <span class="text-danger w-100">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember" />
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    <hr>
                    <p class="mb-1">
                        <a href="forgot-password.html">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="register.html" class="text-center">Register a new membership</a>
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="{{ URL('assets/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ URL('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL('assets/dist/js/adminlte.min.js')}}"></script>
    </body>
</html>