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

            $search = $request->get('search', '');

            $query = DB::table('master_kontribusi')
                ->where('is_aktif', 1)
                ->where('created_by', $kelompokId);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kontribusi', 'like', "%{$search}%");;
                });
            }

            $kontribusis = $query->select('id', 'kode_kontribusi', 'nama_kontribusi')
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
        dd($request->all());
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $validated = $request->validate([
                'jamaah_id' => 'required|exists:jamaah,jamaah_id',
                'master_kontribusi_id' => 'required',
                'tgl_transaksi' => 'required|date',
                'metode_bayar' => 'required|in:TUNAI,TRANSFER,QRIS,LAINNYA',
                'keterangan' => 'nullable|string',
                'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sub_kontribusi' => 'required|array',
                'sub_kontribusi.*.sub_kat_id' => 'required|exists:sub_kontribusi,sub_kat_id',
                'sub_kontribusi.*.input_value' => 'required|numeric|min:0'
            ]);

            // Hitung total jumlah dari semua sub kontribusi
            $totalJumlah = 0;
            $subKontribusiData = [];

            foreach ($validated['sub_kontribusi'] as $sub) {
                $totalJumlah += $sub['input_value'];

                // Ambil data sub kontribusi untuk disimpan ke JSON
                $subDetail = DB::table('sub_kontribusi')
                    ->where('id', $sub['id'])
                    ->first();

                $subKontribusiData[] = [
                    'id' => $sub['id'],
                    'nama_kontribusi' => $subDetail->nama_kontribusi,
                    'jenis' => $subDetail->jenis,
                    'value' => $subDetail->value,
                    'input_value' => $sub['input_value']
                ];
            }

            // Generate transaction IDs
            $transaksiId = 'TRX' . Str::upper(Str::random(10));
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . Str::random(6);

            // Get kontribusi info untuk kategori_id
            $kontribusi = DB::table('master_kontribusi')
                ->where('master_kontribusi_id', $validated['master_kontribusi_id'])
                ->first();

            // Prepare data JSON
            $dataJson = [
                'master_kontribusi_id' => $validated['master_kontribusi_id'],
                'nama_kontribusi' => $kontribusi->nama_kontribusi,
                'kode_kontribusi' => $kontribusi->kode_kontribusi,
                'sub_kontribusi' => $subKontribusiData,
                'total_jumlah' => $totalJumlah,
                'input_data' => [
                    'tgl_transaksi' => $validated['tgl_transaksi'],
                    'metode_bayar' => $validated['metode_bayar'],
                    'keterangan' => $validated['keterangan'] ?? null
                ]
            ];

            $transaksiData = [
                'transaksi_id' => $transaksiId,
                'kode_transaksi' => $kodeTransaksi,
                'tgl_transaksi' => $validated['tgl_transaksi'],
                'jamaah_id' => $validated['jamaah_id'],
                'kontribusi_id' => $kontribusi->kode_kontribusi,
                'jumlah' => $totalJumlah,
                'satuan' => 'IDR',
                'keterangan' => $validated['keterangan'] ?? null,
                'metode_bayar' => $validated['metode_bayar'],
                'status' => 'VERIFIED',
                'data_json' => json_encode($dataJson, JSON_PRETTY_PRINT),
                'created_by' => $user['user_id'],
                'created_at' => now()
            ];

            // Handle file upload if exists
            if ($request->hasFile('bukti_bayar')) {
                $file = $request->file('bukti_bayar');
                $filename = 'bukti_' . time() . '_' . $transaksiId . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/bukti_bayar', $filename);
                $transaksiData['bukti_bayar'] = 'bukti_bayar/' . $filename;
            }

            DB::table('transaksi')->insert($transaksiData);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $user['user_id'],
                'action' => 'ADD_TRANSAKSI',
                'description' => 'Input pembayaran untuk jamaah: ' . $validated['jamaah_id'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat',
                'data' => [
                    'transaksi_id' => $transaksiId,
                    'kode_transaksi' => $kodeTransaksi
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing transaksi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
