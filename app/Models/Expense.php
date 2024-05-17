<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference_number',
        'warehouse_name',
        'expense_category_id',
        'amount',
        'note',
    ];

    public function category()
    {
        return $this->belongsTo(ExpensesCategory::class, 'expense_category_id');
    }
}
