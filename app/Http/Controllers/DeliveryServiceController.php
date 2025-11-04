<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DeliveryService;
use App\Models\DeliveryCompany;

use App\Traits\RecordManagement;

class DeliveryServiceController extends Controller
{
    use RecordManagement;
    protected $modelClass = DeliveryService::class;

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

        $deliveryServices = DeliveryService::search(
                $search,
                ['name'],
                ['deliveryCompany' => ['name']]
            )
            ->with('deliveryCompany')
            ->orderBy('delivery_company_id')
            ->orderBy('name')
            ->paginate(25)
            ->through(fn ($item) => tap($item, fn ($i) => 
                $i->delivery_company_name = $i->deliveryCompany?->name ?? ''
            ));

        $deliveryCompanies = DeliveryCompany::orderBy('id')->get();

        return view('pages.delivery-services.index', compact('deliveryServices', 'deliveryCompanies', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'delivery_company_id' => 'required|integer',
            'default_cost' => 'required|numeric|min:0',
        ]);

        return $this->createRecord($data, 'Dodan je novi dostavni servis.');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['name', 'delivery_company_id', 'default_cost']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Custom methods used by this controller
    |--------------------------------------------------------------------------------------------
    */
    public function updateVisibility(Request $request, $id)
    {
        $receipt = DeliveryService::findOrFail($id);
        $receipt->update(['in_use' => !$receipt->in_use]);
    }
}