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

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Country::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $countries = $query->orderBy('id')->paginate(25);

        return view('pages.countries.index', compact('countries', 'search'));
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

        return response()->json(['status' => 'success', 'message' => 'Izmjenjeni podaci su uspješno spremljeni.']);
    }

    public function destroy($id): JsonResponse
    {
        return Country::findOrFail($id)->delete()
            ? response()->json(['status' => 'success', 'message' => 'Uspjšno obrisano.'])
            : response()->json(['status' => 'error', 'message' => 'Dogodila se pogreška kod brisanja.'], 500);
    }
}
