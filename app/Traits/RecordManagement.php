<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

trait RecordManagement
{ 
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