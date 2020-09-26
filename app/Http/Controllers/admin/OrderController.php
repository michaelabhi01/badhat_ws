<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Product;
use App\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {   
        $orders = Order::with('user')
        				->with('items.product')
            			->get();
            			// ->toArray();
        // echo "<pre>"; print_r($orders); die;
        return view('layout.order.index', compact('orders'));
    }
}
