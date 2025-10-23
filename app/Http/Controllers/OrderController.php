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
use App\Models\KprItemList;
use App\Models\WorkYears;
use Illuminate\Support\Facades\DB;
use App\Services\GlobalService;
use App\Traits\RecordManagement;

class OrderController extends Controller
{
    use RecordManagement;
    protected $modelClass = Order::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function index(Request $request, $type, $customerId = null)
    {
        $search = $request->input('search');
        $query = Order::query();

        // Search functionality
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

        // Filter by defined types
        switch ($type) {
            case 'sve':
                $orders = $query->orderBy('id')->paginate(25);
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
            case 'kupac':
                if ($customerId) {
                    $orders = $query->where('customer_id', $customerId)
                        ->orderBy('id')
                        ->paginate(25);
                } else {
                    return redirect()->back()->with(['error' => 'Nije odabran kupac ili šifra kupca nije ispravna.']);
                }
                break;
            default:
                return;
        }

        // Fetch data for the view
        $data = $this->getReceiptAndKprData($orders);
        $receipts = $data['receiptId'];
        $kprIds = $data['kprIds'];

        // Get other required data for the view
        $customers = Customer::orderBy('id')->get();
        $sources = Source::orderBy('id')->get();
        $deliveryServices = DeliveryService::where('in_use', true)->orderBy('name')->get();
        $deliveryCompanies = DeliveryCompany::whereHas('deliveryServices')->orderBy('id')->get();
        $paymentTypes = PaymentType::orderBy('id')->get();
        $countries = Country::orderBy('id')->get();
        $today = Carbon::now();
        $currentUrl = url()->current();

        // Add extra information to orders using accessors
        foreach ($orders as $order) {
            $order->total_amount = GlobalService::sumOrderItems(orderId: $order->id);
            $order->receipt_id = $receipts[$order->id] ?? null;
            $order->is_paid = isset($order->receipt_id) && isset($kprIds[$order->receipt_id]);
        }

        return view('pages.orders.index', compact(
            'orders', 'customers', 'sources', 'deliveryServices', 
            'deliveryCompanies', 'paymentTypes', 'countries', 'today', 'currentUrl'
        ));
    }

    public function show($order_id)
    {
        $order = Order::with([
            'customer',
            'paymentType',
            'source',
            'deliveryService.deliveryCompany',
            'country',
            'orderItemList.product',
            'orderItemList.product.productType',
            'orderItemList.color',
            'orderNote'
        ])->findOrFail($order_id);

        $order->delivery_cost = $order->deliveryService->default_cost;
        $order->subtotal = GlobalService::sumOrderItems(orderId: $order_id);
        $order->total = $order->subtotal + $order->delivery_cost;

        // Fetch data for the view
        $data = $this->getReceiptAndKprData($order);
        $receipts = $data['receiptId'];
        $kprIds = $data['kprIds'];

        // Condition checks
        $order->receipt_id = $receipts[$order->id] ?? null;
        $order->is_paid = isset($order->receipt_id) && isset($kprs[$order->receipt_id]);

        $sources = Source::orderBy('id')->get();
        $deliveryServices = DeliveryService::orderBy('id')->get();
        $deliveryCompanies = DeliveryCompany::has('deliveryServices')->orderBy('id')->get();
        $paymentTypes = PaymentType::orderBy('id')->get();
        $countries = Country::orderBy('id')->get();
        $products = Product::orderBy('name')->get();
        $productTypes = ProductType::orderBy('id')->get();
        $colors = Color::orderBy('id')->get();
        $latestReceiptNumber = GlobalService::getLatestReceiptNumber(date('Y'));
        $orderItemList = $order->orderItemList;

        return view('pages.orders.show', [
            'order' => $order,
            'sources' => $sources,
            'deliveryServices' => $deliveryServices,
            'deliveryCompanies' => $deliveryCompanies,
            'paymentTypes' => $paymentTypes,
            'countries' => $countries,
            'orderItemList' => $orderItemList,
            'orderNotes' => $order->orderNote,
            'products' => $products,
            'productTypes' => $productTypes,
            'colors' => $colors,
            'latestReceiptNumber' => $latestReceiptNumber
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_ordered' => 'required|date',
            'date_deadline' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'source_id' => 'required|exists:sources,id',
            'delivery_service_id' => 'required|exists:delivery_services,id',
            'payment_type_id' => 'required|exists:payment_types,id',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        $order = Order::create([
            ...$validated,
            'delivery_address' => "{$customer->address} {$customer->house_number}",
            'delivery_city' => $customer->city,
            'delivery_country_id' => $customer->country_id,
            'delivery_postal' => $customer->postal,
            'delivery_phone' => $customer->phone,
            'delivery_email' => $customer->email,
        ]);

        return redirect()->route('narudzbe.show', $order);
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, [
            'date_ordered', 'date_deadline', 'customer_id',
            'date_sent', 'date_delivered', 'date_cancelled',
            'source_id', 'delivery_service_id', 'payment_type_id',
            'delivery_address', 'delivery_city', 'delivery_country_id',
            'delivery_postal', 'delivery_phone', 'delivery_email',
            'tracking_code'
        ]);
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
    private function getReceiptAndKprData($orders)
    {
        $orderIds = $orders->pluck('id')->toArray();

        $receiptId = Receipt::whereIn('order_id', $orderIds)
            ->where('is_cancelled', 0)
            ->pluck('id', 'order_id');

        $kprIds = KprItemList::whereIn('receipt_id', $receiptId->values())
            ->pluck('receipt_id', 'receipt_id');

        return [
            'receiptId' => $receiptId,
            'kprIds' => $kprIds
        ];
    }
}