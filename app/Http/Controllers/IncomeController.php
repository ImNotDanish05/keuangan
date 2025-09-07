<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Category;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $year = (int)($request->query('year', $now->year));
        $month = (int)($request->query('month', $now->month));

        $query = Income::with('category')
            ->orderByDesc('received_at')
            ->orderByDesc('id');

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        if (!$dateFrom || !$dateTo) {
            $dateFrom = sprintf('%04d-%02d-01', $year, $month);
            $dateTo = date('Y-m-t', strtotime($dateFrom));
        }
        $query->whereBetween('received_at', [$dateFrom, $dateTo]);

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }
        if ($q = $request->query('q')) {
            $query->where('description', 'like', "%$q%");
        }

        $incomes = $query->paginate(15)->withQueryString();
        $categories = Category::where('kind','income')->orderBy('name')->get();

        $total = (clone $query)->sum('amount');

        return view('incomes.index', compact('incomes','categories','dateFrom','dateTo','total','year','month'));
    }

    public function create()
    {
        $categories = Category::where('kind','income')->orderBy('name')->get();
        return view('incomes.create', compact('categories'));
    }

    public function store(IncomeRequest $request)
    {
        Income::create($request->validated());
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    public function edit(Income $income)
    {
        $categories = Category::where('kind','income')->orderBy('name')->get();
        return view('incomes.edit', compact('income','categories'));
    }

    public function update(IncomeRequest $request, Income $income)
    {
        $income->update($request->validated());
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil dihapus.');
    }
}

