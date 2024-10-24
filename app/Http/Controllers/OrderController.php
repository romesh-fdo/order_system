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
    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'user_id' => 'required|exists:users,uuid',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,uuid',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        
        $validator->after(function ($validator) {
            $quantities = $validator->errors()->get('items.*.quantity');
        
            if (!empty($quantities)) {
                $validator->errors()->add('items_quantities', 'Please provide a valid quantity for all selected products. Each item must have a quantity of at least 1.');
            }
        });
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Fix the above issues and try again',
                'validate_errors' => $validator->errors(),
                'notify' => true,
            ], 422);
        }

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

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'notify' => true,
            ], 404);
        }

        DB::beginTransaction();

        try {
            $total_price = 0;

            foreach ($order_data['items'] as $item)
            {
                $product = Product::where('uuid', $item['product_id'])->first();

                if ($product->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock for ' . $product->name . '. Only ' . $product->stock_quantity . ' items are remaining',
                        'stock_quantity' => $product->stock_quantity,
                        'notify' => true,
                    ], 422);
                }
            }

            $order = Order::create([
                'user_id' => $user['id'],
                'total_price' => 0,
            ]);

            foreach ($order_data['items'] as $item)
            {
                $product = Product::where('uuid', $item['product_id'])->first();

                $price = $product->price * $item['quantity'];
                $total_price += $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);

                $product->update([
                    'stock_quantity' => $product->stock_quantity - $item['quantity']
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
        } catch (\Exception $e) {
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
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $response = $orders->map(function ($order) {
            return [
                'uuid' => $order->uuid,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'items' => $order->orderItems->map(function ($item) {
                    if($item->product)
                    {
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
                    }

                    return [
                        'product' => [
                            'uuid' => null,
                            'name' => "(Product deleted)",
                            'description' => "-",
                            'price' => "-",
                            'quantity' => "-",
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

    public function manageOrders()
    {
        return view('order.manage');
    }

    public function getOrderData(Request $request)
    {
        $query = Order::with(['orderItems', 'user'])
            ->select(
                'orders.id', 
                'orders.uuid', 
                'orders.user_id', 
                'orders.total_price', 
                'orders.status', 
                'orders.created_at'
            );

        if ($search = $request->input('search.value')) {
            $query->where(function($subQuery) use ($search) {
                $subQuery->where('orders.total_price', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $orders = $query->get()->map(function($order) {
            $order->formatted_created_at = \Carbon\Carbon::parse($order->created_at)->format('jS \\of F Y g:i A');
            $order->user_name = $order->user->name;
            return $order;
        });

        return \DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a class="me-2" href="javascript:void(0)" onclick="viewRecord(\'' . $row->uuid . '\')"><i class="fas fa-eye text-success"></i></a>';
                if($row->status == Order::PENDING)
                {
                    $actionBtn .= '<a class="me-2" href="javascript:void(0)" onclick="updateOrderStatus(\'' . $row->uuid . '\',\'cancelled\')"><i class="fas fa-ban text-danger"></i></a>';
                    $actionBtn .= '<a class="me-2" href="javascript:void(0)" onclick="updateOrderStatus(\'' . $row->uuid . '\',\'completed\')"><i class="fa-regular fa-circle-check text-primary"></i></a>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Request $request)
    {
        $order = Order::with(['orderItems', 'user'])
            ->where('uuid', $request['record_id'])
            ->first();

        if(!$order)
        {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'validate_errors' => null,
                'notify' => true,
            ]);
        }

        $orderData = [
            'uuid' => $order->uuid,
            'user_name' => $order->user->name,
            'total_price' => $order->total_price,
            'formatted_created_at' => $order->created_at->format('jS \\of F Y g:i A'),
            'items' => $order->orderItems->filter(function($item) {
                return $item->product && !$item->product->trashed();
            })->map(function($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_item_price' => $item->quantity * $item->price
                ];
            }),
            'total_order_price' => $order->orderItems->filter(function($item) {
                return $item->product && !$item->product->trashed();
            })->sum(function($item) {
                return $item->quantity * $item->price;
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $orderData,
            'message' => 'Order details retrieved successfully',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->json()->all(), [
            'status' => 'required|in:cancelled,completed',
        ]);

        $new_order_data = $request->json()->all();

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Fix the above issues and try again',
                'validate_errors' => $validator->errors(),
                'notify' => true,
            ], 422);
        }
    
        $order = Order::where('uuid', $id)->first();
    
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'notify' => true,
            ], 404);
        }
    
        if ($order->status != Order::PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be updated as it has already been processed',
                'notify' => true,
            ], 422);
        }
    
        $new_status = $new_order_data['status'] === 'cancelled' ? Order::CANCELLED : Order::COMPLETED;
    
        if (!$order->update(['status' => $new_status])) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again',
                'notify' => true,
            ], 422);
        }
    
        return response()->json([
            'success' => true,
            'message' => "Order {$new_order_data['status']} successfully",
            'notify' => true,
        ], 200);
    }
}
