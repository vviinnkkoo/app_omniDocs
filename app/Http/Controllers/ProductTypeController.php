<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductType;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ProductTypeController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // GET function for displaying purposes
    //
    public function show() {
        $productTypes = ProductType::get()->sortBy('id');
        
        return view('productTypes', [
            'productTypes' => $productTypes
            ]);
    }


    // POST function for saving new stuff
    //
    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'type_name' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/vrste-proizvoda')
                    ->withInput()
                    ->withErrors($validator);
            }
        $productType = new ProductType;
        $productType->type_name = $request->type_name;
        $productType->save();
    
        return redirect('/vrste-proizvoda');
    }


     // UPDATE (Ajax version)
    //
    public function update(Request $request, $id)
    {

        // Validate and update the payment type in the database
        $productType = ProductType::findOrFail($id);

        // Get the field name and new value from the request
        $field = $request->input('field');
        $newValue = $request->input('newValue');

        // Update the attribute in the model
        $productType->$field = $newValue;

        // Save the model to the database
        $productType->save();

        // Return a JSON response or other appropriate response
        return response()->json(['message' => 'Payment type updated successfully']);
    }


    // DELETE function (Ajax version)
    //
    public function destroy(Request $request, $id): JsonResponse
    {
        // Find the record by ID
        $record = ProductType::findOrFail($id);

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
