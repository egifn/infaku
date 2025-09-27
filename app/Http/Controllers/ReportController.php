<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncomeExport;

class ReportController extends Controller
{
    public function profitLoss(Request $request) {
        $date = $request->date ?? today();

        $income = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->whereDate('payments.created_at', $date)
            ->sum('payments.amount');

        $expenses = DB::table('expenses')
            ->whereDate('date', $date)
            ->sum('amount');

        $profit = $income - $expenses;

        return response()->json(compact('income', 'expenses', 'profit'));
    }

    public function exportProfitPdf()
    {
        $data = []; // Isi data laporan sesuai kebutuhan
        $pdf = Pdf::loadView('reports.profit_pdf', $data);
        return $pdf->download('laporan_keuangan.pdf');
    }

    public function exportIncomeExcel()
    {
        return Excel::download(new IncomeExport, 'pemasukan.xlsx');
    }
}
