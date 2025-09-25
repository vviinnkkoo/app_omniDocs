<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Kpr;
use App\Models\KprItemList;
use App\Models\Receipt;
use App\Models\KprPaymentType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Services\GlobalService;

class KprController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request, $year = null)
    {
        $search = $request->input('search');
        $query = Kpr::query();

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('payer', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('origin', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhere('info', 'like', "%{$search}%");
            });
        }

        if (!is_null($year)) {
            $query->whereYear('date', $year);
        } else {
            $year = Carbon::now()->year;
        }

        $kprs = $query->orderBy('date')->paginate(25);
        $paymentMethods = KprPaymentType::all();

        foreach ($kprs as $index => $item) {
            $item->exists = KprItemList::where('kpr_id', $item->id)->exists();
            $item->date = Carbon::parse($item->date)->format('d.m.Y');
            $item->paymentTypeName = $item->paymentType->name;
            $item->receiptsTotal = GlobalService::sumAllReciepesFromKpr($item->id);
            $item->index = $kprs->firstItem() + $index;
        }

        return view('pages.kpr.index', compact('kprs', 'year', 'paymentMethods'));
    }



    public function show($id)
    {
        $kprInstance = Kpr::with([
            'kprItemList.receipt.order.customer'
        ])->findOrFail($id);
    
        $invoiceList = $kprInstance->kprItemList;
        $year = Carbon::parse($kprInstance->date)->year;
    
        $existingReceiptIds = KprItemList::where('kpr_id', $id)
            ->pluck('receipt_id')
            ->toArray();
    
        $receipts = Receipt::where('year', $year)
            ->where('is_cancelled', 0)
            ->whereNotIn('id', $existingReceiptIds)
            ->orderBy('number')
            ->get();

        $count = 1;
    
        $receiptOptions = [];
        
        foreach ($receipts as $receipt) {
            $receiptOptions[] = [
                'id' => $receipt->id,
                'number' => $receipt->number,
                'customerName' => $receipt->order->customer->name,
                'total' => number_format(GlobalService::calculateReceiptTotal($receipt->order_id), 2, ',', '.'),
                'trackingCode' => $receipt->order->tracking_code
            ];
        }
    
        foreach ($invoiceList as $item) {
            $item->receiptNumber = $item->receipt->number;
            $item->customerName = $item->receipt->order->customer->name;
            $item->orderId = $item->receipt->order_id;
            $item->trackingCode = $item->receipt->order->tracking_code;
            $item->receiptDate = Carbon::parse($item->receipt->created_at)->format('d.m.Y - H:i:s');
            $item->receiptsTotal = number_format (GlobalService::calculateReceiptTotal($item->receipt->order_id), 2, ',', '.');
            $item->receiptID = $item->receipt->id;
        }
    
        return view('pages.kpr.show', compact('kprInstance', 'year', 'invoiceList', 'receipts', 'receiptOptions', 'count'));
    }
    

    public function store(Request $request)
    {
        $date = Carbon::parse($request->date);

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/knjiga-prometa/' . $date->year)
                ->withInput()
                ->withErrors($validator);
        }

        Kpr::create($request->only(['payer', 'amount', 'origin', 'date', 'info', 'payment_type_id']));

        return redirect()->back();
    }


    public function update(Request $request, $id)
    {
        $record = Kpr::findOrFail($id);
        $record->update([$request->input('field') => $request->input('newValue')]);

        return response()->json(['message' => 'Payment type updated successfully']);
    }


    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Kpr::findOrFail($id);

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}