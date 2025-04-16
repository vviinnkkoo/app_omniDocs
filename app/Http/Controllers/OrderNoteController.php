<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

use App\Models\OrderNote;

class OrderNoteController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
{
    $request->validate(['note' => 'required|string|min:2|max:1000', 'order_id' => 'required|integer']);

    OrderNote::create([
        'note' => $request->note,
        'order_id' => $id,
    ]);

    return back()->with('success', 'Napomena uspješno dodana.');
}

public function update(Request $request, $id): JsonResponse
{
    OrderNote::findOrFail($id)->update([
        $request->input('field') => $request->input('newValue')
    ]);

    return response()->json(['message' => 'Napomena ažurirana.']);
}

public function destroy($id): JsonResponse
{
    $deleted = OrderNote::findOrFail($id)->delete();

    return response()->json([
        'message' => $deleted ? 'Napomena obrisana.' : 'Greška prilikom brisanja.'
    ], $deleted ? 200 : 500);
}

}
