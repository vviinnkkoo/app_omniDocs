<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Country;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $countries = Country::orderBy('id')->get();
        
        return view('countries', [
            'countries' => $countries
            ]);
    }

    public function store (Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Country::create($request->only([
            'name'
        ]));
    
        return redirect()->back()->with('success', 'Država uspješno dodana.');

    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);

        $validated = $request->validate([
            'field' => 'required|in:name',
            'newValue' => 'required'
        ]);

        $country->update([$validated['field'] => $validated['newValue']]);        

        return response()->json(['message' => 'Izmjenjeni podaci su uspješno spremljeni.']);
    }

    public function destroy(Request $request, $id)
    {
        $record = Country::findOrFail($id);

        if ($record->delete()) {
            return redirect()->back()->with('success', 'Država uspješno obrisana.');
        }
        return redirect()->back()->with('error', 'Država nije obrisana.');
    }
}
