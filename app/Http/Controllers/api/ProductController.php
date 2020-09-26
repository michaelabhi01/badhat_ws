<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    /**
     * List all products
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $products = Product::select('id', 'name', 'image', 'description', 'moq', 'price', 'category_id')
            ->with('category')
            ->where('user_id', $user_id)
            ->latest()
            ->get();

        return $this->success($products, "All Products");
    }

    /**
     * Add Product
     *
     * @param Request $request
     * @return void
     */
    public function addProduct(Request $request)
    {
        try {
            $product = new Product();
    
            $product->name = $request->name;
            $product->description = $request->description;
            $product->moq = $request->moq;
            $product->price = $request->price;
            $product->category_id = $this->getUserCategoryId();
            $product->user_id = $this->getAuthenticatedUser()->id;
            $product->sub_category_id = $request->sub_category_id;
            $product->vertical_id = $request->vertical_id;
            $path = Storage::put('public/products', $request->file('image'), 'public');
            $product->image = $path;
    
            if ($product->save()) {
                return $this->success([], "Product added");
            } else {
                return $this->error([], "Failed to add product");
            }
        } catch (Exception $e) {
            return $this->error("Failed to update product");
        }
    }

    /**
     * Edit Product
     *
     * @param Request $request
     * @return void
     */
    public function editProduct(Request $request)
    {
        $product = Product::find($request->id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->moq = $request->moq;
        $product->price = $request->price;
        $product->category_id = $this->getUserCategoryId();
        $product->user_id = $this->getAuthenticatedUser()->id;
        $product->sub_category_id = $request->sub_category_id;
        $product->vertical_id = $request->vertical_id;

        if ($product->save()) {
            return $this->success([], "Product Updated");
        } else {
            return $this->error([], "Failed to update product");
        }

    }

    /**
     * Delete a product if it exists and can be deleted
     *
     * @param integer $id
     * @return void
     */
    public function deleteProduct(int $id)
    {

        $product = Product::find($id);
        if ($product && $product->can_delete) {
            $product->delete();
            return $this->success([], "Product Deleted");
        } else {
            return $this->error("Invalid Product");
        }
    }

    public function productDetail($id)
    {
        $product = Product::where('id', $id)
            ->with('subcategory')
            ->with('vertical')
            ->first();

        return $this->success($product, "Product Detail");
    }
}
