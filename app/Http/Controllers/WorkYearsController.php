<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WorkYears;

use App\Traits\RecordManagement;

class WorkYearsController extends Controller
{
    use RecordManagement;
    protected $modelClass = WorkYears::class;

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

        $years = WorkYears::search($search, ['year'])
                        ->orderBy('year', 'desc')
                        ->paginate(25);

        return view('pages.work-years.index', compact('years', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|integer',
        ]);

        return $this->createRecord($data, 'Radna godina je uspješno dodana!');
    }

    public function update(Request $request, $id)
    {
        return $this->updateRecord($request, $id, ['year']);
    }

    public function destroy($id)
    {
        return $this->deleteRecord($id);
    }
}
