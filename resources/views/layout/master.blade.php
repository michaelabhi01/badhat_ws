<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') | Badhat</title>
    <!-- <script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script type="text/javascript" src="{{ url('js/sweetalert.min.js') }}"></script>
    <link href="{{ url('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('css/dataTables.bootstrap4.min.css') }}" />
</head>

<body>

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
    <div id="main-wrapper" class="show">

        @include('layout.common.header')
        @include('layout.common.sidebar')

        <div class="content-body" style="min-height: 774px; background-color: #f9f8f780;">
            <div class="container-fluid mt-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-body">
                            {{-- <div class="card-body"> --}}
                                <h3 style="margin-bottom:40px">@yield('section_title')</h4>
                                    @yield('content')
                                    {{-- <i class="glyphicon glyphicon-lock"></i> --}}
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            @include('layout.common.modal')
        </div>

        <script type="text/javascript" src="{{ url('js/jquery-3.5.1.js') }}"></script>
        <!-- <link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css"
            rel="stylesheet">
        <script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
 -->
        <script src="{{ url('plugins/common/common.min.js')}}"></script>
        <script src="{{ url('js/custom.min.js')}}"></script>
        <script src="{{ url('js/settings.js')}}"></script>
        <script src="{{ url('js/gleek.js')}}"></script>
        <script src="{{ url('js/styleSwitcher.js')}}"></script>

        <script type="text/javascript" src="{{ url('js/jquery.dataTables.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('js/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Circle progress -->
<!--         <script src="{{ url('plugins/circle-progress/circle-progress.min.js')}}"></script>
 -->
        <!-- Pignose Calender -->
       <!--  <script src="{{ url('plugins/moment/moment.min.js')}}"></script>
        <script src="{{ url('plugins/pg-calendar/js/pignose.calendar.min.js')}}"></script>

        <script src="{{ url('plugins/validation/jquery.validate.min.js')}}"></script>
        <script src="{{ url('plugins/validation/jquery.validate-init.js')}}"></script> -->

        <script>
            // $.noConflict();

            // $(function() {
            //     $.ajaxSetup({
            //         headers: {
            //         'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            // });
            $(".delete").on("submit", function(){
                return confirm("Are you sure?");
            });

            $(document).ready(function() {
                $('#example').DataTable();
            } );
        </script>
        @stack('scripts')
</body>

</html>
