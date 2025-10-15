<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Country::create([
            'name' => $request->name
        ]);

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