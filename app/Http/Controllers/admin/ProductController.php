<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {   
        $products = Product::with('vendor')
        				->with('category')
        				->with('subcategory')
            			->with('vertical')
            			->get();
            			// ->toArray();
        // echo "<pre>"; print_r($products); die;
        return view('layout.product.index', compact('products'));
    }
}
