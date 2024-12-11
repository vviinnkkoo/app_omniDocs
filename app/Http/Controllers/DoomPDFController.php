<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Order;
use App\Models\OrderItemList;
use App\Models\DeliveryService;
use App\Models\PrintLabel;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DoomPDFController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct(OrderItemListController $orderItemListController, Omnicontrol $omnicontrol)
    {
        $this->middleware('auth');
        $this->orderItemListController = $orderItemListController;
        $this->omnicontrol = $omnicontrol;
    }



    protected $orderItemListController;
    protected $omnicontrol;
    


    public function invoice($id) {

        $receipt = Receipt::where('id', $id)->firstOrFail();
        $order = Order::where('id', $receipt->order_id)->firstOrFail();
        $orderItemList = OrderItemList::where('order_id', $receipt->order_id)->get();
        $deliveryService = DeliveryService::where('id', $order->delivery_service_id)->firstOrFail();

        $hp_cod_modifier = $this->omnicontrol->hpCodModifierCheck($order->id); // only for company with id "1"
        $subtotal = $this->orderItemListController->sumOrderItemList($order->id);

        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        $deliveryCost = $deliveryCost - $hp_cod_modifier;
        $deliveryCostFormated = number_format($deliveryCost, 2, ',', '.');

        $total = number_format(($subtotal + $deliveryCost), 2, ',', '.');
        
        $date = date("dmY-Gis"); // Date for name

        $pdf = Pdf::loadView('pdf.invoice', [
            'receipt' => $receipt,
            'order' => $order,
            'orderItemList' => $orderItemList,
            'deliveryService' => $deliveryService,
            'total' => $total,
            'subtotal' => $subtotal,
            'deliveryCost' => $deliveryCostFormated
        ]);

        return $pdf->stream('račun-' . $receipt->year . '-' . $receipt->number . '-1-1-' . $date . '.pdf');

    }



    public function generalDocs($id, $mode) {

        $order = Order::where('id', $id)->firstOrFail();
        $orderItemList = OrderItemList::where('order_id', $id)->get();
        $deliveryService = DeliveryService::where('id', $order->delivery_service_id)->firstOrFail();

        $hp_cod_modifier = $this->omnicontrol->hpCodModifierCheck($order->id); // only for company with id "1"
        $subtotal = $this->orderItemListController->sumOrderItemList($order->id);

        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        $deliveryCost = $deliveryCost - $hp_cod_modifier;
        $deliveryCostFormated = number_format($deliveryCost, 2, ',', '.');

        $total = number_format(($subtotal + $deliveryCost), 2, ',', '.');


        $date = date("dmY-Gis"); // Date for name 

        $pdf = Pdf::loadView('pdf.dispatch', [
            'order' => $order,
            'orderItemList' => $orderItemList,
            'deliveryService' => $deliveryService,
            'total' => $total,
            'subtotal' => $subtotal,
            'deliveryCost' => $deliveryCostFormated
        ]);

        return $pdf->stream('otpremnica-' . $id . '-' . $date . '.pdf');                
    }



    public function shippingLabels() {
        $shippingLabels = PrintLabel::where('label_type', 'shipping')->get();
        
        $pdf = Pdf::loadView('pdf.shippingLabels', [
            'shippingLabels' => $shippingLabels
        ]);
     
        return $pdf->stream();
    }



    public function p10mLabels($id) {

        $order = Order::where('id', $id)->firstOrFail();;
        $customPaper = array(0,0,396.85,563.15);
        
        $pdf = new Pdf();
        
        $pdf = Pdf::loadView('pdf.p10mLabels', [
             'order' => $order
         ]);

         $pdf->setPaper($customPaper);

        
     
        return $pdf->stream();
    }



    public static function labelItemTotal($order_id, $omnicontrol, $orderItemListController) {

        $receipt = Receipt::where('order_id', $order_id)->firstOrFail();
        $order = Order::where('id', $order_id)->firstOrFail();
        $deliveryService = DeliveryService::where('id', Order::find($order_id)->delivery_service_id)->firstOrFail();

        $hp_cod_modifier = $omnicontrol->hpCodModifierCheck($order_id); // only for company with id "1"
        $subtotal = $orderItemListController->sumOrderItemList($order_id);

        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        $deliveryCost = $deliveryCost - $hp_cod_modifier;
        
        return number_format(($subtotal + $deliveryCost), 2, ',', '.');
    }

}
