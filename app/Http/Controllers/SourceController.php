<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Source;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class SourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() {
        $sources = Source::get()->sortBy('id');
        return view('pages.sources.index', compact('sources'));
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
        'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        Source::store($request->all());

        return redirect()->back()->with('success', 'Novi kanal prodaje uspješno dodan.');
    }

    public function update(Request $request, $id)
    {

        $source = Source::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $source->$field = $newValue;
        $source->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {

        $record = Source::findOrFail($id);
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
