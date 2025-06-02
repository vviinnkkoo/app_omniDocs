<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderItemList;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class OrderItemListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function add (Request $request, $id) {
        $validator = Validator::make($request->all(), [
        'product_id' => 'required',
        'amount' => 'required',
        'color_id' => 'required',
        'price' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect()
                    ->back()
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
    
        return redirect()->back()->with('success', 'Proizvod je uspješno dodan.');
    }

    public function showProductionItems($mode) {

        $pendingOrders = fn ($query) => $query->whereNull('date_sent')->whereNull('date_cancelled');
        $sentOrders    = fn ($query) => $query->whereNotNull('date_sent')->whereNull('date_cancelled');

        $baseQuery = OrderItemList::query()->with(['product', 'color', 'order']);

        switch ($mode) {
            case 'u-izradi':
                $items = $baseQuery->whereHas('order', $pendingOrders)->get();
                $title = "Svi proizvodi za izradu";
                break;

            case 'grupirano-prema-boji':
                $items = $baseQuery->selectRaw('product_id, color_id, SUM(amount) as amount')
                    ->whereHas('order', $pendingOrders)
                    ->groupBy('product_id', 'color_id')
                    ->get();
                $title = "Količine za izradu - po boji";
                break;

            case 'grupirano-u-izradi':
                $items = $baseQuery->selectRaw('product_id, SUM(amount) as amount')
                    ->whereHas('order', $pendingOrders)
                    ->groupBy('product_id')
                    ->get();
                $title = "Količine za izradu - po proizvodu";
                break;

            case 'izradeno':
                $items = $baseQuery->selectRaw('product_id, SUM(amount) as amount')
                    ->whereHas('order', $sentOrders)
                    ->groupBy('product_id')
                    ->get();
                $title = "Izrađene količine - po proizvodu";
                break;

            default:
                abort(404);
        }

        return view('productionItems', [
            'items' => $items,
            'title' => $title,
            'count' => 1,
        ]);

    }

    public function update(Request $request, $id)
    {
        $record = OrderItemList::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $record->$field = $newValue;
        $record->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

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
        $orderItem = OrderItemList::findOrFail($id);
        $orderItem->update(['is_done' => !$orderItem->is_done]);
    }

    public function updateNoteOnInvoiceStatus(Request $request, $id)
    {
        $orderItem = OrderItemList::findOrFail($id);
        $orderItem->update(['note_on_invoice' => !$orderItem->note_on_invoice]);
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
}
