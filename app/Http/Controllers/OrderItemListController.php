<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderItemList;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class OrderItemListController extends Controller
{       
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // POST function for saving new stuff
    public function add (Request $request, $id) {
        $validator = Validator::make($request->all(), [
        'product_id' => 'required',
        'amount' => 'required',
        'color_id' => 'required',
        'price' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/uredi-narudzbu/' . $id)
                    ->withInput()
                    ->withErrors($validator);
            }
        $orderItemList = new OrderItemList;
        $orderItemList->product_id = $request->product_id;
        $orderItemList->order_id = $id;
        $orderItemList->amount = $request->amount;
        $orderItemList->price = $request->price;
        $orderItemList->color_id = $request->color_id;
        $orderItemList->note = $request->note;
        $orderItemList->save();
    
        return redirect('/uredi-narudzbu/' . $id);
    }

    public function showProductionItems($mode) {

        // show all items to be produced
        if ($mode === '1') {
            $items = OrderItemList::whereHas('order', function ($query) {
                $query->whereNull('date_sent')->whereNull('date_cancelled');
            })->get();

            $title = "Svi proizvodi za izradu";

        // show items grouped by color
        } elseif ($mode === '2') {
            $items = OrderItemList::select('product_id', 'color_id', \DB::raw('SUM(amount) as amount'))
            ->whereHas('order', function ($query) {
                $query->whereNull('date_sent')->whereNull('date_cancelled');
            })
            ->groupBy(['product_id', 'color_id'])
            ->get();

            $title = "Količine za izradu - po boji";
        
        // show items grouped by product
        } elseif ($mode === '3') {
            $items = OrderItemList::select('product_id', \DB::raw('SUM(amount) as amount'))
            ->whereHas('order', function ($query) {
                $query->whereNull('date_sent')->whereNull('date_cancelled');
            })
            ->groupBy(['product_id'])
            ->get();

            $title = "Količine za izradu - po proizvodu";

        } elseif ($mode === '4') {
            $items = OrderItemList::select('product_id', \DB::raw('SUM(amount) as amount'))
            ->whereHas('order', function ($query) {
                $query->whereNotNull('date_sent')->whereNull('date_cancelled');
            })
            ->groupBy(['product_id'])
            ->get();

            $title = "Izrađene količine - po proizvodu";

        } else {
            return;
        }


        return view('productionItems', [
            'items' => $items,
            'title' => $title,
            'count' => 1,
            ]);
    }

    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {

        // Validate and update the payment type in the database
        $record = OrderItemList::findOrFail($id);

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
    public function destroy(Request $request, $id): JsonResponse
    {
        $record = OrderItemList::findOrFail($id);
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Error deleting the record'], 500);
    }

    public function updateIsDoneStatus(Request $request, $id)
    {
        $orderItem = OrderItemList::find($id);
        if ($orderItem->is_done == false) {
            $newIsDoneValue = true; // If it's 0, set it to 1
        } else {
            $newIsDoneValue = false; // If it's 1, set it to 0
        }
        $orderItem->update(['is_done' => $newIsDoneValue]);
    }

    // SUM colors
    public static function productColors() {
        $items = OrderItemList::select('product_id', \DB::raw('SUM(amount) as amount'))
            ->whereHas('order', function ($query) {
                $query->whereNull('date_sent')->whereNull('date_cancelled');
            })
            ->groupBy(['product_id'])
            ->get();
            $title = "Količine za izradu - po proizvodu";
    }

    public static function sumOrderItemList($id) {
        return ( OrderItemList::where( 'order_id', $id )->sum( \DB::raw('amount * price * ( ( 100 - discount ) / 100 )' ) ) );
    }

    public static function sumSingleItem($id) {
        return number_format( ( OrderItemList::where( 'id', $id )->sum( \DB::raw('amount * price * ( ( 100 - discount ) / 100 )' ) ) ), 2, ',');
    }
}
