@extends('layout.master')

@section('title')
Dashboard
@endsection

@section('content')

<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-1">
            <div class="card-body">
                <h3 class="card-title text-white">Total Orders</h3>
                <div class="d-inline-block">
                <h2 class="text-white">{{ $total_orders }}</h2>
                    {{-- <p class="text-white mb-0">Jan - March 2019</p> --}}
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-shopping-cart"></i></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-2">
            <div class="card-body">
                <h3 class="card-title text-white">Total Products</h3>
                <div class="d-inline-block">
                <h2 class="text-white">{{ $total_product }}</h2>
                    {{-- <p class="text-white mb-0">Jan - March 2019</p> --}}
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-money"></i></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-3">
            <div class="card-body">
                <h3 class="card-title text-white">Total Users</h3>
                <div class="d-inline-block">
                <h2 class="text-white">{{ $total_users }}</h2>
                    {{-- <p class="text-white mb-0">Jan - March 2019</p> --}}
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-4">
            <div class="card-body">
                <h3 class="card-title text-white">New users this week</h3>
                <div class="d-inline-block">
                <h2 class="text-white">{{ $total_users_weekly }}</h2>
                    {{-- <p class="text-white mb-0">Jan - March 2019</p> --}}
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-heart"></i></span>
            </div>
        </div>
    </div>
</div>

@endsection
