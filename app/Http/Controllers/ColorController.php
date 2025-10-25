<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Color;

use App\Traits\RecordManagement;

class ColorController extends Controller
{
    use RecordManagement;
    protected $modelClass = Color::class;

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

        $colors = Color::search($search, ['name'])
                    ->orderBy('id')
                    ->paginate(25);

        return view('pages.colors.index', compact('colors', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return $this->createRecord($data, 'Boja ili opis proizvoda uspješno dodan!');
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