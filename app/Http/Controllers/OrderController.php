<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $query = $this->buildBaseQuery($search);

        if ($type === 'kupac' && !$customerId) {
            return redirect()->back()->with(['error' => 'Nije odabran kupac ili šifra kupca nije ispravna.']);
        }

        $this->applyTypeFilter($query, $type, $customerId);

        $orders = $query->orderBy('id')->paginate(25);

        $data = $this->getReceiptAndKprData($orders);
        $receipts = $data['receiptIds'];
        $kprIds = $data['kprIds'];

        [
            'customers' => $customers,
            'sources' => $sources,
            'deliveryServices' => $deliveryServices,
            'deliveryCompanies' => $deliveryCompanies,
            'paymentTypes' => $paymentTypes,
            'countries' => $countries,
        ] = $this->getFormData();

        $today = now();
        $currentUrl = url()->current();

        $orders->getCollection()->transform(function ($order) use ($receipts, $kprIds) {
            $order->total_amount = GlobalService::sumOrderItems(orderId: $order->id);
            $order->receipt_id = $receipts[$order->id] ?? null;
            $order->is_paid = isset($order->receipt_id) && isset($kprIds[$order->receipt_id]);
            return $order;
        });

        return view('pages.orders.index', compact(
            'orders', 'customers', 'sources', 'deliveryServices',
            'deliveryCompanies', 'paymentTypes', 'countries', 'today', 'currentUrl'
        ));
    }

    public function show($order_id)
    {
        $order = $this->getOrderWithRelations($order_id);

        $this->calculateOrderTotals($order);

        $this->attachReceiptsAndKpr($order);

        $orderData = $this->getOrderData($order);

        $latestReceiptNumber = GlobalService::getLatestReceiptNumber(date('Y'));

        return view('pages.orders.show', compact(
            'order',
            'latestReceiptNumber'
        ))->with($orderData);
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
        $orderIds = $orders->pluck('id');

        $receiptIds = Receipt::whereIn('order_id', $orderIds)
            ->where('is_cancelled', 0)
            ->pluck('id', 'order_id');

        $kprIds = KprItemList::whereIn('receipt_id', $receiptIds->values())
            ->pluck('receipt_id')
            ->flip();

        return compact('receiptIds', 'kprIds');
    }

    private function buildBaseQuery($search)
    {
        return Order::search($search, [
            'tracking_code',
            'delivery_postal',
            'delivery_city',
        ], [
            'paymentType' => ['name'],
            'deliveryService.deliveryCompany' => ['name'],
            'customer' => ['name'],
        ]);
    }

    private function applyTypeFilter($query, $type, $customerId)
    {
        match ($type) {
            'sve' => $query,
            'poslane' => $query
                ->whereNotNull('date_sent')
                ->whereNull('date_delivered')
                ->whereNull('date_cancelled'),
            'neodradene' => $query
                ->whereNull('date_sent')
                ->whereNull('date_cancelled'),
            'otkazane' => $query
                ->whereNotNull('date_cancelled'),
            'kupac' => $query->where('customer_id', $customerId),
            default => abort(404),
        };
    }

    private function getFormData()
    {
        return [
            'customers' => Customer::orderBy('id')->get(),
            'sources' => Source::orderBy('id')->get(),
            'deliveryServices' => DeliveryService::where('in_use', true)->orderBy('name')->get(),
            'deliveryCompanies' => DeliveryCompany::whereHas('deliveryServices')->orderBy('id')->get(),
            'paymentTypes' => PaymentType::orderBy('id')->get(),
            'countries' => Country::orderBy('id')->get(),
        ];
    }

    private function getOrderWithRelations($order_id)
    {
        return Order::with([
            'customer',
            'paymentType',
            'source',
            'deliveryService.deliveryCompany',
            'country',
            'orderItemList.product.productType',
            'orderItemList.color',
            'orderNote'
        ])->findOrFail($order_id);
    }

    private function calculateOrderTotals($order)
    {
        $order->delivery_cost = $order->deliveryService->default_cost;
        $order->subtotal = GlobalService::sumOrderItems(orderId: $order->id);
        $order->total = $order->subtotal + $order->delivery_cost;
    }

    private function attachReceiptsAndKpr($order)
    {
        $data = $this->getReceiptAndKprData($order);
        $receipts = $data['receiptIds'];
        $kprIds = $data['kprIds'];

        $order->receipt_id = $receipts[$order->id] ?? null;
        $order->is_paid = isset($order->receipt_id) && isset($kprIds[$order->receipt_id]);
    }

    private function getOrderData($order)
    {
        return [
            'sources' => Source::orderBy('id')->get(),
            'deliveryServices' => DeliveryService::orderBy('id')->get(),
            'deliveryCompanies' => DeliveryCompany::has('deliveryServices')->orderBy('id')->get(),
            'paymentTypes' => PaymentType::orderBy('id')->get(),
            'countries' => Country::orderBy('id')->get(),
            'products' => Product::orderBy('name')->get(),
            'productTypes' => ProductType::orderBy('id')->get(),
            'colors' => Color::orderBy('id')->get(),
            'orderItemList' => $order->orderItemList,
            'orderNotes' => $order->orderNote,
        ];
    }
}