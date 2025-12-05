<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\DeliveryService;
use App\Models\OrderItemList;
use App\Models\WorkYears;
use App\Models\KprItemList;

use App\Services\GlobalService;

use App\Traits\RecordManagement;

class InvoicecController extends Controller
{
    use RecordManagement;
    protected $modelClass = Invoice::class;

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

        $invoices = Invoice::search($search, 
            ['number', 'year', 'is_cancelled'], 
            ['order.customer' => ['name'], 'order.paymentType' => ['name']]
        )
        ->where('year', $year)
        ->with(['order.customer', 'order.paymentType', 'kprItem'])
        ->orderBy('number')
        ->paginate(25)
        ->through(function ($invoice) {
            $invoice->customerName = $invoice->order->customer->name ?? '';
            $invoice->paymentTypeName = $invoice->order->paymentType->name ?? '';
            $invoice->formatedDateCreatedAt = $invoice->created_at->format('d.m.Y - H:i:s');

            $total = GlobalService::calculateInvoiceTotal($invoice->order_id);
            if ($invoice->cancelled_invoice_id) {
                $total *= -1;
            }

            $invoice->totalAmount = number_format($total, 2, ',');

            return $invoice;
        });

        $orderIdsWithInvoices = Invoice::where('is_cancelled', 0)->pluck('order_id');

        $orders = Order::whereNotIn('id', $orderIdsWithInvoices)
            ->with('customer:id,name')
            ->get();

        $latest = GlobalService::getLatestInvoiceNumber($year);

        return view('pages.invoices.index', compact('invoices', 'orders', 'latest'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'year' => 'required|integer',
            'number' => 'required|integer'
        ]);

        $exists = Invoice::where('year', $data['year'])
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
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['is_cancelled' => !$invoice->is_cancelled]);
    }

    public function getLatestNumber($year)
    {
        return response()->json(['latest' => GlobalService::getLatestInvoiceNumber($year)]);
    }

}