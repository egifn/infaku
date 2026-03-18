<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        $infoKelompok = DB::table('master_kelompok')
            ->where('kelompok_id', $kelompokId)
            ->first();

        return view('admin.ku.kelompok.transaksi', [
            'user' => $user,
            'info_kelompok' => $infoKelompok
        ]);
    }

    public function create(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        $infoKelompok = DB::table('master_kelompok')
            ->where('kelompok_id', $kelompokId)
            ->first();

        return view('admin.ku.kelompok.input_pembayaran', [
            'user' => $user,
            'info_kelompok' => $infoKelompok
        ]);
    }

    public function getJamaahOptions(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $search = $request->get('search', '');

            $query = DB::table('jamaah')
                ->where('kelompok_id', $kelompokId)
                ->where('is_aktif', true);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('jamaah_id', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('telepon', 'like', "%{$search}%");
                });
            }

            $jamaahs = $query->select('jamaah_id', 'nik', 'nama_lengkap', 'telepon', 'alamat')
                ->orderBy('nama_lengkap')
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $jamaahs
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting jamaah options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jamaah'
            ], 500);
        }
    }

    public function getKontribusiOptions(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $status = $request->get('is_aktif', 1);
            $search = $request->get('search', '');

            $query = DB::table('master_kontribusi')
                ->where('is_aktif', 1)
                ->where('created_by', $kelompokId);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kontribusi', 'like', "%{$search}%");;
                });
            }

            if ($status !== null) {
                $query->where('is_aktif', $status);
            }

            $kontribusis = $query->select('id', 'kode_kontribusi', 'nama_kontribusi',)
                ->orderBy('id')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $kontribusis
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting kontribusi options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kontribusi'
            ], 500);
        }
    }

    public function getSubKontribusiOptions($masterKontribusiId)
    {
        try {
            $subKontribusis = DB::table('sub_kontribusi')
                ->where('kode_kontribusi', $masterKontribusiId)
                ->where('is_active', true)
                ->select('id', 'kode_kontribusi', 'nama_kontribusi', 'jenis', 'value', 'level')
                ->orderBy('id')
                ->get();

            // Kelompokkan berdasarkan level (pusat, daerah, desa, kelompok)
            $levelOrder = ['pusat', 'daerah', 'desa', 'kelompok'];
            $grouped = collect($subKontribusis)->groupBy(function ($item) {
                return strtolower($item->level);
            });

            $result = [];
            foreach ($levelOrder as $level) {
                if ($grouped->has($level)) {
                    $result[$level] = $grouped[$level]->values();
                }
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub kontribusi options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data sub kontribusi'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {

            $user = $request->session()->get('user');

            $validated = $request->validate([
                'jamaah_id' => 'required|exists:jamaah,jamaah_id',
                'master_kontribusi_id' => 'required',
                'tgl_transaksi' => 'required|date',
                'metode_bayar' => 'required|in:TUNAI,TRANSFER,QRIS,LAINNYA',
                'keterangan' => 'nullable|string',
                'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sub_kontribusi' => 'required|array',
                'sub_kontribusi.*.sub_kat_id' => 'required',
                'sub_kontribusi.*.input_value' => 'required|numeric|min:0'
            ]);

            //  Generate ID
            $transaksiId = 'TRX' . Str::upper(Str::random(10));
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . Str::upper(Str::random(6));


            //    Ambil master kontribusi
            $kontribusi = DB::table('master_kontribusi')
                ->where('id', $validated['master_kontribusi_id'])
                ->first();

            // Upload Bukti Bayar
            $buktiPath = null;

            if ($request->hasFile('bukti_bayar')) {

                $file = $request->file('bukti_bayar');
                $filename = 'bukti_' . time() . '_' . $transaksiId . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/bukti_bayar', $filename);
                $buktiPath = 'bukti_bayar/' . $filename;
            }

            //  Simpan TRANSAKSI (HEADER)
            DB::table('transaksi')->insert([
                'transaksi_id' => $transaksiId,
                'kode_transaksi' => $kodeTransaksi,
                'kode_kontribusi' => $kontribusi->kode_kontribusi,
                'tgl_transaksi' => $validated['tgl_transaksi'],
                'jamaah_id' => $validated['jamaah_id'],
                'metode_bayar' => $validated['metode_bayar'],
                'bukti_bayar' => $buktiPath,
                'status' => 'VERIFIED',
                'keterangan' => $validated['keterangan'] ?? null,
                'jumlah' => $valdidated['jumlah'] ?? 0,
                'created_by' => $user['user_id'],
                'created_at' => now()
            ]);

            /*  Simpan DETAIL KONTRIBUSI */
            $detailJson = [];
            $totalJumlah = 0;

            foreach ($validated['sub_kontribusi'] as $sub) {

                $subDetail = DB::table('sub_kontribusi')
                    ->where('sub_kat_id', $sub['sub_kat_id'])
                    ->first();

                $jumlah = $sub['input_value'];

                $totalJumlah += $jumlah;

                /*  insert detail */
                DB::table('transaksi_detail')->insert([
                    'transaksi_id' => $transaksiId,
                    'kode_kontribusi' => $kontribusi->kode_kontribusi,
                    'sub_kontribusi_id' => $sub['sub_kat_id'],
                    'jumlah' => $jumlah,
                    'satuan' => 'IDR',
                    'keterangan' => $validated['keterangan'] ?? null,
                    'created_at' => now()
                ]);

                /*  json snapshot */
                $detailJson[] = [
                    'sub_kontribusi_id' => $sub['sub_kat_id'],
                    'nama_kontribusi' => $subDetail->nama_kontribusi,
                    'jumlah' => $jumlah
                ];
            }

            /*  Simpan JSON Snapshot */
            $dataJson = [
                'master_kontribusi' => [
                    'id' => $kontribusi->master_kontribusi_id,
                    'nama' => $kontribusi->nama_kontribusi,
                    'kode' => $kontribusi->kode_kontribusi
                ],
                'detail' => $detailJson,
                'total' => $totalJumlah
            ];

            DB::table('transaksi')
                ->where('transaksi_id', $transaksiId)
                ->update([
                    'data_json' => json_encode($dataJson)
                ]);

            /*  Activity Log */
            DB::table('activity_logs')->insert([
                'user_id' => $user['user_id'],
                'action' => 'ADD_TRANSAKSI',
                'description' => 'Input pembayaran jamaah ID: ' . $validated['jamaah_id'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat',
                'data' => [
                    'transaksi_id' => $transaksiId,
                    'kode_transaksi' => $kodeTransaksi
                ]
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Error storing transaksi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pembayaran'
            ], 500);
        }
    }
}
