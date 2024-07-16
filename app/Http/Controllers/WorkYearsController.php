<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WorkYears;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class WorkYearsController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }


    // GET function for displaying purposes
    public function show() {        
        return view('workYears');
    }


    // POST function for saving new stuff
    public function save (Request $request) {

        $validator = Validator::make($request->all(), [
        'year' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/radne-godine')
                    ->withInput()
                    ->withErrors($validator);
            }
        $source = new WorkYears;
        $source->year = $request->year;
        $source->save();
    
        return redirect('/radne-godine');
    }


    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {

        $source = WorkYears::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $source->$field = $newValue;
        $source->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }


    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {

        $record = WorkYears::findOrFail($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
