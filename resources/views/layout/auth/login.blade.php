<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Badhat</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('')}}">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"> -->
    <link href="{{ url('css/style.css')}}" rel="stylesheet">

</head>

<body class="h-100">

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->
    @include('sweet::alert')
    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <div class="text-center">
                                    <img width="100" height="100" src="{{ asset('images/logo.jpeg') }}">
                                </div>
                                <div class="text-center">
                                    <h4>Badhat</h4>
                                </div>
                                <div class="text-center">
                                </div>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }} </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form class="mt-5 mb-5 login-input" action="{{ route('login')}}"
                                    enctype="multipart/form-data" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input name="email" type="email" class="form-control" placeholder="Email"
                                            value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group">
                                        <input name="password" type="password" class="form-control"
                                            placeholder="Password">
                                    </div>
                                    <button class="btn login-form__btn submit w-100">Sign In</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!--**********************************
        Scripts
    ***********************************-->

    <script src="{{ url('plugins/common/common.min.js')}}"></script>
    <script src="{{ url('js/custom.min.js')}}"></script>
    <script src="{{ url('js/settings.js')}}"></script>
    <script src="{{ url('js/gleek.js')}}"></script>
    <script src="{{ url('js/styleSwitcher.js')}}"></script>
</body>

</html>
