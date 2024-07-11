<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Kpr;
use App\Models\KprItemList;
use App\Models\Receipt;
use App\Models\KprPaymentType;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class KprController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    // GET function for displaying purposes
    public function show($year) {

    $kprs = Kpr::whereYear('date', $year)->orderBy('date')->get();
    $paymentMethods = KprPaymentType::get();
        
    return view('kpr-view', [
        'kprs' => $kprs,
        'year' => $year,
        'paymentMethods' => $paymentMethods
        ]);
    }

    // EDIT function for displaying single KPR item
    public function edit($kpr_id) {

        $item = Kpr::where('id', $kpr_id)->firstOrFail();            
        $invoiceList = KprItemList::where('kpr_id', $kpr_id)->get();

        $date = new Carbon( $item->date );
        $year = $date->year;

        $receipts = Receipt::where('year', $date->year)->where('is_cancelled', 0)->orderBy('number')->get();
        
        return view('kpr-edit', [
            'item' => $item,
            'year' => $year,
            'invoiceList' => $invoiceList,
            'receipts' => $receipts
            ]);
    }


    // POST function for saving new stuff
    public function save (Request $request) {

        $date = new Carbon( $request->date );

        $validator = Validator::make($request->all(), [
        'date' => 'required',
        'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/knjiga-prometa/' . $date->year)
                ->withInput()
                ->withErrors($validator);
        }        

        $kprEntry = new Kpr;
        $kprEntry->payer = $request->payer;
        $kprEntry->kpr_payment_type_id = $request->kpr_payment_type_id;
        $kprEntry->date = $request->date;
        $kprEntry->amount = $request->amount;
        $kprEntry->origin = $request->origin;
        $kprEntry->info = $request->info;
        $kprEntry->save();
        
        return redirect('/knjiga-prometa/' . $date->year);
    }


    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {

        $record = Kpr::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $record->$field = $newValue;
        $record->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }


    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {

        $record = Kpr::findOrFail($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }


    // RETURN total payments per given year
    public static function getTotalPayments($year) {

        return number_format(( Kpr::whereYear('date', $year)->sum(\DB::raw('amount')) ), 2, ',');
    }


    // RETURN number of payments per given year
    public static function countPayments($year) {

        return Kpr::whereYear('date', $year)->count();
    }


    // UNKNOWN FUNCTION !!!
    public static function checkReceiptsAndSum($kpr_id) {
        
        $currentKprItemList = KprItemList::where('kpr_id', $kpr_id)->get();

        $totalSum = 0;
        foreach ($currentKprItemList as $item) {
            $itemReceiptSum = ReceiptController::getReceiptTotal(Receipt::where('id', $item->receipt_id)->limit(1)->value('order_id'));
            $convertedCost = str_replace(',', '.', $itemReceiptSum);
            $totalSum += $convertedCost;
        }

        return number_format(($totalSum), 2, ',');
    }

        
}
