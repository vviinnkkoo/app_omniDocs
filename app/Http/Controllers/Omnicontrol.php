<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderItemList;
use App\Models\Order;
use App\Models\DeliveryService;
use App\Models\Receipt;
use App\Models\WorkYears;
use App\Services\GlobalService;

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
        $activeOrderIds = Order::whereNull('date_cancelled')->pluck('id');
        $countActiveOrders = $activeOrderIds->count();

        $undeliveredOrderIds = Order::whereNull('date_sent')->whereNull('date_cancelled')->pluck('id');
        $countUndeliveredOrders = $undeliveredOrderIds->count();
        
        $thisMonthOrderIds = Order::whereYear('date_ordered', Carbon::now()->year)
            ->whereMonth('date_ordered', Carbon::now()->month)
            ->pluck('id');
        $countThisMonthOrders = $thisMonthOrderIds->count();

        $workYears = WorkYears::orderBy('year')->pluck('year')->toArray();
        $yearData = [];

        foreach ($workYears as $year) {
        $invoiceCount = GlobalService::countReceipts($year);
        $invoiceSum = GlobalService::calculateTotalForAllReceiptsInYear($year);

        $paymentCount = GlobalService::countAllPaymentsInYear($year);
        $paymentSum = GlobalService::sumAllPaymentsInYear($year);

        $yearData[] = [
            'year' => $year,
            'invoiceCount' => $invoiceCount,
            'invoiceSum' => number_format($invoiceSum, 2, ','),
            'paymentCount' => $paymentCount,
            'paymentSum' => number_format($paymentSum, 2, ',')
        ];
    }

        $totalEarnings = $undeliveredEarnings = $currentMonthEarnings  = 0;

        foreach ($activeOrderIds as $orderId) {
            $totalEarnings += GlobalService::sumWholeOrder($orderId); 
        }

        foreach ($undeliveredOrderIds as $orderId) {
            $undeliveredEarnings += GlobalService::sumWholeOrder($orderId); 
        }

        foreach ($thisMonthOrderIds as $orderId) {
            $currentMonthEarnings += GlobalService::sumWholeOrder($orderId); 
        }

        $totalEarnings = number_format($totalEarnings, 2, ',');
        $undeliveredEarnings = number_format($undeliveredEarnings, 2, ',');
        $currentMonthEarnings = number_format($currentMonthEarnings, 2, ',');

        return view('home', compact(
            'totalEarnings', 
            'undeliveredEarnings', 
            'currentMonthEarnings', 
            'countActiveOrders', 
            'countUndeliveredOrders', 
            'countThisMonthOrders',
            'yearData'
        ));
    }
}
