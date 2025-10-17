<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Country;
use App\Traits\RecordManagement;

class CustomerController extends Controller
{
    use RecordManagement;
    protected $modelClass = \App\Models\Customer::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::search(
                        $search,
                        ['name', 'email', 'phone', 'address', 'postal', 'city'],
                        ['country' => ['name']]
                    )
                    ->with('country', 'orders')
                    ->withCount('orders')
                    ->orderBy('id')
                    ->paginate(25);

        $countries = Country::orderBy('id')->get();

        return view('pages.customers.index', compact('customers', 'countries', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'oib' => 'nullable|string|max:20',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'postal' => 'nullable|string|max:20',
            'country_id' => 'required|integer',
        ]);

        return $this->createRecord($data, 'Novi kupac je uspješno dodan.');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['name, oib, email, phone, address, house_number, city, postal,country_id']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}