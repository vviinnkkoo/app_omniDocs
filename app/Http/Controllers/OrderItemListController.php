<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Order;
use App\Models\OrderItemList;
use App\Traits\RecordManagement;

class OrderItemListController extends Controller
{    
    use RecordManagement;
    protected $modelClass = App\Models\OrderItemList::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            $decryptedOrderId = (int) Crypt::decryptString($request->input('order_id'));
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Neispravan ID narudžbe.');
        }

        if ($decryptedOrderId <= 0 || !Order::where('id', $decryptedOrderId)->exists()) {
            return redirect()->back()->with('error', 'Narudžba ne postoji ili je unesena kriva oznaka narudžbe.');
        }

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id'   => 'required|exists:colors,id',
            'amount'     => 'required',
            'price'      => 'required',
            'note'       => 'nullable|string',
            'discount'   => 'nullable|numeric|min:0|max:100',
        ]);

        $data['order_id'] = $decryptedOrderId;


        return $this->createRecord($data, 'Proizvod je uspješno dodan!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, [
            'product_id', 'amount', 'color_id', 'price', 'note', 'discount'
        ]);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Custom methods used by this controller
    |--------------------------------------------------------------------------------------------
    */
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

    public static function productColors() {
        $items = OrderItemList::select('product_id', \DB::raw('SUM(amount) as amount'))
            ->whereHas('order', function ($query) {
                $query->whereNull('date_sent')->whereNull('date_cancelled');
            })
            ->groupBy(['product_id'])
            ->get();
        $title = "Količine za izradu - po proizvodu";
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
            'title' => $title
        ]);

    }
}
