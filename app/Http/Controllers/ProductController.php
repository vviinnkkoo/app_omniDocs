<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductType;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Product::query();

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('productType', function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                      });
            });
        }

        $products = $query->orderBy('name')->paginate(25);
        $productTypes = ProductType::orderBy('id')->get();

        return view('pages.products.index', compact('products', 'productTypes', 'search'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'product_type_id' => 'required|exists:product_type,id',
            'default_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        Product::create($request->all());

        return redirect()->back()->with('success', 'Proizvod je uspješno dodan.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $product->$field = $newValue;
        $product->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Product::findOrFail($id);
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
