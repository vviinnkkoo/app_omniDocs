<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use Illuminate\Http\JsonResponse;

class ProductTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $productTypes = ProductType::orderBy('id')->get();

        return view('pages.payment-types.index', compact('productTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        ProductType::create($request->only('name'));

        return redirect()
            ->back()
            ->with('success', 'Vrsta proizvoda uspješno dodana!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'field' => 'in:name',
            'newValue' => 'required|string|max:255'
        ]);

        $productType = ProductType::findOrFail($id);
        $productType->{$request->field} = $request->newValue;
        $productType->save();

        return response()->json(['status' => 'success', 'message' => 'Product type updated successfully']);
    }

    public function destroy($id): JsonResponse
    {
        return ProductType::findOrFail($id)->delete()
            ? response()->json(['status' => 'success', 'message' => 'Record deleted successfully'])
            : response()->json(['status' => 'error', 'message' => 'Error deleting the record'], 500);
    }
}