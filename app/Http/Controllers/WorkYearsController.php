<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WorkYears;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class WorkYearsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {        
        return view('pages.work-years.index');
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
        'year' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('error', 'Molimo unesite radnu godinu.')
                ->withInput();
        }

        WorkYears::create($request->all());
    
        return redirect()->back()
            ->with('success', 'Radna godina je uspješno dodana.');
    }

    public function update(Request $request, $id)
    {
        $source = WorkYears::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $source->$field = $newValue;
        $source->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $record = WorkYears::findOrFail($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
}
