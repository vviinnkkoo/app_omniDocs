<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Traits\RecordManagement;

class CountryController extends Controller
{
    use RecordManagement;
    protected $modelClass = \App\Models\Country::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $countries = Country::search($search, ['name'])
                            ->orderBy('id')
                            ->paginate(25);

        return view('pages.countries.index', compact('countries', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return $this->createRecord($data, 'Država uspješno dodana.');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['name']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}