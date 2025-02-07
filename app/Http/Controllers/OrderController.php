<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Source;
use App\Models\DeliveryService;
use App\Models\DeliveryCompany;
use App\Models\PaymentType;
use App\Models\Country;
use App\Models\OrderItemList;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Color;
use App\Models\OrderNote;
use App\Models\Receipt;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;


class OrderController extends Controller
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
    
    // GET function for displaying purposes
    //

        public function showOrders(Request $request, $mode) {

        $search = $request->input('search');
        $query = Order::query();

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('tracking_code', 'like', "%{$search}%")
                      ->orWhere('delivery_postal', 'like', "%{$search}%")
                      ->orWhere('delivery_city', 'like', "%{$search}%")
                      ->orWhereHas('paymentType', function($query) use ($search) {
                        $query->where('type_name', 'like', "%{$search}%");
                    })
                      ->orWhereHas('deliveryService.deliveryCompany', function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                      ->orWhereHas('customer', function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Show ALL orders
        if ($mode === '1') {
            $orders = $query->orderBy('id')
                            ->paginate(25);
        // Show SENT orders
        } elseif ($mode === '2') {
            $orders = $query->whereNotNull('date_sent')
                            ->whereNull('date_delivered')
                            ->whereNull('date_cancelled')
                            ->orderBy('id')
                            ->paginate(25);
        // Show UNFINISHED orders
        } elseif ($mode === '3') {
            $orders = $query->whereNull('date_sent')
                            ->whereNull('date_cancelled')
                            ->orderBy('id')
                            ->paginate(25);
        // Show CANCELLED orders
        } elseif ($mode === '4') {
            $orders = $query->whereNotNull('date_cancelled')
                            ->orderBy('id')
                            ->paginate(25);
        } else {
            return;
        }

        $customers = Customer::get()
                        ->sortBy('id');

        $sources = Source::get()
                        ->sortBy('id');

        $deliveryServices = DeliveryService::where('in_use', true)
                        ->get()
                        ->sortBy('name');

        $deliveryCompanies = DeliveryCompany::whereNot('id', 1)
                        ->whereHas('deliveryService')
                        ->get()
                        ->sortBy('id');

        $paymentTypes = PaymentType::get()
                        ->sortBy('id');

        $countries = Country::get()
                        ->sortBy('id');
        
        return view('orders', [
            'orders' => $orders,
            'customers' => $customers,
            'sources' => $sources,
            'deliveryServices' => $deliveryServices,
            'deliveryCompanies' => $deliveryCompanies,
            'paymentTypes' => $paymentTypes,            
            'countries' => $countries
            ]);
    }




    public function edit($order_id) {

        $order = Order::where('id', $order_id)
                        ->firstOrFail();

        $customers = Customer::get()
                        ->sortBy('id');

        $sources = Source::get()
                        ->sortBy('id');

        $deliveryServices = DeliveryService::get()
                        ->sortBy('id');

        $deliveryCompanies = DeliveryCompany::whereNot('id', 1)
                        ->whereHas('deliveryService')
                        ->get()
                        ->sortBy('id');

        $paymentTypes = PaymentType::get()
                        ->sortBy('id');

        $countries = Country::get()
                        ->sortBy('id');

        $productList = OrderItemList::where('order_id', $order_id)
                        ->get();

        $orderNotes = OrderNote::where('order_id', $order_id)
                        ->get();

        $products = Product::get()
                        ->sortBy('name');

        $productTypes = ProductType::get()
                        ->sortBy('id');

        $colors = Color::get()
                        ->sortBy('id');

        $orderSum = $this->orderItemListController
                        ->sumOrderItemList($order_id);

        $deliveryService = DeliveryService::where('id', $order->delivery_service_id)
                        ->firstOrFail();

        $latest = (Receipt::where('year', date('Y'))
                        ->orderBy('number', 'desc')
                        ->limit(1)
                        ->value('number')) + 1;

        $hp_cod_modifier = $this->omnicontrol
                        ->hpCodModifierCheck($order->id); // only for company with id "1"

        $orderSum_converted = str_replace(',', '.', $orderSum);
        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        $deliveryCost = $deliveryCost - $hp_cod_modifier;

        $orderTotal = number_format(($orderSum_converted + $deliveryCost), 2, ',');
        $orderSum = number_format(($orderSum), 2, ',');
        
        return view('orders-edit', [
            'order' => $order,
            'customers' => $customers,
            'sources' => $sources,
            'deliveryServices' => $deliveryServices,
            'deliveryCompanies' => $deliveryCompanies,
            'paymentTypes' => $paymentTypes,
            'countries' => $countries,
            'productList' => $productList,
            'orderNotes' => $orderNotes,
            'products' => $products,
            'productTypes' => $productTypes,
            'colors' => $colors,
            'orderSum' => $orderSum,
            'deliveryCost' => $deliveryCost,
            'orderTotal' => $orderTotal,
            'latest' => $latest
            ]);
    }




    public static function SumOrderExpense($order_id) {

        $expenses = Expense::where('order_id', $order_id)
                            ->sum('amount');
        
        return number_format(($expenses), 2, ',');
    }





    public function save (Request $request) {

        $validator = Validator::make($request->all(), [
        'date_ordered' => 'required',
        'date_deadline' => 'required',
        'customer_id' => 'required',
        'source_id' => 'required',
        'delivery_service_id' => 'required',
        'payment_type_id' => 'required',
        ]);
            if ($validator->fails()) {
                return redirect('/narudzbe')
                    ->withInput()
                    ->withErrors($validator);
            }

        $statement=DB::select("SHOW TABLE STATUS LIKE 'orders'");
        $next_id=$statement[0]->Auto_increment;

        $order = new Order;
        $order->date_ordered = $request->date_ordered;
        $order->date_deadline = $request->date_deadline;        
        $order->customer_id = $request->customer_id;
        $order->source_id = $request->source_id;
        $order->delivery_service_id = $request->delivery_service_id;
        $order->payment_type_id = $request->payment_type_id;
        $order->delivery_address = Customer::find($request->customer_id)->address . " " . Customer::find($request->customer_id)->house_number;
        $order->delivery_city = Customer::find($request->customer_id)->city;
        $order->delivery_country_id = Customer::find($request->customer_id)->country_id;
        $order->delivery_postal = Customer::find($request->customer_id)->postal;
        $order->delivery_phone = Customer::find($request->customer_id)->phone;
        $order->delivery_email = Customer::find($request->customer_id)->email;
        $order->save();
    
        return redirect('/uredi-narudzbu/' . $next_id);
    }




    public function update(Request $request, $id) {

        $order = Order::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $order->$field = $newValue;

        $order->save();

        return response()->json(['message' => 'Delivery service updated successfully']);
    }




    public function destroy(Request $request, $id): JsonResponse {

        $record = Order::findOrFail($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }

}
