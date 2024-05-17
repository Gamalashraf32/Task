<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Imports\ExpensesCategoryImport;
use App\Models\ExpensesCategory;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    use ResponseTrait;

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ExpensesCategoryImport, $request->file('file'));

        return $this->returnData('Categories added successfully', [], 200);
    }

    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    public function index(Request $request)
    {
        $search = $request->search ;

        $categories = ExpensesCategory::query()
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
            })
            ->get();

        return $this->returnData('true', $categories, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:expenses_categories,code|max:255',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->returnData('false',[],422,$validator->errors());
        }

        $category = ExpensesCategory::create([
            'code'=>$request->code,
            'name'=>$request->name
        ]);
        return $this->returnData('ture',$category,200);
    }

    public function show($id)
    {
        $category = ExpensesCategory::find($id);

        if (!$category) {
            return $this->returnData('false',[],404,'category not found');
        }

        return $this->returnData('true',$category,200);
    }


    public function update(Request $request, $id)
    {
        $category = ExpensesCategory::find($id);

        if (!$category) {
            return $this->returnData('false',[],404,'category not found');
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:expenses_categories,code,' . $id . '|max:255',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->returnData('false',[],422,$validator->errors());
        }

        $category->update([
            'code'=>$request->code,
            'name' => $request->name,
        ]);
        return $this->returnData('true',$category,200);
    }

    public function destroy($id)
    {
        $category = ExpensesCategory::find($id);

        if (!$category) {
            return $this->returnData('false',[],404,'category not found');
        }

        $category->delete();
        return $this->returnData('category deleted successfully',[],200);
    }

}
