<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ config('app.name', 'Hockey App') }} | Log in</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" /> 
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{ URL::asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}" />
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/adminlte.min.css')}}" />
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
                    <p class="login-box-msg">Reset Your Password Here</p>
                    <form role="form" method="POST" action="{{ route('reset.password') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="username" value="{{ $username }}">
                        
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

                        <!-- Confirm Password Field -->
                        <div class="input-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }} has-feedback mb-3">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger w-100">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-8">
                                <!-- Additional content (e.g., remember me) can go here -->
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL::asset('assets/dist/js/adminlte.min.js')}}"></script>
    </body>
</html>