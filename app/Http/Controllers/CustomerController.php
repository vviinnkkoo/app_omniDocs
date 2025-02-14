<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }

    // GET function for displaying purposes
    public function show(Request $request)
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

        return view('customers', [
            'customers' => $customers,
            'countries' => $countries,
            'search' => $search
        ]);
    }

    // POST function for saving new stuff
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $customer->$field = $newValue;
        $customer->save();

        return response()->json(['message' => 'Customer updated successfully']);
    }

    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Customer::findOrFail($id);

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}