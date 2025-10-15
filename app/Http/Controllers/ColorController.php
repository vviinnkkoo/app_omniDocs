<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Traits\RecordManagement;

class ColorController extends Controller
{
    use RecordManagement;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Color::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $colors = $query->orderBy('id')->paginate(25);

        return view('pages.colors.index', compact('colors', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Color::create($request->only('name'));

        return redirect()
            ->back()->with('success', 'Boja ili opis proizvoda uspješno dodan!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord(Country::class, $request, $id, ['name']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord(Country::class, $id);
    }
}