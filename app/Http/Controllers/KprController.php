<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Kpr;
use App\Models\KprItemList;
use App\Models\Receipt;
use App\Models\PaymentType;
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
        $paymentMethods = PaymentType::all();

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

        // ID-jevi računa koji su već dodani u KPR
        $existingReceiptIds = $invoiceList->pluck('receipt_id')->filter()->toArray();

        // Dohvat recepata koji nisu dodani u KPR
        $receipts = Receipt::with('order.customer')
            ->where('year', $year)
            ->where('is_cancelled', 0)
            ->whereNotIn('id', $existingReceiptIds)
            ->orderBy('number')
            ->get();

        // Batch dohvat total-a za sve order_id (iz recepata i invoiceList)
        $orderIds = $receipts->pluck('order_id')->merge(
            $invoiceList->pluck('receipt.order_id')->filter()
        )->unique();

        $totals = DB::table('order_item_list')
            ->select('order_id', DB::raw('SUM(price * amount) as total'))
            ->whereIn('order_id', $orderIds)
            ->groupBy('order_id')
            ->pluck('total', 'order_id');

        $count = 1;

        // Formiranje opcija recepata
        $receiptOptions = [];
        foreach ($receipts as $receipt) {
            $receiptOptions[] = [
                'id' => $receipt->id,
                'number' => $receipt->number,
                'customerName' => $receipt->order->customer->name,
                'total' => number_format($totals[$receipt->order_id] ?? 0, 2, ',', '.'),
                'trackingCode' => $receipt->order->tracking_code
            ];
        }

        // Obogaćivanje invoiceList sa podacima za view
        foreach ($invoiceList as $item) {
            $item->receiptNumber = $item->receipt->number;
            $item->customerName = $item->receipt->order->customer->name;
            $item->orderId = $item->receipt->order_id;
            $item->trackingCode = $item->receipt->order->tracking_code;
            $item->receiptDate = Carbon::parse($item->receipt->created_at)->format('d.m.Y - H:i:s');
            $item->receiptsTotal = number_format($totals[$item->receipt->order_id] ?? 0, 2, ',', '.');
            $item->receiptID = $item->receipt->id;
        }

        return view('pages.kpr.show', compact(
            'kprInstance',
            'year',
            'invoiceList',
            'receipts',
            'receiptOptions',
            'count'
        ));
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