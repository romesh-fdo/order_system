<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use App\Models\User;

use App\Helpers\Helper;

class OrderController extends Controller
{
    public function place(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'user_id' => 'required|exists:users,uuid',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,uuid',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
    
        $validator->after(function ($validator) {
            if ($validator->errors()->has('items.*.quantity')) {
                $validator->errors()->add('items.quantity', 'One or more items have invalid quantity. Each item must have a quantity of at least 1.');
            }
        });

        $order_data = $request->json()->all();

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'validate_errors' => $validator->errors(),
                'notify' => true,
            ], 422);
        }

        $user = User::where('uuid', $order_data['user_id'])->first();

        if(!$user)
        {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'notify' => true,
            ], 404);
        }

        DB::beginTransaction();

        try 
        {
            $order = Order::create([
                'user_id' => $user['id'],
                'total_price' => 0,
                'status_id' => Status::getIdByCode(Status::NEW_ORDER),
            ]);

            $total_price = 0;

            foreach ($order_data['items'] as $item) {
                $product = Product::where('uuid', $item['product_id'])->first();

                $price = $product->price * $item['quantity'];
                $total_price += $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);
            }

            $order->update(['total_price' => $total_price]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_uuid' => $order->uuid,
                'notify' => true,
            ], 201);
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place the order. Please try again.',
                'error' => $e->getMessage(),
                'notify' => true,
            ], 500);
        }
    }

    public function getMyOrders(Request $request)
    {
        $user = Helper::getAuth($request);

        $orders = Order::where('user_id', $user->id)
            ->with(['orderItems.product', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();

        $response = $orders->map(function ($order) {
            return [
                'uuid' => $order->uuid,
                'total_price' => $order->total_price,
                'status_name' => $order->status->status,
                'status_badge' => $order->status->badge,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'product' => [
                            'uuid' => $item->product->uuid,
                            'name' => $item->product->name,
                            'description' => $item->product->description,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                        ],
                        'subtotal' => $item->quantity * $item->price,
                    ];
                }),
                'order_total' => $order->orderItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                }),
                'created_at' => $order->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'order_details' => $response,
        ], 200);
    }
}
