<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

trait RecordManagement
{ 
    /*
    |--------------------------------------------------------------------------------------------
    | Create a new record.
    |--------------------------------------------------------------------------------------------
    */
    public function createRecord(array $data, string $successMsg = 'Uspješno dodano.')
    {
        try {
            $this->modelClass::create($data);
            return redirect()->back()->with('success', $successMsg);
        } catch (\Throwable $e) {
            \Log::error('Greška prilikom kreiranja zapisa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Greška prilikom spremanja zapisa.');
        }
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Update a single field of a model.
    |--------------------------------------------------------------------------------------------
    */
    public function updateRecord(Request $request, $id, array $allowedFields): JsonResponse
    {
        $validated = $request->validate([
            'field' => 'required|string|in:' . implode(',', $allowedFields),
            'newValue' => 'required'
        ]);

    try {
            $record = $this->modelClass::findOrFail($id);
            $record->{$validated['field']} = $validated['newValue'];
            $record->save();

            return response()->json(['status' => 'success', 'message' => 'Uspješno izmijenjeno.']);
        } catch (\Throwable $e) {
            \Log::error('Greška prilikom izmjene zapisa: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Greška prilikom izmjene zapisa.'], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Delete a field in the model.
    |--------------------------------------------------------------------------------------------
    */
    public function deleteRecord($id): JsonResponse
    {
        return $this->modelClass::findOrFail($id)->delete()
            ? response()->json(['status' => 'success', 'message' => 'Uspješno obrisano.'])
            : response()->json(['status' => 'error', 'message' => 'Greška pri brisanju.'], 500);
    }
}