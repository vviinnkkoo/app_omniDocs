<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;

use App\Traits\RecordManagement;

class ProductTypeController extends Controller
{
    use RecordManagement;
    protected $modelClass = ProductType::class;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = ProductType::search($search, ['name']);

        $productTypes = $query->orderBy('id')->paginate(25);

        return view('pages.product-types.index', compact('productTypes', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        return $this->createRecord($validated, 'Nova vrsta proizvoda uspješno dodana!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['name']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}