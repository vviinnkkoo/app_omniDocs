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

    public function invoice($id)
    {
        $receipt = Receipt::where('id', $id)->firstOrFail();
        $order = Order::where('id', $receipt->order_id)->firstOrFail();
        $orderItemList = OrderItemList::where('order_id', $receipt->order_id)->get();
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
            'receipt' => $receipt,
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

        return $pdf->stream('račun-' . $receipt->year . '-' . $receipt->number . '-1-1-' . $currentDateTime . '.pdf');
    }

    public function documents($mode, $id)
    {
        $order = Order::with([
            'paymentType', 
            'customer', 
            'country', 
            'deliveryService.deliveryCompany'
        ])->findOrFail($id);

        $receipt = $mode === 'racun' 
            ? Receipt::where('order_id', $order->id)->firstOrFail() 
            : null;

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
                    'total' => GlobalService::sumSingleOrderItem($item->id),
                ];
            });

        $total = GlobalService::calculateReceiptTotal($order->id);
        $deliveryCost = $order->deliveryService->default_cost;
        $currentDateTime = now()->format('dmY-Gis');

        $orderData = [
            'paymentType' => $order->paymentType->name,
            'customerName' => $order->customer->name,
            'customerOib' => $order->customer->oib,
            'countryName' => $order->country->name,
            'deliveryServiceName' => $order->deliveryService->name,
            'deliveryCompanyName' => $order->deliveryService->deliveryCompany->name
        ];

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

    public function shippingLabels()
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

    public static function labelItemTotal($order_id, $omnicontrol, $orderItemListController)
    {
        $subtotal = $orderItemListController->sumOrderItemList($order_id);
        $deliveryService = DeliveryService::where('id', Order::find($order_id)->delivery_service_id)->firstOrFail();
        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);

        return number_format(($subtotal + $deliveryCost), 2, ',', '.');
    }
}