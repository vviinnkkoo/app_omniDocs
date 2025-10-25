<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KprItemList;
use App\Traits\RecordManagement;

class KprItemListController extends Controller
{
    use RecordManagement;
    protected $modelClass = \App\Models\KprItemList::class;

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

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}
