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

class InvoiceController extends Controller
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
            'business_space_id' => 'required|exists:business_spaces,id',
            'business_device_id' => 'required|exists:business_devices,id',
            'year' => 'required|integer',
            'type' => 'required|in:invoice,credit,advance,cancellation',
            'number' => 'required|integer',
            'customer_name' => 'nullable|string|max:255',
            'customer_oib' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:255',
            'customer_postal' => 'nullable|string|max:255',
            'customer_city' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'customer_email' => 'nullable|string|max:255',
            'issued_by' => 'required|string|max:255',
            'issued_at' => 'required|date',
            'due_at' => 'required|date'
        ]);

        $exists = Invoice::where('year', $data['year'])
                        ->where('business_space_id', $data['business_space_id'])
                        ->where('number', $data['number'])
                        ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Račun s brojem {$data['number']} već postoji u {$data['year']}. godini za odabrani poslovni prostor.");
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