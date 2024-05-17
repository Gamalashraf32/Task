<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Exports\ExpensesExport;
use App\Imports\ExpensesImport;
use App\Models\Expense;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    use ResponseTrait;
    public function export()
    {
        return Excel::download(new ExpensesExport, 'expenses.xlsx');
    }
    public function import(Request $request)
    {
        Excel::import(new ExpensesImport, $request->file('file'));

        return $this->returnData('Expenses added successfully', [], 200);
    }
    public function index(Request $request)
    {
        $search = $request->search;
        $warehouse = $request->warehouse;
        $date = $request->date;

        $expenses = Expense::query()
            ->when($search, function ($query, $search) {
                $query->where('reference_number', 'like', "%$search%");
            })
            ->when($warehouse, function ($query, $warehouse) {
                $query->where('warehouse_name', $warehouse);
            })
            ->when($date, function ($query, $date) {
                $query->whereDate('created_at', Carbon::parse($date));
            })
            ->with('category')
            ->get();

        return $this->returnData('true', $expenses, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reference_number' => 'required|string|unique:expenses,reference_number|max:255',
            'warehouse_name' => 'required|string|max:255',
            'expense_category_id' => 'required|exists:expenses_categories,id',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->returnData('false', [], 422, $validator->errors());
        }

        $expense = Expense::create([
            'reference_number' => $request->reference_number,
            'warehouse_name' => $request->warehouse_name,
            'expense_category_id' => $request->expense_category_id,
            'amount' =>  $request->amount,
            'note' => $request->note,
        ]);
        return $this->returnData('true', $expense, 201);
    }

    public function show($id)
    {
        $expense = Expense::with('category')->find($id);

        if (!$expense) {
            return $this->returnData('false', [], 404, 'Expense not found');
        }

        return $this->returnData('true', $expense, 200);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return $this->returnData('false', [], 404, 'Expense not found');
        }

        $validator = Validator::make($request->all(), [
            'reference_number' => 'required|string|unique:expenses,reference_number,' . $id . '|max:255',
            'warehouse_name' => 'required|string|max:255',
            'expense_category_id' => 'required|exists:expenses_categories,id',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->returnData('false', [], 422, $validator->errors());
        }

        $expense->update($request->all());
        return $this->returnData('true', $expense, 200);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return $this->returnData('false', [], 404, 'Expense not found');
        }

        $expense->delete();
        return $this->returnData('Expense deleted successfully', [], 200);
    }

}
