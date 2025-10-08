<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Package;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('id', 'desc')->paginate(25);
        return view('pages.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        public function store(Request $request)
        {
            $validated = $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'delivery_service_id' => 'required|integer|exists:delivery_services,id',
                'status' => 'nullable|string|max:255',
                'tracking_number' => 'nullable|string|max:255',
                'date_shipped' => 'nullable|date',
                'date_delivered' => 'nullable|date',
                'date_cancelled' => 'nullable|date',
                'weight' => 'nullable|numeric|min:0',
                'cod_price' => 'nullable|numeric|min:0',
                'recipient_name' => 'required|string|max:255',
                'recipient_address_name' => 'required|string|max:255',
                'recipient_address_number' => 'required|string|max:50',
                'recipient_postcode' => 'required|string|max:20',
                'recipient_city' => 'required|string|max:255',
                'recipient_country' => 'required|string|max:255',
                'recipient_country_code' => 'nullable|string|max:5',
                'recipient_email' => 'nullable|email|max:255',
                'recipient_phone' => 'nullable|string|max:50',
            ]);

            Package::create($validated);

            return redirect()->back()->with('success', 'Paket uspješno dodan!');
        }
    }

    public function update(Request $request, $id)
    {
        $record = Package::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $record->$field = $newValue;
        $record->save();

        return response()->json(['message' => 'Paket uspješno ažuriran!']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Package::findOrFail($id);
        if (!$record) {
            return response()->json(['message' => 'Odabrani unos nije pronađen!'], 404);
        }
        if ($record->delete()) {
            return response()->json(['message' => 'Uspješno obrisano!']);
        }
        return response()->json(['message' => 'Greška prilikom brisanja!'], 500);
    }
}