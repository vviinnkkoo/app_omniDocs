<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
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
use App\Models\Kpr;
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

    public function index(Request $request, $type)
    {
        $search = $request->input('search');
        $query = Order::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('tracking_code', 'like', "%{$search}%")
                    ->orWhere('delivery_postal', 'like', "%{$search}%")
                    ->orWhere('delivery_city', 'like', "%{$search}%")
                    ->orWhereHas('paymentType', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('deliveryService.deliveryCompany', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        switch ($type) {
            case 'sve':
                $orders = $query->orderBy('id')
                    ->paginate(25);
                break;
            case 'poslane':
                $orders = $query->whereNotNull('date_sent')
                    ->whereNull('date_delivered')
                    ->whereNull('date_cancelled')
                    ->orderBy('id')
                    ->paginate(25);
                break;
            case 'neodradene':
                $orders = $query->whereNull('date_sent')
                    ->whereNull('date_cancelled')
                    ->orderBy('id')
                    ->paginate(25);
                break;
            case 'otkazane':
                $orders = $query->whereNotNull('date_cancelled')
                    ->orderBy('id')
                    ->paginate(25);
                break;
            default:
                return;
        }

        // Get all receipts and KPRs for the orders
        $orderIds = $orders->pluck('id')->toArray();
        $receipts = Receipt::whereIn('order_id', $orderIds)
        ->where('is_cancelled', 0)
        ->pluck('id', 'order_id');

        // Učitaj samo ID-eve Kpr
        $kprs = Kpr::whereIn('receipt_id', $receipts->values())
        ->where('is_cancelled', 0)
        ->pluck('receipt_id', 'receipt_id');


        // Get all required data for the view
        $customers = Customer::orderBy('id')->get();
        $sources = Source::orderBy('id')->get();
        $deliveryServices = DeliveryService::where('in_use', true)->orderBy('name')->get();
        $deliveryCompanies = DeliveryCompany::whereHas('deliveryServices')->orderBy('id')->get();
        $paymentTypes = PaymentType::orderBy('id')->get();
        $countries = Country::orderBy('id')->get();
        $today = Carbon::now();

        // Update info for required fields
        foreach ($orders as $order) {
            $order->totalAmount = GlobalService::sumWholeOrder($order->id);
            $order->customeName = $order->customer->name;
            $order->dateOrdered = $order->date_ordered->format('d. m. Y.');
            $order->sourceName = $order->source->name;
            $order->deliveryServiceName = $order->deliveryService->name;
            $order->deliveryCompanyName = $order->deliveryService->deliveryCompany->name;
            $order->paymentTypeName = $order->paymentType->name;
            
            if ($order->date_cancelled) {
                $order->date_cancelled = $order->date_cancelled->format('d. m. Y.');
            }

            if ($order->date_delivered) {                
                $order->daysToDeliver = $order->date_delivered->diffInDays($order->date_ordered);
                $order->date_delivered = $order->date_delivered->format('d. m. Y.');
            }

            if ($order->date_sent) {
                $order->date_sent = $order->date_sent->format('d. m. Y.');
            }

            if ($order->date_deadline) {                
                $order->daysLeft = $order->date_deadline->diffInDays($today);
                $order->date_deadline = $order->date_deadline->format('d. m. Y.');
                
                if ($order->$daysLeft < 3) {
                    $order->deadlineClass = 'btn-danger';
                } elseif ($daysLeft < 5) {
                    $order->deadlineClass = 'btn-warning';
                } else {
                    $order->deadlineClass = 'btn-success';
                }
            }

            $order->receipt_id = $receipts[$order->id]->id ?? null;
            $order->isPaid = isset($order->receipt_id) && isset($kprs[$order->receipt_id]);

        }

        return view('pages.orders.index', compact(
            'orders', 'customers', 'sources', 'deliveryServices', 
            'deliveryCompanies', 'paymentTypes', 'countries', 'today'
        ));
    }

    public function show($order_id)
    {
        $order = Order::with(['customer', 'paymentType', 'source', 'deliveryService', 'country', 'orderItemList', 'orderNote'])->findOrFail($order_id);
        $order->paymentTypeName = $order->paymentType->name;
        $order->countryName = $order->country->name;
        $sources = Source::orderBy('id')->get();
        $deliveryServices = DeliveryService::orderBy('id')->get();
        $deliveryCompanies = DeliveryCompany::has('deliveryServices')->orderBy('id')->get();
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

        return view('pages.orders.show', [
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

        return redirect('/narudzbe/' . $next_id);
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