<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Color;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ColorController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // GET function for displaying purposes
    public function show() {
        $colors = Color::get()->sortBy('id');
        
        return view('colors', [
            'colors' => $colors
            ]);
    }


    // POST function for saving new stuff
    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'color_name' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/boje-proizvoda')
                    ->withInput()
                    ->withErrors($validator);
            }
        $color = new Color;
        $color->color_name = $request->color_name;
        $color->save();
    
        return redirect('/boje-proizvoda');
    }


     // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $color->$field = $newValue;
        $color->save();
        return response()->json(['message' => 'Payment type updated successfully']);
    }


    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Color::findOrFail($id);
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
