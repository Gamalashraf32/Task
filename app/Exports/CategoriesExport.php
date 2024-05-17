<?php

namespace App\Exports;

use App\Models\ExpensesCategory;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ExpensesCategory::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
            'Name',
            'Created At',
            'Updated At',
        ];
    }
    public function map($category): array
    {
        return [
            $category->id,
            $category->code,
            $category->name,
            Carbon::parse($category->created_at)->format('d-m-Y'), // Format created_at date
            Carbon::parse($category->updated_at)->format('d-m-Y'), // Format updated_at date
        ];
    }
}
