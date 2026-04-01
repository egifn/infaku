<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        $infoKelompok = DB::table('master_kelompok')
            ->where('kelompok_id', $kelompokId)
            ->first();

        return view('admin.ku.kelompok.laporan', [
            'user' => $user,
            'info_kelompok' => $infoKelompok
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];
            // dd($kelompokId);

            $search = $request->get('search', '');
            $status = $request->get('status', '');
            $month = $request->get('month', '');
            $year = $request->get('year', '');
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $query = DB::table('laporan_keuangan')
                ->where('kelompok_id', $kelompokId);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_laporan', 'like', "%{$search}%")
                        ->orWhere('judul_laporan', 'like', "%{$search}%");
                });
            }

            if (!empty($status) && $status !== 'ALL') {
                $query->where('status_laporan', $status);
            }

            if (!empty($month)) {
                $query->whereMonth('tgl_awal', $month);
            }

            if (!empty($year)) {
                $query->whereYear('tgl_awal', $year);
            }

            $total = $query->count();
            $data = $query->select('*')
                ->orderBy('created_at', 'desc')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data,
                'current_page' => (int)$page,
                'last_page' => ceil($total / $perPage),
                'total' => $total
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting laporan data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data laporan'
            ], 500);
        }
    }

    public function preview(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $tglAwal = $request->get('tgl_awal');
            $tglAkhir = $request->get('tgl_akhir');
            $tipeLaporan = $request->get('tipe_laporan', 'BULANAN');
            $masterKontribusiId = $request->get('master_kontribusi_id');

            $masterKontribusi = null;
            $kodeKontribusi = null;
            if (!empty($masterKontribusiId)) {
                $masterKontribusi = DB::table('master_kontribusi')
                    ->where('id', $masterKontribusiId)
                    ->where('created_by', $kelompokId)
                    ->first();
                $kodeKontribusi = $masterKontribusi ? $masterKontribusi->kode_kontribusi : null;
            }

            if (!$tglAwal || !$tglAkhir) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal awal dan akhir harus diisi'
                ]);
            }

            // Query transaksi dalam periode
            $transaksi = DB::table('transaksi as t')
                ->join('jamaah as j', 't.jamaah_id', '=', 'j.jamaah_id')
                ->leftJoin('master_kontribusi as mk', 't.kategori_id', '=', 'mk.kode_kontribusi')
                ->leftJoin('sub_kontribusi as sk', DB::raw('CAST(t.sub_kategori_id AS UNSIGNED)'), '=', 'sk.sub_kat_id')
                ->where('j.kelompok_id', $kelompokId)
                ->where('t.status', 'VERIFIED')
                ->whereBetween('t.tgl_transaksi', [$tglAwal, $tglAkhir])
                ->when($kodeKontribusi, function ($q) use ($kodeKontribusi) {
                    return $q->where('t.kode_kontribusi', $kodeKontribusi);
                })
                ->select(
                    't.*',
                    'j.nama_lengkap as nama_jamaah',
                    'mk.nama_kontribusi',
                    'sk.nama_kontribusi',
                    'sk.jenis as jenis_kontribusi',
                    'sk.value as nilai_kontribusi'
                )
                ->get();

            // Hitung summary
            $totalPemasukan = $transaksi->sum('jumlah');
            $totalTransaksi = $transaksi->count();

            // Group by kategori
            $perKategori = $transaksi->groupBy('nama_kontribusi')->map(function ($items) {
                return [
                    'total' => $items->sum('jumlah'),
                    'count' => $items->count(),
                    'subs' => $items->groupBy('nama_kontribusi')->map(function ($subItems) {
                        return [
                            'total' => $subItems->sum('jumlah'),
                            'count' => $subItems->count(),
                            'jenis' => $subItems->first()->jenis_kontribusi,
                            'nilai' => $subItems->first()->nilai_kontribusi
                        ];
                    })
                ];
            });

            // Group by sub kontribusi
            $perSubKontribusi = $transaksi->whereNotNull('nama_kontribusi')
                ->groupBy('nama_kontribusi')
                ->map(function ($items) {
                    return [
                        'total' => $items->sum('jumlah'),
                        'count' => $items->count(),
                        'kategori' => $items->first()->nama_kontribusi,
                        'jenis' => $items->first()->jenis_kontribusi,
                        'nilai' => $items->first()->nilai_kontribusi
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => [
                        'total_pemasukan' => $totalPemasukan,
                        'total_transaksi' => $totalTransaksi,
                        'tgl_awal' => $tglAwal,
                        'tgl_akhir' => $tglAkhir
                    ],
                    'per_kategori' => $perKategori,
                    'per_sub_kontribusi' => $perSubKontribusi,
                    'transaksi_sample' => $transaksi->take(5)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error preview laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan preview laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        dd($request->all());
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $request->merge([
                'master_kontribusi_id' => $request->input('master_kontribusi_id') ?: null
            ]);

            $validator = Validator::make($request->all(), [
                'judul_laporan' => 'required|string|max:255',
                'tgl_awal' => 'required|date',
                'tgl_akhir' => 'required|date',
                'tipe_laporan' => 'required|in:HARIAN,MINGGUAN,BULANAN,TAHUNAN,KHUSUS',
                'catatan' => 'nullable|string',
                'master_kontribusi_id' => 'nullable'
            ]);
            if ($validator->fails()) {
                Log::error('Validation failed on store laporan', [
                    'errors' => $validator->errors()->toArray(),
                    'payload' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }
            $validated = $validator->validated();

            // Generate kode laporan
            $lastLaporan = DB::table('laporan_keuangan')
                ->where('kelompok_id', $kelompokId)
                ->orderBy('created_at', 'desc')
                ->first();

            $nextNumber = $lastLaporan ? (int)substr($lastLaporan->kode_laporan, 4) + 1 : 1;
            $kodeLaporan = 'LAP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Hitung data transaksi
            $masterKontribusi = null;
            $kodeKontribusi = null;
            if (!empty($validated['master_kontribusi_id'])) {
                $masterKontribusi = DB::table('master_kontribusi')
                    ->where('id', $validated['master_kontribusi_id'])
                    ->where('created_by', $kelompokId)
                    ->first();
                $kodeKontribusi = $masterKontribusi ? $masterKontribusi->kode_kontribusi : null;
            }

            $transaksi = DB::table('transaksi as t')
                ->join('jamaah as j', 't.jamaah_id', '=', 'j.jamaah_id')
                ->where('j.kelompok_id', $kelompokId)
                ->where('t.status', 'VERIFIED')
                ->whereBetween('t.tgl_transaksi', [$validated['tgl_awal'], $validated['tgl_akhir']])
                ->when($kodeKontribusi, function ($q) use ($kodeKontribusi) {
                    return $q->where('t.kode_kontribusi', $kodeKontribusi);
                })
                ->get();

            $totalPemasukan = $transaksi->sum('jumlah');
            $totalTransaksi = $transaksi->count();

            // Simpan ke database
            $laporanData = [
                'kode_laporan' => $kodeLaporan,
                'kelompok_id' => $kelompokId,
                'judul_laporan' => $validated['judul_laporan'],
                'tgl_awal' => $validated['tgl_awal'],
                'tgl_akhir' => $validated['tgl_akhir'],
                'tipe_laporan' => $validated['tipe_laporan'],
                'tipe_kontribusi' => $masterKontribusi ? $masterKontribusi->jenis : 'BILLING',
                'total_pemasukan' => $totalPemasukan,
                'total_transaksi' => $totalTransaksi,
                'total_pengeluaran' => 0, // Default 0, bisa diisi nanti
                'saldo_akhir' => $totalPemasukan,
                'status_laporan' => 'PUBLISHED',
                'created_by' => $user['user_id'],
                'created_at' => now()
            ];

            DB::table('laporan_keuangan')->insert($laporanData);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $user['user_id'],
                'action' => 'CREATE_LAPORAN',
                'description' => 'Membuat laporan: ' . $validated['judul_laporan'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dibuat',
                'data' => [
                    'kode_laporan' => $kodeLaporan,
                    'laporan_id' => DB::getPdo()->lastInsertId()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $laporan = DB::table('laporan_keuangan')
                ->where('laporan_id', $id)
                ->first();

            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $laporan
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail laporan'
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $laporan = DB::table('laporan_keuangan')
                ->where('laporan_id', $id)
                ->first();

            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }

            // Get detailed transactions
            $transaksi = DB::table('transaksi as t')
                ->join('jamaah as j', 't.jamaah_id', '=', 'j.jamaah_id')
                ->leftJoin('master_kontribusi as mk', 't.kategori_id', '=', 'mk.kode_kontribusi')
                ->leftJoin('sub_kontribusi as sk', DB::raw('CAST(t.sub_kategori_id AS UNSIGNED)'), '=', 'sk.sub_kat_id')
                ->where('j.kelompok_id', $laporan->kelompok_id)
                ->where('t.status', 'VERIFIED')
                ->whereBetween('t.tgl_transaksi', [$laporan->tgl_awal, $laporan->tgl_akhir])
                ->select(
                    't.*',
                    'j.nama_lengkap as nama_jamaah',
                    'mk.nama_kontribusi',
                    'sk.nama_kontribusi',
                    'sk.jenis as jenis_kontribusi',
                    'sk.value as nilai_kontribusi'
                )
                ->orderBy('t.tgl_transaksi', 'desc')
                ->get();

            // Group by kategori
            $perKategori = $transaksi->groupBy('nama_kontribusi')->map(function ($items, $kategori) {
                return [
                    'kategori' => $kategori,
                    'total' => $items->sum('jumlah'),
                    'count' => $items->count(),
                    'subs' => $items->groupBy('nama_kontribusi')->map(function ($subItems, $subKontribusi) {
                        return [
                            'sub_kontribusi' => $subKontribusi,
                            'total' => $subItems->sum('jumlah'),
                            'count' => $subItems->count(),
                            'jenis' => $subItems->first()->jenis_kontribusi,
                            'nilai' => $subItems->first()->nilai_kontribusi
                        ];
                    })->values()
                ];
            })->values();

            // Group by sub kontribusi
            $perSubKontribusi = $transaksi->whereNotNull('nama_kontribusi')
                ->groupBy('nama_kontribusi')
                ->map(function ($items) {
                    return [
                        'nama' => $items->first()->nama_kontribusi,
                        'kategori' => $items->first()->nama_kontribusi,
                        'total' => $items->sum('jumlah'),
                        'count' => $items->count(),
                        'jenis' => $items->first()->jenis_kontribusi,
                        'nilai' => $items->first()->nilai_kontribusi
                    ];
                })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'laporan' => $laporan,
                    'summary' => [
                        'total_pemasukan' => $laporan->total_pemasukan,
                        'total_transaksi' => $laporan->total_transaksi,
                        'tgl_awal' => $laporan->tgl_awal,
                        'tgl_akhir' => $laporan->tgl_akhir
                    ],
                    'per_kategori' => $perKategori,
                    'per_sub_kontribusi' => $perSubKontribusi,
                    'transaksi' => $transaksi->take(50) // Limit untuk performance
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting laporan detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail laporan'
            ], 500);
        }
    }

    public function settle($id)
    {
        try {
            DB::table('laporan_keuangan')
                ->where('laporan_id', $id)
                ->update([
                    'status_laporan' => 'PUBLISHED',
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil disettle'
            ]);
        } catch (\Exception $e) {
            Log::error('Error settling laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal settle laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('laporan_keuangan')
                ->where('laporan_id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export($id)
    {
        try {
            $laporan = DB::table('laporan_keuangan')
                ->where('laporan_id', $id)
                ->first();

            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ]);
            }

            // Get data for export
            $transaksi = DB::table('transaksi as t')
                ->join('jamaah as j', 't.jamaah_id', '=', 'j.jamaah_id')
                ->leftJoin('master_kontribusi as mk', 't.kategori_id', '=', 'mk.kode_kontribusi')
                ->leftJoin('sub_kontribusi as sk', DB::raw('CAST(t.sub_kategori_id AS UNSIGNED)'), '=', 'sk.sub_kat_id')
                ->where('j.kelompok_id', $laporan->kelompok_id)
                ->where('t.status', 'VERIFIED')
                ->whereBetween('t.tgl_transaksi', [$laporan->tgl_awal, $laporan->tgl_akhir])
                ->select(
                    't.kode_transaksi',
                    't.tgl_transaksi',
                    'j.nama_lengkap',
                    'mk.nama_kontribusi',
                    'sk.nama_kontribusi',
                    't.jumlah',
                    't.metode_bayar',
                    't.keterangan'
                )
                ->orderBy('t.tgl_transaksi')
                ->get();

            $filename = 'laporan-' . $laporan->kode_laporan . '-' . date('Ymd') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($laporan, $transaksi) {
                $file = fopen('php://output', 'w');

                // Header laporan
                fputcsv($file, ['LAPORAN KEUANGAN']);
                fputcsv($file, [$laporan->judul_laporan]);
                fputcsv($file, ['Periode: ' . $laporan->tgl_awal . ' s/d ' . $laporan->tgl_akhir]);
                fputcsv($file, ['']);

                // Summary
                fputcsv($file, ['SUMMARY']);
                fputcsv($file, ['Total Pemasukan', 'Total Transaksi']);
                fputcsv($file, [
                    number_format($laporan->total_pemasukan, 2),
                    $laporan->total_transaksi
                ]);
                fputcsv($file, ['']);

                // Detail transaksi
                fputcsv($file, ['DETAIL TRANSAKSI']);
                fputcsv($file, [
                    'Kode Transaksi',
                    'Tanggal',
                    'Nama Jamaah',
                    'Kategori',
                    'Sub Kontribusi',
                    'Jumlah',
                    'Metode Bayar',
                    'Keterangan'
                ]);

                foreach ($transaksi as $row) {
                    fputcsv($file, [
                        $row->kode_transaksi,
                        $row->tgl_transaksi,
                        $row->nama_lengkap,
                        $row->nama_kontribusi,
                        $row->nama_kontribusi,
                        number_format($row->jumlah, 2),
                        $row->metode_bayar,
                        $row->keterangan
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal export laporan'
            ], 500);
        }
    }

    public function print($id)
    {
        try {
            $user = request()->session()->get('user');
            $laporan = DB::table('laporan_keuangan')
                ->where('laporan_id', $id)
                ->first();

            if (!$laporan) {
                return back()->with('error', 'Laporan tidak ditemukan');
            }

            $infoKelompok = DB::table('master_kelompok')
                ->where('kelompok_id', $laporan->kelompok_id)
                ->first();

            // Get data for print
            $transaksi = DB::table('transaksi as t')
                ->join('jamaah as j', 't.jamaah_id', '=', 'j.jamaah_id')
                ->leftJoin('master_kontribusi as mk', 't.kategori_id', '=', 'mk.kode_kontribusi')
                ->leftJoin('sub_kontribusi as sk', DB::raw('CAST(t.sub_kategori_id AS UNSIGNED)'), '=', 'sk.sub_kat_id')
                ->where('j.kelompok_id', $laporan->kelompok_id)
                ->where('t.status', 'VERIFIED')
                ->whereBetween('t.tgl_transaksi', [$laporan->tgl_awal, $laporan->tgl_akhir])
                ->select(
                    't.*',
                    'j.nama_lengkap as nama_jamaah',
                    'mk.nama_kontribusi',
                    'sk.nama_kontribusi'
                )
                ->orderBy('t.tgl_transaksi')
                ->get();

            // Group by kategori for summary
            $perKategori = $transaksi->groupBy('nama_kontribusi')->map(function ($items, $kategori) {
                return [
                    'kategori' => $kategori,
                    'total' => $items->sum('jumlah'),
                    'count' => $items->count()
                ];
            })->values();

            return view('admin.ku.kelompok.laporan.print', [
                'laporan' => $laporan,
                'info_kelompok' => $infoKelompok,
                'user' => $user,
                'transaksi' => $transaksi,
                'per_kategori' => $perKategori
            ]);
        } catch (\Exception $e) {
            Log::error('Error printing laporan: ' . $e->getMessage());
            return back()->with('error', 'Gagal mencetak laporan');
        }
    }
}
