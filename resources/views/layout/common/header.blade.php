<!--**********************************
            Nav header start
        ***********************************-->
<div class="nav-header">
    <div class="brand-logo">
        <a href="#_">
            <b class="logo-abbr" style="color:white;">Badhat</b>
            <span class="logo-compact" style="color:white;">Badhat</span>
            <span class="brand-title" style="color:white;">
                Badhat
            </span>
        </a>
    </div>
</div>
<style type="text/css">
    [data-headerbg="color_1"] .header{
        background-color: #7571f9 !important;
    }
</style>
<!--**********************************
            Nav header end
        ***********************************-->

<!--**********************************
            Header start
        ***********************************-->
<div class="header">
    <div class="header-content clearfix">

        <div class="nav-control">
            <div class="hamburger">
                <span class="toggle-icon"><i class="icon-menu"></i></span>
            </div>
        </div>
        <div class="header-right">
            <ul class="clearfix">


                <li class="icons dropdown">
                    <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                        <img src="{{ url('images/form-user.png') }}" height="40" width="40" alt="">
                    </div>
                    <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                        <div class="dropdown-content-body">
                            <ul>
                                <li><a href="{{ route('logout') }}"><i class="icon-key"></i> <span>Logout</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!--**********************************
            Header end ti-comment-alt
        ***********************************-->
