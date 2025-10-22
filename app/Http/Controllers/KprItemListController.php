<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\KprItemList;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Traits\RecordManagement;

class KprItemListController extends Controller
{
    use RecordManagement;
    protected $modelClass = KprItemList::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function store(Request $request, $kprId)
    {
        $data = $request->validate([
            'receipt_id' => 'required',
        ]);

        $data['kpr_id'] = $kprId;

        return $this->createRecord($data, 'Račun je uspješno dodan!');
    }

    public function destroy($id): JsonResponse
    {
        return $this->deleteRecord($id);
    }
}
