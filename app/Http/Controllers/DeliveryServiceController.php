<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryService;
use App\Models\DeliveryCompany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class DeliveryServiceController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // GET function for displaying purposes
    public function index(Request $request)
    {
        $deliveryServices = DeliveryService::with('deliveryCompany')->orderBy('delivery_company_id')->orderBy('name')->get();
        $deliveryCompanies = DeliveryCompany::orderBy('id')->get();
        
        return view('deliveryServices', [
            'deliveryServices' => $deliveryServices,
            'deliveryCompanies' => $deliveryCompanies
        ]);
    }

    // POST function for saving new stuff
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'default_cost' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DeliveryService::create([
            'name' => $request->name,
            'delivery_company_id' => $request->company_id,
            'default_cost' => $request->default_cost
        ]);
    
        return redirect()->back();
    }

    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {
        $deliveryService = DeliveryService::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');
        
        $deliveryService->$field = $newValue;
        $deliveryService->save();

        return response()->json(['message' => 'Delivery service updated successfully']);
    }

    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {
        $record = DeliveryService::findOrFail($id);

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }

    // Checkbox function (Ajax version)
    public function updateIsUsedStatus(Request $request, $id)
    {
        $deliveryService = DeliveryService::findOrFail($id);
        $deliveryService->in_use = !$deliveryService->in_use;
        $deliveryService->save();

        return response()->json(['message' => 'Delivery service status updated successfully']);
    }
}