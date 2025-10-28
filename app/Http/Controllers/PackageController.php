<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Package;

use App\Traits\RecordManagement;

class PackageController extends Controller
{
    use RecordManagement;
    protected $modelClass = Package::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function index()
    {
        $packages = Package::orderBy('id', 'desc')->paginate(25);
        return view('pages.packages.index', compact('packages'));
    }

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
            'recipient_email' => 'nullable|string|max:255',
            'recipient_phone' => 'nullable|string|max:50',
        ]);

        return $this->createRecord($validated, 'Paket je uspješno dodan!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, [
            'status', 'tracking_number', 'date_shipped', 'date_delivered', 'date_cancelled',
            'weight', 'cod_price', 'recipient_name', 'recipient_address_name',
            'recipient_address_number', 'recipient_postcode', 'recipient_city',
            'recipient_country', 'recipient_country_code', 'recipient_email',
            'recipient_phone'
        ]);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}