<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

use App\Models\Expense;

class ExpenseController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }


    // POST function for saving new stuff
    public function add (Request $request, $id) {
        $validator = Validator::make($request->all(), [
        'type_id' => 'required',
        'expenseDate' => 'required',
        'expenseAmount' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/uredi-narudzbu/' . $id)
                ->withInput()
                ->withErrors($validator);
        }

        $expense = new Expense;
        $expense->amount = $request->expenseAmount;
        $expense->order_id = $id;
        $expense->type_id = $request->type_id;
        $expense->date = $request->expenseDate;
        $expense->note = $request->expenseNote;
        $expense->save();
    
        return redirect('/uredi-narudzbu/' . $id);
    }


    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {
        $record = Expense::findOrFail($id);

        $field = $request->input('field');
        $newValue = $request->input('newValue');

        $record->$field = $newValue;
        $record->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    
    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Expense::findOrFail($id);
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }
     

    public static function sumSingleOrderExpense($order_id) {
        $expenses = Expense::where('order_id', $order_id)->sum('amount');
        return number_format(($expenses), 2, ',');
    }
}
