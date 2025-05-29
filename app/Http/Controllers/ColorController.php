<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Color;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() {
        $colors = Color::get()->sortBy('id');
        return view('pages.colors.index', compact('colors'));
    }

    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        Color::create($request->all());

        return redirect()->back()->with('success', 'Boja ili opis proizvoda uspješno dodan!');
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $color->$field = $newValue;
        $color->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Color::findOrFail($id);
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
