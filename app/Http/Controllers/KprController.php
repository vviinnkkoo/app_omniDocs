<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Kpr;
use App\Models\KprItemList;
use App\Models\Receipt;
use App\Models\PaymentType;

use App\Services\GlobalService;

use App\Traits\RecordManagement;

class KprController extends Controller
{    
    use RecordManagement;
    protected $modelClass = Kpr::class;

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

        $kprs = Kpr::search($search,
                ['payer', 'amount', 'origin', 'date', 'info'],
                ['paymentType' => ['name']]
            )
            ->whereYear('date', $year)
            ->orderBy('date')
            ->paginate(25)
            ->through(function ($item) {
                return tap($item, function ($i) {
                    $i->exists = KprItemList::where('kpr_id', $i->id)->exists();
                    $i->date = Carbon::parse($i->date)->format('d.m.Y');
                    $i->payment_type_name = $i->paymentType->name ?? '';
                    $i->formated_amount = number_format($i->amount, 2, ',', '.');
                    $i->formated_receipts_total = number_format(GlobalService::sumAllReciepesFromKpr($i->id), 2, ',', '.');
                });
            });

        $paymentMethods = PaymentType::all();

        return view('pages.kpr.index', compact('kprs', 'year', 'paymentMethods', 'search'));
    }

    public function show($id)
    {
        $kprInstance = Kpr::with([
            'kprItemList.receipt.order.customer'
        ])->findOrFail($id);

        $invoiceList = $kprInstance->kprItemList;
        $year = Carbon::parse($kprInstance->date)->year;

        // ID-jevi računa koji su već dodani u KPR
        $existingReceiptIds = KprItemList::whereNotNull('receipt_id')
            ->distinct()
            ->pluck('receipt_id')
            ->toArray();

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

        $totals = DB::table('order_item_lists')
            ->select(
                'order_id',
                DB::raw('SUM(' . GlobalService::itemSumFormula() . ') as total')
            )
            ->whereIn('order_id', $orderIds)
            ->groupBy('order_id')
            ->pluck('total', 'order_id');

        // Formiranje opcija računa
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
            $item->receiptsTotal = number_format(($totals[$item->receipt->order_id] ?? 0) + ($item->receipt->order->deliveryService->default_cost ?? 0), 2, ',', '.');
            $item->receiptID = $item->receipt->id;
        }

        return view('pages.kpr.show', compact(
            'kprInstance',
            'year',
            'invoiceList',
            'receipts',
            'receiptOptions'
        ));
    }    

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'payer' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'info' => 'nullable|string|max:255',
            'payment_type_id' => 'nullable|integer|exists:payment_types,id',
        ]);

        return $this->createRecord($data, 'Zapis u knjizi prometa uspješno dodan!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, [
            'payer', 'amount', 'origin', 'date', 'info', 'payment_type_id'
        ]);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}