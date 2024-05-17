<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Expense::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Reference Number',
            'Warehouse Name',
            'Category ID',
            'Amount',
            'Note',
            'Created At',
            'Updated At',
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->id,
            $expense->reference_number,
            $expense->warehouse_name,
            $expense->expense_category_id,
            $expense->amount,
            $expense->note,
            Carbon::parse($expense->created_at)->format('d-m-Y'), // Format created_at date
            Carbon::parse($expense->updated_at)->format('d-m-Y'), // Format updated_at date
        ];
    }

}
