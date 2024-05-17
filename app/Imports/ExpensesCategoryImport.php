<?php

namespace App\Imports;

use App\Models\ExpensesCategory;
use Maatwebsite\Excel\Concerns\ToModel;

class ExpensesCategoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $existingCategory = ExpensesCategory::where('code', $row[0])->first();

        if ($existingCategory) {
            return null;
        }
        return new ExpensesCategory([
            'code' => $row[0],
            'name' => $row[1],
        ]);
    }
}
