<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Kpr;
use App\Models\KprItemList;
use App\Models\Invoice;
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
                    $i->formated_invoices_total = number_format(GlobalService::sumAllInvoicesFromKpr($i->id), 2, ',', '.');
                });
            });

        $paymentMethods = PaymentType::all();

        return view('pages.kpr.index', compact('kprs', 'year', 'paymentMethods', 'search'));
    }

    public function show($id)
    {
        $kprInstance = Kpr::with([
            'kprItemList.invoice.order.customer'
        ])->findOrFail($id);

        $invoiceList = $kprInstance->kprItemList;
        $year = Carbon::parse($kprInstance->date)->year;

        // ID-jevi računa koji su već dodani u KPR
        $existingInvoiceIds = KprItemList::whereNotNull('invoice_id')
            ->distinct()
            ->pluck('invoice_id')
            ->toArray();

        // Dohvat recepata koji nisu dodani u KPR
        $invoices = Invoice::with('order.customer')
            ->where('year', $year)
            ->where('is_cancelled', 0)
            ->whereNotIn('id', $existingInvoiceIds)
            ->orderBy('number')
            ->get();

        // Batch dohvat total-a za sve order_id (iz recepata i invoiceList)
        $orderIds = $invoices->pluck('order_id')->merge(
            $invoiceList->pluck('invoice.order_id')->filter()
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
        $invoiceOptions = [];
        foreach ($invoices as $invoice) {
            $invoiceOptions[] = [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'customerName' => $invoice->order->customer->name,
                'total' => number_format($totals[$invoice->order_id] ?? 0, 2, ',', '.'),
                'trackingCode' => $invoice->order->tracking_code
            ];
        }

        $invoiceList = $kprInstance->kprItemList->map(function ($item) use ($totals) {
            $item->invoiceNumber = $item->invoice->number;
            $item->customerName = $item->invoice->order->customer->name;
            $item->orderId = $item->invoice->order_id;
            $item->trackingCode = $item->invoice->order->tracking_code;
            $item->invoiceDate = Carbon::parse($item->invoice->created_at)->format('d.m.Y - H:i:s');
            $item->invoicesTotal = number_format(
                ($totals[$item->invoice->order_id] ?? 0)
                + ($item->invoice->order->deliveryService->default_cost ?? 0),
                2, ',', '.'
            );
            $item->invoiceID = $item->invoice->id;

            return $item;
        });

        return view('pages.kpr.show', compact(
            'kprInstance',
            'year',
            'invoiceList',
            'invoices',
            'invoiceOptions'
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