<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use App\Models\Product;

use App\Helpers\Helper;

use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.manage');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Fix the above issues and try again',
                'validate_errors' => $validator->errors(),
                'notify' => true,
            ], 422);
        }

        if(!Product::create($request->all()))
        {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again',
                'validate_errors' => null,
                'notify' => true,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'validate_errors' => null,
            'notify' => true,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('uuid', $id)->first();

        if(!$product)
        {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'validate_errors' => null,
                'notify' => true,
            ], 500);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Fix the above issues and try again',
                'validate_errors' => $validator->errors(),
                'notify' => true,
            ], 422);
        }

        if(!$product->update($request->all()))
        {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again',
                'validate_errors' => null,
                'notify' => true,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'validate_errors' => null,
            'notify' => true,
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::where('uuid', $id)->first();

        if(!$product)
        {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'validate_errors' => null,
                'notify' => true,
            ], 422);
        }

        if(!$product->delete())
        {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again',
                'notify' => true,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
            'notify' => true,
        ], 200);
    }

    public function show($id)
    {
        $product = Product::where('uuid', $id)->first();

        if(!$product)
        {
            return response()->json([
                'success' => false,
                'message' => 'Product not found3',
                'validate_errors' => null,
                'notify' => true,
            ], 404);
        }

        $product->created_at_formatted = $product->created_at->format('jS \of F Y g:i A');

        $product_data = [
            'uuid' => $product->uuid,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'created_at' => $product->created_at_formatted,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product_data,
            'validate_errors' => null,
            'notify' => false,
        ], 200);
    }

    public function getProductsData(Request $request)
    {
        $query = Product::select(
            'products.id', 
            'products.uuid', 
            'products.name', 
            'products.stock_quantity', 
            'products.price', 
            'products.description', 
            'products.created_at'
        );
    
        if ($search = $request->input('search.value')) {
            $query->where(function($subQuery) use ($search) {
                $subQuery->where('products.name', 'LIKE', "%{$search}%")
                    ->orWhere('products.description', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->get();

        $products = $query->get()->map(function($product) {
            $product->formatted_created_at = Carbon::parse($product->created_at)->format('jS \\of F Y g:i A');
            return $product;
        });
    
        return Datatables::of($products)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a class="me-2" href="javascript:void(0)" onclick="viewRecord(\'' . $row->uuid . '\')"><i class="fas fa-eye text-success"></i></a>';
                $actionBtn .= '<a class="me-2" href="javascript:void(0)" onclick="editRecord(\'' . $row->uuid . '\')"><i class="fas fa-pencil-alt"></i></a>';
                $actionBtn .= '<a href="javascript:void(0)" onclick="deleteRecord(\'' . $row->uuid . '\')"><i class="fas fa-trash-alt text-danger"></i></a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
