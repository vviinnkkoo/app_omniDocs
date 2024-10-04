<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderItemList;
use App\Models\Order;
use App\Models\DeliveryService;
use App\Models\Receipt;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;

class Omnicontrol extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display index page
    public function index()
    {
        $earningTotal = number_format(OrderItemList::sum(\DB::raw('amount * price')), 2, ',');
        
        $earningUndelivered = number_format(OrderItemList::whereHas('order', function ($q1) {
            $q1->whereNull('date_sent');
        })->sum(\DB::raw('amount * price')), 2, ',');

        $earningCurrentMonth = number_format(OrderItemList::whereHas('order', function ($q2) {
            $q2->whereYear('date_ordered', Carbon::now()->year)->whereMonth('date_ordered', Carbon::now()->month);
        })->sum(\DB::raw('amount * price')), 2, ',');
        
        $countOrders = Order::all()->count();
        $countUndeliveredOrders = Order::whereNull('date_sent')->count();
        $countThisMonthOrders = Order::whereYear('date_ordered', Carbon::now()->year)->whereMonth('date_ordered', Carbon::now()->month)->count();
        
        return view('home', [
            'earningTotal' => $earningTotal,
            'earningUndelivered' => $earningUndelivered,
            'earningCurrentMonth' => $earningCurrentMonth,
            'countOrders' => $countOrders,
            'countUndeliveredOrders' => $countUndeliveredOrders,
            'countThisMonthOrders' => $countThisMonthOrders
            ]);
    }

    public function hpCodModifierCheck($order_id) {
        $order = Order::where('id', $order_id)->firstOrFail();
        $deliveryService = DeliveryService::where('id', Order::find($order_id)->delivery_service_id)->firstOrFail();

        if ($order->payment_type_id == 2 && $deliveryService->delivery_company_id == 1) {
            return 0.6;
        } else {
            return 0;
        }
    }
}
