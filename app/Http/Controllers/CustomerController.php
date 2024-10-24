<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

use App\Helpers\Helper;

class CustomerController extends Controller
{
    public function order(Request $request)
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        $auth_user = Helper::getAuth($request);

        return view('customer.index', [
            'products' => $products,
            'auth_user' => $auth_user
        ]);
    }
}
