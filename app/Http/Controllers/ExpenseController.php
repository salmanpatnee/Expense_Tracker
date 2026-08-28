<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Expense::orderBy('date', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        return Expense::create($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->noContent();
    }

    /**
     * Sum expenses grouped by category, including categories with no expenses.
     */
    public function totals()
    {
        return Category::query()
            ->leftJoin('expenses', 'expenses.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', DB::raw('COALESCE(SUM(expenses.amount), 0) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.id')
            ->get();
    }
}
