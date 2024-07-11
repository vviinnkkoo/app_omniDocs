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
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // GET function for displaying purposes
    //
    public function show() {
        $products = Product::get()->sortBy('id');
        $productTypes = ProductType::get()->sortBy('id');
        
        return view('products', [
            'products' => $products,
            'productTypes' => $productTypes
            ]);
    }


    // POST function for saving new stuff
    //
    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'product_name' => 'required',
        'product_type_id' => 'required',
        'default_price' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/proizvodi')
                    ->withInput()
                    ->withErrors($validator);
            }
        $products = new Product;
        $products->product_name = $request->product_name;
        $products->product_type_id = $request->product_type_id;
        $products->default_price = $request->default_price;
        $products->save();
    
        return redirect('/proizvodi');
    }


    // UPDATE (Ajax version)
    //
    public function update(Request $request, $id)
    {

        // Validate and update the payment type in the database
        $product = Product::findOrFail($id);

        // Get the field name and new value from the request
        $field = $request->input('field');
        $newValue = $request->input('newValue');

        // Update the attribute in the model
        $product->$field = $newValue;

        // Save the model to the database
        $product->save();

        // Return a JSON response or other appropriate response
        return response()->json(['message' => 'Payment type updated successfully']);
    }


    // DELETE function (Ajax version)
    //
    public function destroy(Request $request, $id): JsonResponse
    {
        // Find the record by ID
        $record = Product::findOrFail($id);

        // Check if the record exists
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        // Delete the record
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
