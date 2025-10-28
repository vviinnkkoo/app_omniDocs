<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Source;

use App\Traits\RecordManagement;

class SourceController extends Controller
{
    use RecordManagement;
    protected $modelClass = Source::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $sources = Source::search($search, ['name'])
            ->orderBy('id')
            ->paginate(25);

        return view('pages.sources.index', compact('sources', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        return $this->createRecord($data, 'Novi izvor prodaje uspješno je dodan!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}
