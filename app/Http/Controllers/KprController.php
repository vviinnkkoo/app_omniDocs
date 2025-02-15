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
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function show(Request $request, $year)
    {
        $search = $request->input('search');
        $query = Kpr::query();

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('payer', 'like', "%{$search}%")
                      ->orWhere('amount', 'like', "%{$search}%")
                      ->orWhere('origin', 'like', "%{$search}%")
                      ->orWhere('date', 'like', "%{$search}%")
                      ->orWhere('info', 'like', "%{$search}%");
            });
        }

        $kprs = $query->whereYear('date', $year)->orderBy('date')->paginate(25);
        $paymentMethods = KprPaymentType::all();
        $count = 0;

        foreach ($kprs as $item) {
            $item->exists = KprItemList::where('kpr_id', $item->id)->exists();
            $item->date = Carbon::parse($item->date)->format('d.m.Y');
            $item->paymentTypeName = $item->paymentType->name;
            $item->receiptsTotal = GlobalService::sumAllReciepesFromKpr($item->id);
        }

        return view('kpr-view', compact('kprs', 'year', 'paymentMethods', 'count'));
    }


    public function edit($kpr_id)
    {
        $item = Kpr::findOrFail($kpr_id);
        $invoiceList = KprItemList::where('kpr_id', $kpr_id)->get();
        $year = Carbon::parse($item->date)->year;
        $receipts = Receipt::where('year', $year)->where('is_cancelled', 0)->orderBy('number')->get();

        return view('kpr-edit', compact('item', 'year', 'invoiceList', 'receipts'));
    }


    public function save(Request $request)
    {
        $date = Carbon::parse($request->date);

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/knjiga-prometa/' . $date->year)
                ->withInput()
                ->withErrors($validator);
        }

        Kpr::create($request->all());

        return redirect('/knjiga-prometa/' . $date->year);
    }


    public function update(Request $request, $id)
    {
        $record = Kpr::findOrFail($id);
        $record->update([$request->input('field') => $request->input('newValue')]);

        return response()->json(['message' => 'Payment type updated successfully']);
    }


    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Kpr::findOrFail($id);

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}