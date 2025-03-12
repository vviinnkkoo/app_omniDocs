<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Customer::query();

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhere('postal', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('id')->paginate(25);
        $countries = Country::orderBy('id')->get();

        return view('pages.customers.index', [
            'customers' => $customers,
            'countries' => $countries,
            'search' => $search
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'country_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Customer::create($request->only([
            'name', 'oib', 'email', 'phone', 'address', 'house_number', 'city', 'postal', 'country_id'
        ]));

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $customer->$field = $newValue;
        $customer->save();

        return response()->json(['message' => 'Customer updated successfully']);
    }

    public function destroy($id): JsonResponse
    {
        $record = Customer::findOrFail($id);

        if (!$record->delete()) {
            abort(500, 'Brisanje trenutno nije moguće.');
        }

        return response()->json(['message' => 'Uspješno obrisano.']);
    }
}