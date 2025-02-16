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
use App\Models\WorkYears;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Services\GlobalService;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showOrders(Request $request, $mode)
    {
        $search = $request->input('search');
        $query = Order::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('tracking_code', 'like', "%{$search}%")
                    ->orWhere('delivery_postal', 'like', "%{$search}%")
                    ->orWhere('delivery_city', 'like', "%{$search}%")
                    ->orWhereHas('paymentType', function ($query) use ($search) {
                        $query->where('type_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('deliveryService.deliveryCompany', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        switch ($mode) {
            case '1':
                $orders = $query->orderBy('id')
                    ->paginate(25);
                break;
            case '2':
                $orders = $query->whereNotNull('date_sent')
                    ->whereNull('date_delivered')
                    ->whereNull('date_cancelled')
                    ->orderBy('id')
                    ->paginate(25);
                break;
            case '3':
                $orders = $query->whereNull('date_sent')
                    ->whereNull('date_cancelled')
                    ->orderBy('id')
                    ->paginate(25);
                break;
            case '4':
                $orders = $query->whereNotNull('date_cancelled')
                    ->orderBy('id')
                    ->paginate(25);
                break;
            default:
                return;
        }

        $customers = Customer::orderBy('id')->get();
        $sources = Source::orderBy('id')->get();
        $deliveryServices = DeliveryService::where('in_use', true)->orderBy('name')->get();
        $deliveryCompanies = DeliveryCompany::whereNot('id', 1)->whereHas('deliveryService')->orderBy('id')->get();
        $paymentTypes = PaymentType::orderBy('id')->get();
        $countries = Country::orderBy('id')->get();

        // Calculate the total amount for each item in the order
        foreach ($orders as $order) {
            $order->totalAmount = GlobalService::sumWholeOrder($order->id);
        }

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

    public function edit($order_id)
    {
        $order = Order::with(['customer', 'paymentType', 'source', 'deliveryService', 'country', 'orderItemList', 'orderNote'])->findOrFail($order_id);        
        $order->paymentTypeName = $order->paymentType->name;
        $order->countryName = $order->country->name;
        $sources = Source::orderBy('id')->get();
        $deliveryServices = DeliveryService::orderBy('id')->get();
        $deliveryCompanies = DeliveryCompany::whereHas('deliveryService')->orderBy('id')->get();
        $paymentTypes = PaymentType::orderBy('id')->get();
        $countries = Country::orderBy('id')->get();
        $products = Product::orderBy('name')->get();
        $productTypes = ProductType::orderBy('id')->get();
        $colors = Color::orderBy('id')->get();
        $latestReceiptNumber = GlobalService::getLatestReceiptNumber(date('Y'));
        $workYears = WorkYears::orderBy('year')->get();

        // Calculations for display
        $deliveryCost = $order->deliveryService->default_cost;
        $orderSubtotal = GlobalService::sumWholeOrder($order_id);
        $orderTotal = $orderSubtotal + $deliveryCost;

        return view('orders-edit', [
            'order' => $order,
            'sources' => $sources,
            'deliveryServices' => $deliveryServices,
            'deliveryCompanies' => $deliveryCompanies,
            'paymentTypes' => $paymentTypes,
            'countries' => $countries,
            'productList' => $order->orderItemList,
            'orderNotes' => $order->orderNote,
            'products' => $products,
            'productTypes' => $productTypes,
            'colors' => $colors,
            'orderSubtotal' => number_format($orderSubtotal, 2, ','),
            'deliveryCost' => number_format($deliveryCost, 2, ','),
            'orderTotal' => number_format(($orderTotal), 2, ','),
            'latestReceiptNumber' => $latestReceiptNumber,
            'workYears' => $workYears
        ]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_ordered' => 'required',
            'date_deadline' => 'required',
            'customer_id' => 'required',
            'source_id' => 'required',
            'delivery_service_id' => 'required',
            'payment_type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $statement = DB::select("SHOW TABLE STATUS LIKE 'orders'");
        $next_id = $statement[0]->Auto_increment;

        $customer = Customer::findOrFail($request->customer_id);

        $order = new Order;
        $order->date_ordered = $request->date_ordered;
        $order->date_deadline = $request->date_deadline;
        $order->customer_id = $request->customer_id;
        $order->source_id = $request->source_id;
        $order->delivery_service_id = $request->delivery_service_id;
        $order->payment_type_id = $request->payment_type_id;
        $order->delivery_address = "{$customer->address} {$customer->house_number}";
        $order->delivery_city = $customer->city;
        $order->delivery_country_id = $customer->country_id;
        $order->delivery_postal = $customer->postal;
        $order->delivery_phone = $customer->phone;
        $order->delivery_email = $customer->email;
        $order->save();

        return redirect('/uredi-narudzbu/' . $next_id);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $order->$field = $newValue;
        $order->save();

        return response()->json(['message' => 'Delivery service updated successfully']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Order::findOrFail($id);

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}