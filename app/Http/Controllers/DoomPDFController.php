<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Receipt;
use App\Models\Order;
use App\Models\OrderItemList;
use App\Models\DeliveryService;
use App\Models\PrintLabel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\GlobalService;

class DoomPDFController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generateDocument($mode, $id)
    {
        $methods = [
            'otpremnica' => 'generateDispatchNote',
            'ponuda' => 'generateQuotation',
            'racun' => 'generateInvoice'
        ];
    
        if (!isset($methods[$mode])) {
            return redirect('/')->with('error', 'Vrsta dokumenta nije definirana! Pokušajte ponovno otvoriti poveznicu.');
        }
    
        return $this->{$methods[$mode]}($mode, $id);
    }

    private function generateInvoice($mode, $invoiceID)
    {
        $invoice = Receipt::where('id', $invoiceID)->firstOrFail();
        $orderID = $invoice->order->id;
        $invoiceData = [
            'number' => $invoice->number,
            'date' => Carbon::parse($invoice->created_at)->format('d.m.Y'),
            'time' => Carbon::parse($invoice->created_at)->format('H:i'),            
            'eta' => Carbon::parse($invoice->created_at)->addDays(14)->format('d.m.Y'),
        ];
        [$order, $orderData, $orderItemList] = $this->getOrderData($orderID, true);
        [$view, $filename] = $this->getTemplate($mode, $orderID, $invoice->number);
        
        return Pdf::loadView($view, compact('invoiceData', 'order', 'orderData', 'orderItemList'))
            ->stream($filename);

    }

    public function invoice($id)
    {
        $invoice = Receipt::where('id', $id)->firstOrFail();
        $order = Order::where('id', $invoice->order_id)->firstOrFail();
        $orderItemList = OrderItemList::where('order_id', $invoice->order_id)->get();
        $subtotal = GlobalService::sumWholeOrder($order->id);
        $total = GlobalService::calculateReceiptTotal($order->id);
        $deliveryCost = $order->deliveryService->default_cost;
        $currentDateTime = date("dmY-Gis");

        foreach ($orderItemList as $item) {            
            $item->productName = $item->product->name;
            $item->color = $item->color->name;
            $item->unit = $item->product->unit;
            $item->itemTotal = GlobalService::sumSingleOrderItem($item->id);
        }

        $pdf = Pdf::loadView('pdf.invoice', [
            'receipt' => $invoice,
            'order' => $order,
            'customer' => $order->customer->name,
            'orderItemList' => $orderItemList,
            'deliveryService' => $order->deliveryService->name,
            'deliveryCompany' => $order->deliveryService->deliveryCompany->name,
            'paymentType' => $order->paymentType->name,
            'subtotal' => number_format($subtotal, 2, ',', '.'),
            'total' => number_format($total, 2, ',', '.'),
            'deliveryCost' => number_format($deliveryCost, 2, ',', '.')
        ]);

        return $pdf->stream('račun-' . $invoice->year . '-' . $invoice->number . '-1-1-' . $currentDateTime . '.pdf');
    }

    public function documents($mode, $id)
    {
        [$order, $orderData, $orderItemList] = $this->getOrderData($orderID);

        $templates = [
            'otpremnica' => ['pdf.dispatch', "otpremnica-{$id}-{$currentDateTime}.pdf"],
            'ponuda' => ['pdf.quotation', "ponuda-{$id}-{$currentDateTime}.pdf"],
            'racun' => ['pdf.invoice', "racun-{$id}-{$currentDateTime}.pdf"]
        ];

        if (!isset($templates[$mode])) {
            return redirect('/');
        }

        [$view, $filename] = $templates[$mode];

        return Pdf::loadView($view, compact('receipt', 'order', 'orderItemList', 'orderData', 'total', 'deliveryCost'))
            ->stream($filename);
    }

    /*public function shippingLabels()
    {
        $shippingLabels = PrintLabel::where('label_type', 'shipping')->get();

        $pdf = Pdf::loadView('pdf.shippingLabels', [
            'shippingLabels' => $shippingLabels
        ]);

        return $pdf->stream();
    }

    public function p10mLabels($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        $customPaper = [0, 0, 396.85, 563.15];

        $pdf = Pdf::loadView('pdf.p10mLabels', [
            'order' => $order
        ]);

        $pdf->setPaper($customPaper);

        return $pdf->stream();
    }

    public static function labelItemTotal($order_id)
    {
        return GlobalService::calculateReceiptTotal($order_id);
    }*/

    private function getOrderData($id, $includeItems = true)
    {
        $order = Order::with([
            'paymentType:id,name',
            'customer:id,name,oib',
            'country:id,name',
            'deliveryService:id,name,default_cost',
            'deliveryService.deliveryCompany:id,name'
        ])->findOrFail($id);

        $orderData = [
            'id' => $order->id,
            'paymentTypeName' => $order->paymentType->name ?? '',
            'customerName' => $order->customer->name ?? '',
            'customerOib' => $order->customer->oib ?? '',
            'countryName' => $order->country->name ?? '',
            'dateSent' => Carbon::parse($order->date_sent)->format('d.m.Y') ?? '',
            'dateOrdered' => Carbon::parse($order->created_at)->format('d.m.Y') ?? '',
            'timeOrdered' => Carbon::parse($order->created_at)->format('H:i') ?? '',
            'eta' => Carbon::parse($order->created_at)->addDays(14)->format('d.m.Y') ?? '',
            'deliveryAddress' => $order->delivery_address ?? '',
            'deliveryCity' => $order->delivery_city ?? '',
            'deliveryPostal' => $order->delivery_postal ?? '',
            'deliveryServiceName' => $order->deliveryService->name ?? '',
            'deliveryCompanyName' => $order->deliveryService->deliveryCompany->name ?? '',
            'total' => GlobalService::calculateReceiptTotal($id),
            'deliveryCost' => $order->deliveryService->default_cost ?? 0
        ];

        if ($includeItems) {
            $orderItemList = OrderItemList::with(['product:id,name,unit', 'color:id,name'])
                ->where('order_id', $id)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'productID' => $item->product->id,
                        'productName' => $item->product->name,
                        'productUnit' => $item->product->unit,
                        'colorID' => $item->color->id,
                        'colorName' => $item->color->name,
                        'price' => $item->price,
                        'amount' => $item->amount,
                        'discount' => $item->discount,
                        'itemTotal' => GlobalService::sumSingleOrderItem($item->id)
                    ];
                });
        } else {
            $orderItemList = null;
        }

        return [$order, $orderData, $orderItemList];
    }

    private function getTemplate($mode, $id, $invoiceNumber = null)
    {
        $currentDateTime = $this->getCurrentDateTime();

        $templates = [
            'otpremnica' => ['pdf.dispatch', "otpremnica-{$id}-{$currentDateTime}.pdf"],
            'ponuda' => ['pdf.quotation', "ponuda-{$id}-{$currentDateTime}.pdf"],
            'racun' => ['pdf.invoice', "racun-{$invoiceNumber}-{$currentDateTime}.pdf"]
        ];

        return $templates[$mode] ?? null;
    }

    private function getCurrentDateTime()
    {
        return now()->format('dmY-Gis');
    }
}