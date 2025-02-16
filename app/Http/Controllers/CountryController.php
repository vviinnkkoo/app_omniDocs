<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Country;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function show() {
        $countries = Country::get()->sortBy('id');
        
        return view('countries', [
            'countries' => $countries
            ]);
    }


    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'name' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/drzave-poslovanja')
                    ->withInput()
                    ->withErrors($validator);
            }
        $country = new Country;
        $country->name = $request->name;
        $country->save();
    
        return redirect('/drzave-poslovanja');
    }


    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $country->$field = $newValue;
        $country->save();
        return response()->json(['message' => 'Payment type updated successfully']);
    }


    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Country::findOrFail($id);
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
