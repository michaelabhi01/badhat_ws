<?php

namespace App\Http\Controllers\api;

use App\CartItem;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Order;
use App\OrderItem;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    /**
     * Cancel an order by user
     *
     * @param Request $request
     * @return void
     */
    public function cancelOrder(Request $request, int $id)
    {
        $order = Order::find($id);
        $order->status = "Cancelled";
        $order->save();

        $notification = new Notification();
        $notification->user_id = $order->user_id;
        // $notification->vendor_id = $order->vendor_id;
        $notification->message = "Your order #" . $order->id . " has been cancelled.";
        $notification->save();
        
        $vendor = User::find($order->vendor_id);
        $user = User::find($order->user_id);
        $this->sendPush('Order Cancelled', $notification->message, $user->fcm_token);
        $this->sendPush('Order Cancelled',$notification->message, $vendor->fcm_token);

        return $this->success([], "Cancelled");
    }

    /**
     * Accept an order by user
     *
     * @param Request $request
     * @return void
     */
    public function acceptOrder(Request $request, int $id)
    {
        $order = Order::find($id);
        $order->status = 'Accepted';
        $order->save();

        $notification = new Notification();
        $notification->user_id = $order->user_id;
        // $notification->vendor_id = $order->vendor_id;
        $notification->message = "Your order #" . $order->id . " has been accepted.";
        $notification->save();
        $user = User::find($order->user_id);
        $this->sendPush('Order Accepted',$notification->message, $user->fcm_token);

        return $this->success([], "Accepted");
    }

    /**
     * Fetch all orders placed by user
     *
     * @param Request $request
     * @return void
     */
    public function placedOrders(Request $request)
    {

        $orders = Order::where('user_id', $this->getAuthenticatedUser()->id)
            ->with('items')
            ->with('vendor')
            ->latest()
            ->get();

        return $this->success($orders, "Plced Order list");
    }

    /**
     * Fetch all orders received by user
     *
     * @param Request $request
     * @return void
     */
    public function receivedOrders(Request $request)
    {
        $user_id = $this->getAuthenticatedUser()->id;

        $orders = Order::with(['items' => function ($q) use ($user_id) {
            // $q->with(['product' => function ($q) use ($user_id) {
            //     $q->where('user_id', $user_id);
            // }])
            $q->whereHas('product', function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            });
        }])
            ->whereHas('items', function ($q) use ($user_id) {
                $q->whereHas('product', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                });
            })
            ->with('user')
            ->latest()
            ->get();

        return $this->success($orders, "Received Order list");
    }

    /**
     * Place order
     *
     * @return void
     */
    public function placeOrder()
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $cartItems = CartItem::where('user_id', $user_id)
            ->with('product')
            ->orderBy('vendor_id')
            ->get();

        if (count($cartItems) == 0) {
            return $this->error("No items in cart", 400);
        } else {
            $orders= array();
            try {
                DB::beginTransaction();
                // find unique vendors
                $unique = $this->unique_multidim_array($cartItems, 'vendor_id');

                // echo json_encode($unique); die;


                foreach ($unique as $item) {

                    $items=array();
                    foreach ($cartItems as $ci) {
                        if ($ci->vendor_id == $item->vendor_id) {
                            array_push($items, $ci);
                        }
                    }

                    $order = new Order();
                    $order->user_id = $user_id;
                    $order->vendor_id = $items[0]->vendor_id;

                    $order->save();
                    $order->refresh();

                    array_push($orders,$order);

                    // add order items
                    foreach ($items as $item) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $item->product_id;
                        $orderItem->price = $item->product->price;
                        $orderItem->quantity = $item->quantity;
                        $orderItem->save();
                    }

                    $notification = new Notification();
                    $notification->user_id = $order->user_id;
                    // $notification->vendor_id = $order->vendor_id;
                    $notification->message = "Your order #" . $order->id . " has been placed.";
                    $notification->save();
                    
                    $notification = new Notification();
                    $notification->user_id = $order->vendor_id;
                    // $notification->vendor_id = $order->vendor_id;
                    $notification->message = "Your have received a new order #" . $order->id;
                    $notification->save();

                }

                DB::commit();

                foreach ($orders as $item) {
                    $vendor_fcm = User::find($item->vendor_id)->fcm_token;
                    if ($vendor_fcm) {
                        $this->sendPush('New Order!', 'You have a new order. Please check the app for details.', $vendor_fcm);
                    }
                }
                // delete from cart
                foreach ($cartItems as $item) {
                    $item->delete();
                }

                return $this->success([], "Order placed");

            } catch (Exception $e) {
                // print_r($e->getMessage());
                Log::info($e->getMessage());
                DB::rollBack();
                return $this->error("Order failed, please try again", 400);
            }
        }
    }

    /**
     * Order detail
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function orderDetail(Request $request, int $id)
    {
        $data = Order::where('id', $id)
            ->with('user')
            ->with('items.product.vendor')
            ->first();

        return $this->success($data, "Order detail");
    }
    /**
     * Received Order detail
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function receivedOrderDetail(Request $request, int $id)
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $data = Order::where('id', $id)
            ->with('user')
            ->with('items.product')
            ->whereHas('items.product')
            ->first();

        return $this->success($data, "Order detail");
    }

    public function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}
