<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $year = (int)($request->query('year', $now->year));
        $month = (int)($request->query('month', $now->month));

        $totalIncome = Income::forMonth($year, $month)->sum('amount');
        $totalExpense = Expense::forMonth($year, $month)->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $latestExpenses = Expense::with('category')
            ->forMonth($year, $month)
            ->orderByDesc('spent_at')
            ->orderByDesc('id')
            ->take(10)->get()->map(function ($e) {
                return [
                    'type' => 'expense',
                    'date' => $e->spent_at,
                    'category' => $e->category?->name,
                    'amount' => $e->amount,
                    'description' => $e->description,
                ];
            })->toArray();

        $latestIncomes = Income::with('category')
            ->forMonth($year, $month)
            ->orderByDesc('received_at')
            ->orderByDesc('id')
            ->take(10)->get()->map(function ($i) {
                return [
                    'type' => 'income',
                    'date' => $i->received_at,
                    'category' => $i->category?->name,
                    'amount' => $i->amount,
                    'description' => $i->description,
                ];
            })->toArray();

        $latest = collect(array_merge($latestExpenses, $latestIncomes))
            ->sortByDesc(function ($x) { return $x['date']->timestamp; })
            ->take(10);

        // Expense sum by category for selected month (for donut chart)
        $expenseByCategory = Expense::selectRaw('categories.name as label, SUM(expenses.amount) as total')
            ->join('categories', 'categories.id', '=', 'expenses.category_id')
            ->forMonth($year, $month)
            ->groupBy('categories.name')
            ->orderBy('categories.name')
            ->get();

        // Charts datasets
        // 1) Summary bar (current month)
        $chartSummary = [
            'labels' => ['Pemasukan', 'Pengeluaran'],
            'data' => [round((float)$totalIncome,2), round((float)$totalExpense,2)],
        ];

        // 2) Donut by category (current month)
        $chartDonut = [
            'labels' => $expenseByCategory->pluck('label'),
            'data' => $expenseByCategory->pluck('total')->map(fn($x)=> round((float)$x,2)),
        ];

        // Helpers for month range
        $end = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
        $start = (clone $end)->subMonths(11)->startOfMonth();

        $monthKeys = [];
        $monthLabels = [];
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $key = $cursor->format('Y-m');
            $monthKeys[] = $key;
            $monthLabels[] = $cursor->isoFormat('MMM YY');
            $cursor->addMonth();
        }

        // Income grouped by month
        $incomeMonthly = Income::selectRaw('DATE_FORMAT(received_at, "%Y-%m") as ym, SUM(amount) as total')
            ->whereBetween('received_at', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')->pluck('total','ym');

        // Expense grouped by month
        $expenseMonthly = Expense::selectRaw('DATE_FORMAT(spent_at, "%Y-%m") as ym, SUM(amount) as total')
            ->whereBetween('spent_at', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')->pluck('total','ym');

        $incomeMonths = [];
        $expenseMonths = [];
        $saldoMonths = [];
        foreach ($monthKeys as $k) {
            $inc = (float)($incomeMonthly[$k] ?? 0);
            $exp = (float)($expenseMonthly[$k] ?? 0);
            $incomeMonths[] = round($inc,2);
            $expenseMonths[] = round($exp,2);
            $saldoMonths[] = round($inc - $exp,2);
        }

        // Daily details for selected month
        $firstDay = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $dayLabels = [];
        for ($d=1; $d <= $daysInMonth; $d++) { $dayLabels[] = (string)$d; }

        $incomeDailyData = Income::selectRaw('DAY(received_at) as d, SUM(amount) as total')
            ->forMonth($year,$month)->groupBy('d')->pluck('total','d');
        $expenseDailyData = Expense::selectRaw('DAY(spent_at) as d, SUM(amount) as total')
            ->forMonth($year,$month)->groupBy('d')->pluck('total','d');

        $incomeDaily = [];
        $expenseDaily = [];
        for ($d=1; $d <= $daysInMonth; $d++) {
            $incomeDaily[] = round((float)($incomeDailyData[$d] ?? 0),2);
            $expenseDaily[] = round((float)($expenseDailyData[$d] ?? 0),2);
        }

        $chart = compact(
            'chartSummary', 'chartDonut', 'monthLabels', 'incomeMonths', 'expenseMonths', 'saldoMonths', 'dayLabels', 'incomeDaily', 'expenseDaily'
        );

        return view('dashboard.index', compact('year','month','totalIncome','totalExpense','balance','latest','expenseByCategory','chart'));
    }
}
