<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        $data['user'] = $user;

        return view('admin.ku.kelompok.dashboard', $data);
    }

    public function getStats(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        try {
            $stats = [
                'total_kas' => $this->getTotalKas($kelompokId),
                'kas_bulan_ini' => $this->getKasBulanIni($kelompokId),
                'total_sodaqoh' => $this->getTotalSodaqoh($kelompokId),
                'total_jamaah' => $this->getTotalJamaah($kelompokId),
                'total_keluarga' => $this->getTotalKeluarga($kelompokId),
                'transaksi_bulan_ini' => $this->getTransaksiBulanIni($kelompokId),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik'
            ], 500);
        }
    }

    public function getChartData(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        try {
            $chartData = $this->getChartDataForKelompok($kelompokId);

            return response()->json([
                'success' => true,
                'chart' => $chartData
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading chart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data chart'
            ], 500);
        }
    }

    public function getActivities(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        try {
            $activities = $this->getRecentActivities($kelompokId);

            return response()->json([
                'success' => true,
                'activities' => $activities
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading activities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat aktivitas'
            ], 500);
        }
    }

    private function getDashboardData($kelompokId)
    {
        return [
            'info_kelompok' => DB::table('master_kelompok')
                ->where('kelompok_id', $kelompokId)
                ->first(),
            'total_keluarga' => $this->getTotalKeluarga($kelompokId),
            'total_jamaah' => $this->getTotalJamaah($kelompokId),
            'total_transaksi_bulan_ini' => $this->getTransaksiBulanIni($kelompokId),
            'pemasukan_bulan_ini' => $this->getKasBulanIni($kelompokId),
            'recent_activities' => $this->getRecentActivities($kelompokId, false) // tanpa mapping
        ];
    }

    // Helper methods
    private function getTotalKas($kelompokId)
    {
        return DB::table('transaksi')
            ->join('jamaah', 'transaksi.jamaah_id', '=', 'jamaah.jamaah_id')
            ->join('master_kontribusi', 'transaksi.kode_kontribusi', '=', 'master_kontribusi.kode_kontribusi')
            ->where('jamaah.kelompok_id', $kelompokId)
            ->where('transaksi.status', 'VERIFIED')
            ->sum('transaksi.jumlah') ?? 0;
    }

    private function getKasBulanIni($kelompokId)
    {
        return DB::table('transaksi')
            ->join('jamaah', 'transaksi.jamaah_id', '=', 'jamaah.jamaah_id')
            ->join('master_kontribusi', 'transaksi.kode_kontribusi', '=', 'master_kontribusi.kode_kontribusi')
            ->where('jamaah.kelompok_id', $kelompokId)
            ->where('transaksi.status', 'VERIFIED')
            ->whereMonth('transaksi.tgl_transaksi', now()->month)
            ->whereYear('transaksi.tgl_transaksi', now()->year)
            ->sum('transaksi.jumlah') ?? 0;
    }

    private function getTotalSodaqoh($kelompokId)
    {
        return DB::table('transaksi')
            ->join('jamaah', 'transaksi.jamaah_id', '=', 'jamaah.jamaah_id')
            ->join('master_kontribusi', 'transaksi.kode_kontribusi', '=', 'master_kontribusi.kode_kontribusi')
            ->where('jamaah.kelompok_id', $kelompokId)
            ->where('transaksi.status', 'VERIFIED')
            ->where('master_kontribusi.nama_kontribusi', 'SODAQOH')
            ->sum('transaksi.jumlah') ?? 0;
    }

    private function getTotalJamaah($kelompokId)
    {
        return DB::table('jamaah')
            ->where('kelompok_id', $kelompokId)
            ->where('is_aktif', true)
            ->count();
    }

    private function getTotalKeluarga($kelompokId)
    {
        return DB::table('keluarga')
            ->where('kelompok_id', $kelompokId)
            ->count();
    }

    private function getTransaksiBulanIni($kelompokId)
    {
        return DB::table('transaksi')
            ->join('jamaah', 'transaksi.jamaah_id', '=', 'jamaah.jamaah_id')
            ->where('jamaah.kelompok_id', $kelompokId)
            ->where('transaksi.status', 'VERIFIED')
            ->whereMonth('transaksi.tgl_transaksi', now()->month)
            ->whereYear('transaksi.tgl_transaksi', now()->year)
            ->count();
    }

    private function getChartDataForKelompok($kelompokId)
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            $months[] = $monthYear;

            $total = DB::table('transaksi')
                ->join('jamaah', 'transaksi.jamaah_id', '=', 'jamaah.jamaah_id')
                ->join('master_kontribusi', 'transaksi.kode_kontribusi', '=', 'master_kontribusi.kode_kontribusi')
                ->where('jamaah.kelompok_id', $kelompokId)
                ->where('transaksi.status', 'VERIFIED')
                ->whereMonth('transaksi.tgl_transaksi', $date->month)
                ->whereYear('transaksi.tgl_transaksi', $date->year)
                ->sum('transaksi.jumlah') ?? 0;

            $data[] = $total;
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getRecentActivities($kelompokId, $mapped = true)
    {
        $activities = DB::table('transaksi')
            ->join('jamaah', 'transaksi.jamaah_id', '=', 'jamaah.jamaah_id')
            ->join('master_kontribusi', 'transaksi.kode_kontribusi', '=', 'master_kontribusi.kode_kontribusi')
            ->where('jamaah.kelompok_id', $kelompokId)
            ->where('transaksi.status', 'VERIFIED')
            ->orderBy('transaksi.created_at', 'desc')
            ->limit(5)
            ->get(['transaksi.*', 'jamaah.nama_lengkap', 'master_kontribusi.nama_kontribusi']);

        if (!$mapped) {
            return $activities;
        }

        return $activities->map(function ($item) {
            return [
                'type' => 'payment',
                'description' => $item->nama_lengkap . ' - ' . $item->nama_kontribusi . ' (Rp ' . number_format($item->jumlah, 0, ',', '.') . ')',
                'created_at' => $item->created_at
            ];
        })->toArray();
    }
}
