<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\KprItemList;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class KprItemListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function add(Request $request, $id)
    {
        $validated = $request->validate([
            'receipt_id' => 'required',
        ]);
        
        KprItemList::create([
            'receipt_id' => $validated['receipt_id'],
            'kpr_id' => $id,
        ]);

        return back();
    }


    public function destroy(Request $request, $id): JsonResponse
    {
        return KprItemList::findOrFail($id)->delete()
            ? response()->json(['message' => 'Record deleted successfully'])
            : response()->json(['message' => 'Error deleting the record'], 500);
    }
}
