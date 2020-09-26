<?php

namespace App\Http\Controllers\api;

use App\CartItem;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function index()
    {
        $data = CartItem::where('user_id', $this->getAuthenticatedUser()->id)
            ->with('product')
            ->get();

        return $this->success($data, "Cart data");
    }

    public function addToCart(Request $request)
    {
        $user_id = $this->getAuthenticatedUser()->id;

        // product owned by user cannot be added
        $product = Product::find($request->product_id);
        if ($product->user_id == $user_id) {
            return $this->error("Cannot buy own product", 400);
        }

        $item = CartItem::where('user_id', $user_id)
            ->where('product_id', $request->product_id)->first();
        if ($item) {
            $item->quantity += 1;
        } else {
            $item = new CartItem();
            $item->user_id = $user_id;
            $item->product_id = $request->product_id;
            $item->vendor_id = $request->vendor_id;
            $item->quantity = $request->quantity;
        }
        
        if ($item->save()) {
            $cart_count = CartItem::where('user_id',$user_id)->get()->count();
            return $this->success($cart_count, "Item added");
        } else {
            return $this->error("Item failed to add", 400);
        }
    }

    /**
     * Delete item from cart
     *
     * @param [type] $id
     * @return void
     */
    public function deleteFromCart($id)
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $item = CartItem::find($id);
        if ($item) {
            $item->delete();
            $cart_count = CartItem::where('user_id',$user_id)->get()->count();
            return $this->success($cart_count, "Deleted");
        } else {
            return $this->error("Cart item doesn't exist");
        }
    }

    /**
     * Decrease cart item quantity in multiples of MOQ of product
     *
     * @param Request $request
     * @return void
     */
    public function decreaseCartItemQuantity(Request $request)
    {
        $cartItem = CartItem::find($request->cart_id);
        $cartItem->quantity -= 1;
        if ($cartItem->save()) {
            return $this->success([], "Quantity decreased");
        } else {
            return $this->error("Failed to decrease quantity");
        }
    }
}
