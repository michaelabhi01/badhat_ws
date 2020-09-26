<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') | Badhat</title>
    <link href="{{ url('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" />

    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css" />
</head>

<body>


    @include('sweet::alert')
    <div id="main-wrapper">

        @include('layout.common.header')
        @include('layout.common.sidebar')

        <div class="content-body" style="min-height: 773px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 style="margin-bottom:40px">@yield('section_title')</h4>
                                    @yield('content')
                                    {{-- <i class="glyphicon glyphicon-lock"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layout.common.footer')

        </div>

        <script type="text/javascript" src="{{ url('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js">
        </script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js">
        </script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js">
        </script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.65/vfs_fonts.js">
        </script>
        <link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css"
            rel="stylesheet">
        <script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

        <script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="{{ url('plugins/common/common.min.js')}}"></script>
        <script src="{{ url('js/custom.min.js')}}"></script>
        <script src="{{ url('js/settings.js')}}"></script>
        <script src="{{ url('js/gleek.js')}}"></script>
        <script src="{{ url('js/styleSwitcher.js')}}"></script>


        <!-- Circle progress -->
        <script src="{{ url('plugins/circle-progress/circle-progress.min.js')}}"></script>

        <!-- Pignose Calender -->
        <script src="{{ url('plugins/moment/moment.min.js')}}"></script>
        <script src="{{ url('plugins/pg-calendar/js/pignose.calendar.min.js')}}"></script>

        <script src="{{ url('plugins/validation/jquery.validate.min.js')}}"></script>
        <script src="{{ url('plugins/validation/jquery.validate-init.js')}}"></script>

        <script>
            $.noConflict();

            $(function() {
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
            $(document).ready(function() {
                $('#my-datatable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            title: "Complaints | Rail Seva",
                            exportOptions: {
                                modifier: {
                                page: 'all'
                                },
                                    format: {
                                        header: function ( data, columnIdx ) {
                                            if(columnIdx==1){
                                            return 'Complaints | Rail Seva';
                                            }
                                            else{
                                            return data;
                                            }
                                        }
                                    }
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            title: "Complaints | Rail Seva",
                            customize: function ( doc )
                            {
                                doc.content.splice( 1, 0,
                                {
                                    margin: [ 0, 0, 0, 12 ],
                                    alignment: 'center',
                                    image: 'data:image/png;base64,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'
                                });
                                // console.log(doc.content)
                            },
                        },
                    ]
                } );
            } );

            $(".delete").on("submit", function(){
                return confirm("Are you sure?");
            });
        </script>
        @stack('scripts')



</body>

</html>
