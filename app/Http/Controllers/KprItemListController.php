<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\KprItemList;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class KprItemListController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // POST function for saving new stuff
    //
    public function add (Request $request, $id) {
        $validator = Validator::make($request->all(), [
        'receipt_id' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/uredi-uplatu/' . $id)
                    ->withInput()
                    ->withErrors($validator);
            }
        $kprItemList = new KprItemList;
        $kprItemList->receipt_id = $request->receipt_id;
        $kprItemList->kpr_id = $id;
        $kprItemList->save();
    
        return redirect('/uredi-uplatu/' . $id);
    }

    // DELETE function (Ajax version)
        //
        public function destroy(Request $request, $id): JsonResponse
        {
            // Find the record by ID
            $record = KprItemList::findOrFail($id);

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
