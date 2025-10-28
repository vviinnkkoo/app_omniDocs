<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Receipt;
use App\Models\Order;
use App\Models\DeliveryService;
use App\Models\OrderItemList;
use App\Models\WorkYears;
use App\Models\KprItemList;

use App\Services\GlobalService;

use App\Traits\RecordManagement;

class ReceiptController extends Controller
{
    use RecordManagement;
    protected $modelClass = Receipt::class;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function index(Request $request, $year = null)
    {
        $year = $year ?? now()->year;

        $search = $request->input('search');

        $receipts = Receipt::search($search, 
            ['number', 'year', 'is_cancelled'], 
            ['order.customer' => ['name'], 'order.paymentType' => ['name']]
        )
        ->where('year', $year)
        ->with(['order.customer', 'order.paymentType', 'kprItem'])
        ->orderBy('number')
        ->paginate(25)
        ->through(function ($receipt) {
            $receipt->customerName = $receipt->order->customer->name ?? '';
            $receipt->paymentTypeName = $receipt->order->paymentType->name ?? '';
            $receipt->formatedDateCreatedAt = $receipt->created_at->format('d.m.Y - H:i:s');

            $total = GlobalService::calculateReceiptTotal($receipt->order_id);
            if ($receipt->cancelled_receipt_id) {
                $total *= -1;
            }

            $receipt->totalAmount = number_format($total, 2, ',');

            return $receipt;
        });

        $orderIdsWithReceipts = Receipt::where('is_cancelled', 0)->pluck('order_id');

        $orders = Order::whereNotIn('id', $orderIdsWithReceipts)
            ->with('customer:id,name')
            ->get();

        $latest = GlobalService::getLatestReceiptNumber($year);

        return view('pages.receipts.index', compact('receipts', 'orders', 'latest'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'year' => 'required|integer',
            'number' => 'required|integer'
        ]);

        $exists = Receipt::where('year', $data['year'])
                        ->where('number', $data['number'])
                        ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Račun s brojem {$data['number']} već postoji u {$data['year']}. godini.");
        }

        return $this->createRecord(
            $data, "Račun broj {$data['number']} uspješno je dodan u {$data['year']}. godinu!"
        );
    }
    
    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['order_id', 'year', 'number']);
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
    public function updateIsCancelledStatus(Request $request, $id)
    {
        $receipt = Receipt::findOrFail($id);
        $receipt->update(['is_cancelled' => !$receipt->is_cancelled]);
    }

    public function getLatestNumber($year)
    {
        return response()->json(['latest' => GlobalService::getLatestReceiptNumber($year)]);
    }

}