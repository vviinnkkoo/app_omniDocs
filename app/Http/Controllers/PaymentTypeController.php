<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PaymentType;

use App\Traits\RecordManagement;

class PaymentTypeController extends Controller
{
    use RecordManagement;
    protected $modelClass = PaymentType::class;

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

        $query = PaymentType::search($search, ['name']);

        $paymentTypes = $query->orderBy('id')->paginate(25);

        return view('pages.payment-types.index', compact('paymentTypes', 'search'));
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        return $this->createRecord($validated, 'Način plaćanja uspješno dodan!');
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
