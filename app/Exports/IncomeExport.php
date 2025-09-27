<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class IncomeExport implements FromView
{
    public function view(): View
    {
        // Ganti dengan data sesuai kebutuhan
        $income = 1000000;
        $date = now()->toDateString();
        return view('exports.income_excel', compact('income', 'date'));
    }
}
