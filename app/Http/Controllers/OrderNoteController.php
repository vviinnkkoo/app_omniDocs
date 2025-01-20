<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

use App\Models\OrderNote;

class OrderNoteController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }


    // POST function for saving new stuff
    public function add (Request $request, $id) {
        $validator = Validator::make($request->all(), [
        'note' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/uredi-narudzbu/' . $id)
                ->withInput()
                ->withErrors($validator);
        }

        $orderNote = new OrderNote;
        $orderNote->note = $request->note;
        $orderNote->order_id = $id;
        $expense->save();
    
        return redirect('/uredi-narudzbu/' . $id);
    }


    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {
        $record = OrderNote::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $record->$field = $newValue;
        $record->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    
    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {
        $record = OrderNote::findOrFail($id);
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
    
}
