<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $year = (int)($request->query('year', $now->year));
        $month = (int)($request->query('month', $now->month));

        $query = Expense::with('category')
            ->orderByDesc('spent_at')
            ->orderByDesc('id');

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        if (!$dateFrom || !$dateTo) {
            $dateFrom = sprintf('%04d-%02d-01', $year, $month);
            $dateTo = date('Y-m-t', strtotime($dateFrom));
        }
        $query->whereBetween('spent_at', [$dateFrom, $dateTo]);

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }
        if ($q = $request->query('q')) {
            $query->where('description', 'like', "%$q%");
        }

        $expenses = $query->paginate(15)->withQueryString();
        $categories = Category::where('kind','expense')->orderBy('name')->get();

        $total = (clone $query)->sum('amount');

        return view('expenses.index', compact('expenses','categories','dateFrom','dateTo','total','year','month'));
    }

    public function create()
    {
        $categories = Category::where('kind','expense')->orderBy('name')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(ExpenseRequest $request)
    {
        Expense::create($request->validated());
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit(Expense $expense)
    {
        $categories = Category::where('kind','expense')->orderBy('name')->get();
        return view('expenses.edit', compact('expense','categories'));
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}

