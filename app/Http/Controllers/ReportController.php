<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $now = now();
        $year = (int)($request->query('year', $now->year));
        $month = (int)($request->query('month', $now->month));

        $fileName = sprintf('transaksi_%04d_%02d.csv', $year, $month);

        $rows = [];
        $rows[] = ['tipe','tanggal','kategori','nominal','deskripsi'];

        $expenses = Expense::with('category')->forMonth($year,$month)->orderBy('spent_at')->get();
        foreach ($expenses as $e) {
            $rows[] = ['pengeluaran', $e->spent_at->format('Y-m-d'), $e->category?->name, $e->amount, $e->description];
        }
        $incomes = Income::with('category')->forMonth($year,$month)->orderBy('received_at')->get();
        foreach ($incomes as $i) {
            $rows[] = ['pemasukan', $i->received_at->format('Y-m-d'), $i->category?->name, $i->amount, $i->description];
        }

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}

