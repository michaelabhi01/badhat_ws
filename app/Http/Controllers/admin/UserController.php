<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        // echo "<pre>"; print_r($users); die;
        return view('layout.user.index', compact('users'));
    }

    public function dashboard()
    {
        $total_users = User::all()->count();
        $total_product = Product::all()->count();
        $total_orders = Order::all()->count();
        $total_users_weekly = User::where('created_at', '>=', Carbon::now()->startOfWeek()->format('Y-m-d'))
            ->get()->count();

        return view('layout.user.dashboard', compact('total_users', 'total_product', 'total_orders','total_users_weekly'));
    }
}
