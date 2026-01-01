<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PaymentType;

use App\Traits\RecordManagement;

use App\Enums\FiscalCode;

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
        $fiscalCodes = FiscalCode::options();
    
        return view('pages.payment-types.index', compact('paymentTypes', 'search', 'fiscalCodes'));
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'fiscal_code_key' => 'required|in:' . implode(',', PaymentType::fiscalCodeKeys()),
        ]);

        return $this->createRecord($validated, 'Način plaćanja uspješno dodan!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['name', 'fiscal_code_key']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }

}
