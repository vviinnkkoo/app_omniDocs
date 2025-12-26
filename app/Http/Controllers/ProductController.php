<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductType;

use App\Traits\RecordManagement;

class ProductController extends Controller
{
    use RecordManagement;
    protected $modelClass = Product::class;
    
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

        if ($search) {
            $group = Product::groupFromSearch($search);

            if ($group) {
                $search = $group;
            }
        }

        $query = Product::with('productType')
            ->search(
                $search,
                ['name', 'group'],
                ['productType' => ['name']]
            );

        $products = $query->orderBy('name')->paginate(25);
        $productTypes = ProductType::orderBy('id')->get();
        $groups = Product::groups();

        return view('pages.products.index', compact('products', 'productTypes', 'search', 'groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'group' => 'required|in:' . implode(',', Product::groupKeys()),
            'product_type_id' => 'required|exists:product_types,id',
            'default_price' => 'required|numeric',
        ]);

        return $this->createRecord($validated, 'Proizvod je uspješno dodan.');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['name', 'group', 'product_type_id', 'default_price']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}
