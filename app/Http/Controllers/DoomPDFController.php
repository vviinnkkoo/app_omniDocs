<?php

namespace App\Http\Controllers;

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

    private function calculateTotal($order_id)
    {
        $subtotal = GlobalService::calculateReceiptSubtotal($order_id);
        $deliveryService = DeliveryService::where('id', Order::find($order_id)->delivery_service_id)->firstOrFail();
        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        return [
            'subtotal' => $subtotal,
            'deliveryCost' => $deliveryCost,
            'total' => number_format(($subtotal + $deliveryCost), 2, ',', '.'),
            'deliveryCostFormated' => number_format($deliveryCost, 2, ',', '.')
        ];
    }

    public function invoice($id)
    {
        $receipt = Receipt::where('id', $id)->firstOrFail();
        $order = Order::where('id', $receipt->order_id)->firstOrFail();
        $orderItemList = OrderItemList::where('order_id', $receipt->order_id)->get();
        $subtotal = GlobalService::calculateReceiptSubotal($order->id);
        $total = GlobalService::calculateReceiptTotal($order->id);
        $deliveryCost = Order::find($order->id)->deliveryService->default_cost;
        $currentDateTime = date("dmY-Gis");

        $pdf = Pdf::loadView('pdf.invoice', [
            'receipt' => $receipt,
            'order' => $order,
            'orderItemList' => $orderItemList,
            'deliveryService' => $order->deliveryService,            
            'subtotal' => number_format($subtotal, 2, ',', '.'),
            'total' => number_format($total, 2, ',', '.'),
            'deliveryCost' => number_format($deliveryCost, 2, ',', '.')
        ]);

        return $pdf->stream('račun-' . $receipt->year . '-' . $receipt->number . '-1-1-' . $currentDateTime . '.pdf');
    }

    public function documents($mode, $id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        $orderItemList = OrderItemList::where('order_id', $id)->get();
        $totals = $this->calculateTotal($order->id);
        $date = date("dmY-Gis");

        $view = '';
        $filename = '';

        switch ($mode) {
            case 'otpremnica':
                $view = 'pdf.dispatch';
                $filename = 'otpremnica-' . $id . '-' . $date . '.pdf';
                break;
            case 'ponuda':
                $view = 'pdf.quotation';
                $filename = 'ponuda-' . $id . '-' . $date . '.pdf';
                break;
            default:
                return redirect('/');
        }

        $pdf = Pdf::loadView($view, [
            'order' => $order,
            'orderItemList' => $orderItemList,
            'deliveryService' => $order->deliveryService,
            'total' => $totals['total'],
            'subtotal' => $totals['subtotal'],
            'deliveryCost' => $totals['deliveryCostFormated']
        ]);

        return $pdf->stream($filename);
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