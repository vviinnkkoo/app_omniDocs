<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait RecordManagement
{ 
    /*
    |--------------------------------------------------------------------------------------------
    | Update a single field of a model.
    |--------------------------------------------------------------------------------------------
    */

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $fillableFields
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRecord($modelClass, Request $request, $id, array $allowedFields): JsonResponse
    {
        $validated = $request->validate([
            'field' => 'required|string|in:' . implode(',', $allowedFields),
            'newValue' => 'nullable'
        ]);

        $model = $modelClass::findOrFail($id);
        $model->update([$validated['field'] => $validated['newValue']]);

        return response()->json(['status' => 'success', 'message' => 'Izmjene su uspješno spremljene.']);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Delete a field in the model.
    |--------------------------------------------------------------------------------------------
    */

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRecord($modelClass, $id): JsonResponse
    {
        return $modelClass::findOrFail($id)->delete()
            ? response()->json(['status' => 'success', 'message' => 'Uspješno obrisano.'])
            : response()->json(['status' => 'error', 'message' => 'Greška pri brisanju.'], 500);
    }
}