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
    public function show(Request $request) {
        $deliveryServices = DeliveryService::get()->sortBy('name')->sortBy('delivery_company_id');
        $deliveryCompanies = DeliveryCompany::get()->sortBy('id');
        
        return view('deliveryServices', [
            'deliveryServices' => $deliveryServices,
            'deliveryCompanies' => $deliveryCompanies
            ]);
    }


    // POST function for saving new stuff
    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'default_cost' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/dostavne-sluzbe')
                ->withInput()
                ->withErrors($validator);
        }

        $deliveryService = new DeliveryService;
        $deliveryService->name = $request->name;
        $deliveryService->delivery_company_id = $request->company_id;
        $deliveryService->default_cost = $request->default_cost;
        $deliveryService->save();
    
        return redirect('/dostavne-sluzbe');
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
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }


    // Checkbox function (Ajax version)
    public function updateIsUsedStatus(Request $request, $id)
    {
        $deliveryService = DeliveryService::find($id);
        if ($deliveryService->in_use == false) {
            $newUseValue = true; // If it's 0, set it to 1
        } else {
            $newUseValue = false; // If it's 1, set it to 0
        }

        $deliveryService->update(['in_use' => $newUseValue]);
    }

}
