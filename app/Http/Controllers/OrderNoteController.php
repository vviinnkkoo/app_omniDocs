<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderNote;

use App\Traits\RecordManagement;

class OrderNoteController extends Controller
{
    use RecordManagement;
    protected $modelClass = OrderNote::class;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | CRUD methods
    |--------------------------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'note' => 'required|string|min:2|max:1000',
            'order_id' => 'required|integer',
        ]);

        return $this->createRecord($data, 'Napomena uspješno dodana.');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['note']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }

}
