<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PrintLabel;
use App\Models\Order;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class PrintLabelController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function deleteAllLabels() {
        PrintLabel::truncate();
        return redirect('/dostavne-etikete');
    }
    
    // GET function for displaying purposes
    //
    public function showShippingLabels() {
        $shippingLabels = PrintLabel::where('label_type', 'shipping')->get();
        $orders = Order::get()->sortBy('id');
        
        return view('shippingLabelsPrint', [
            'shippingLabels' => $shippingLabels,
            'orders' => $orders
            ]);
    }


    // POST function for saving new stuff
    //
    public function saveShippingLabel (Request $request) {
        $validator = Validator::make($request->all(), [
        'order_id' => 'required',
        'label_type' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/dostavne-etikete')
                    ->withInput()
                    ->withErrors($validator);
            }
        $record = new PrintLabel;
        $record->order_id = $request->order_id;
        $record->label_type = $request->label_type;
        $record->save();
    
        return redirect('/dostavne-etikete');
    }


     // UPDATE (Ajax version)
    //
    public function update(Request $request, $id)
    {

        // Validate and update the payment type in the database
        $record = PrintLabel::findOrFail($id);

        // Get the field name and new value from the request
        $field = $request->input('field');
        $newValue = $request->input('newValue');

        // Update the attribute in the model
        $record->$field = $newValue;

        // Save the model to the database
        $record->save();

        // Return a JSON response or other appropriate response
        return response()->json(['message' => 'Payment type updated successfully']);
    }


    // DELETE function (Ajax version)
    //
    public function destroyShippingLabel(Request $request, $id): JsonResponse
    {
        // Find the record by ID
        $record = PrintLabel::findOrFail($id);

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
