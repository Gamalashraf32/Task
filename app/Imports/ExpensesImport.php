<?php

namespace App\Imports;

use App\Models\Expense;
use App\Models\ExpensesCategory;
use Maatwebsite\Excel\Concerns\ToModel;

class ExpensesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $category = ExpensesCategory::find($row[2]);

        if (!$category) {
            return null;
        }
        return new Expense([
            'reference_number' => $row[0],
            'warehouse_name' => $row[1],
            'expense_category_id' => $row[2],
            'amount' => $row[3],
            'note' => $row[4],
        ]);
    }
}
