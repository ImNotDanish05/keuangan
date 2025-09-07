<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $expense = ['Makan/Minum', 'Transportasi', 'Internet', 'Hiburan', 'Belanja', 'Lainnya'];
        $income = ['Gaji', 'Tabungan', 'Freelance', 'Hadiah', 'Lainnya'];

        foreach ($expense as $name) {
            DB::table('categories')->updateOrInsert(
                ['name' => $name, 'kind' => 'expense'],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

        foreach ($income as $name) {
            DB::table('categories')->updateOrInsert(
                ['name' => $name, 'kind' => 'income'],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}

