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
        // Get all active orders, then sum the total amount of each order
        // and add it to the total earnings
        $orderIds = Order::whereNull('date_cancelled')->pluck('id');
        $countOrders = $orders->count();

        $totalEarnings = 0; // Initialize total earnings to 0

        foreach ($orderIds as $orderId) {
            $totalEarnings += GlobalService::sumWholeOrder($orderId); 
        }

        $earningTotal = number_format($totalEarnings, 2, ',');

        // Get all undelivered orders, then sum the total amount of each order
        $earningUndelivered = number_format(OrderItemList::whereHas('order', function ($q1) {
            $q1->whereNull('date_sent')->whereNull('date_cancelled');
        })->sum('amount * price'), 2, ',');

        $earningCurrentMonth = number_format(OrderItemList::whereHas('order', function ($q2) {
            $q2->whereYear('date_ordered', Carbon::now()->year)
                ->whereMonth('date_ordered', Carbon::now()->month);
        })->sum('amount * price'), 2, ',');
        $countUndeliveredOrders = Order::whereNull('date_sent')
            ->whereNull('date_cancelled')
            ->count();
        $countThisMonthOrders = Order::whereYear('date_ordered', Carbon::now()->year)
            ->whereMonth('date_ordered', Carbon::now()->month)
            ->count();

        return view('home', compact(
            'earningTotal', 
            'earningUndelivered', 
            'earningCurrentMonth', 
            'countOrders', 
            'countUndeliveredOrders', 
            'countThisMonthOrders'
        ));
    }
}
