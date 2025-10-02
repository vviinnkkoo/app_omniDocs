<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Http\JsonResponse;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $colors = Color::orderBy('id')->paginate(25);

        return view('pages.colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Color::create($request->only('name'));

        return redirect()
            ->back()
            ->with('success', 'Boja ili opis proizvoda uspješno dodan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'field' => 'in:name',
            'newValue' => 'required|string|max:255'
        ]);

        $color = Color::findOrFail($id);
        $color->{$request->field} = $request->newValue;
        $color->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Boja ili opis proizvoda uspješno ažuriran!'
        ]);
    }

    public function destroy($id): JsonResponse
    {
        return Color::findOrFail($id)->delete()
            ? response()->json(['status' => 'success', 'message' => 'Record deleted successfully'])
            : response()->json(['status' => 'error', 'message' => 'Error deleting the record'], 500);
    }
}