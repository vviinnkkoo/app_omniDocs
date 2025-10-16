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
    public function createRecord(array $data, string $successMsg = 'Uspješno dodano.'): JsonResponse
    {
        try {
            $this->modelClass::create($data);
            return response()->json(['status' => 'success', 'message' => $successMsg]);
        } catch (\Throwable $e) {
            Log::error('Greška prilikom kreiranja zapisa: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Dogodila se greška prilikom spremanja zapisa.'], 500);
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

        return $this->modelClass::whereKey($id)->update([$validated['field'] => $validated['newValue']])
            ? response()->json(['status' => 'success', 'message' => 'Uspješno izmjenjeno.'])
            : response()->json(['status' => 'error', 'message' => 'Greška prilikom izmjene.'], 500);
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