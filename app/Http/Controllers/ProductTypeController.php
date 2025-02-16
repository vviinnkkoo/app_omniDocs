<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductType;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ProductTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function show() {
        $productTypes = ProductType::get()->sortBy('id');
        
        return view('productTypes', [
            'productTypes' => $productTypes
            ]);
    }


    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'name' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/vrste-proizvoda')
                    ->withInput()
                    ->withErrors($validator);
            }
        $productType = new ProductType;
        $productType->name = $request->name;
        $productType->save();
    
        return redirect('/vrste-proizvoda');
    }


    public function update(Request $request, $id)
    {
        $productType = ProductType::findOrFail($id);
        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $productType->$field = $newValue;
        $productType->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }


    public function destroy(Request $request, $id): JsonResponse
    {
        $record = ProductType::findOrFail($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
