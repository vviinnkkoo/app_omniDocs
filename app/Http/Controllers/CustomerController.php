<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\Country;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
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
                      ->orWhere('email', 'like', "%{$search}%")
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
    public function save (Request $request, $ref) {
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'country_id' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect('/kupci')
                    ->withInput()
                    ->withErrors($validator);
            }
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->oib = $request->oib;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->house_number = $request->house_number;
        $customer->city = $request->city;
        $customer->postal = $request->postal;
        $customer->country_id = $request->country_id;
        $customer->save();

        if ($ref == 1) {
            return redirect('/kupci');
        } elseif ($ref == 2) {
            return redirect('/narudzbe/3');
        }        
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
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
