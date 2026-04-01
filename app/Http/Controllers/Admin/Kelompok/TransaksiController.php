<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhatsAppService;

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

            $kontribusis = $query->select('id', 'kode_kontribusi', 'nama_kontribusi', 'is_aktif', 'jenis', 'periode', 'tgl_mulai', 'tgl_selesai')
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
        DB::beginTransaction();

        try {

            $user = $request->session()->get('user');

            $validated = $request->validate([
                'jamaah_id' => 'required|exists:jamaah,jamaah_id',
                'master_kontribusi_id' => 'required',
                'tgl_transaksi' => 'required|date',
                'metode_bayar' => 'required|in:TUNAI,TRANSFER,QRIS,LAINNYA',
                'keterangan' => 'nullable|string',
                'total_pembayaran' => 'nullable',
                'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sub_kontribusi' => 'required|array',
                'sub_kontribusi.*.sub_kat_id' => 'required',
                'sub_kontribusi.*.input_value' => 'required|numeric|min:0',
                'target_id' => 'nullable|integer'
            ]);

            //  Generate ID
            $transaksiId = 'TRX' . Str::upper(Str::random(10));
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . Str::upper(Str::random(6));


            //    Ambil master kontribusi
            $kontribusi = DB::table('master_kontribusi')
                ->where('id', $validated['master_kontribusi_id'])
                ->first();

            if (!$kontribusi) {
                throw new \Exception('Master kontribusi tidak ditemukan');
            }

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
                'nama_kontribusi' => $kontribusi->nama_kontribusi,
                'tgl_transaksi' => $validated['tgl_transaksi'],
                'jamaah_id' => $validated['jamaah_id'],
                'metode_bayar' => $validated['metode_bayar'],
                'bukti_bayar' => $buktiPath,
                'status' => 'VERIFIED',
                'keterangan' => $validated['keterangan'] ?? null,
                'jumlah' => $validated['total_pembayaran'],
                'created_by' => $user['user_id'],
                'created_at' => now()
            ]);

            /*  Simpan DETAIL KONTRIBUSI */
            $detailJson = [];
            foreach ($validated['sub_kontribusi'] as $sub) {

                $jumlah = $sub['input_value'];

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
            }

            if ($kontribusi->jenis === 'SAVING') {
                $tabungan = DB::table('tabungan_kontribusi')
                    ->where('jamaah_id', $validated['jamaah_id'])
                    ->where('master_kontribusi_id', $kontribusi->id)
                    ->first();

                if ($tabungan) {
                    $update = [
                        'saldo' => DB::raw('saldo + ' . (float) $validated['total_pembayaran']),
                        'updated_at' => now()
                    ];
                    if (!empty($validated['target_id'])) {
                        $update['target_id'] = $validated['target_id'];
                        $update['status'] = 'TARGET_DIPILIH';
                    }
                    DB::table('tabungan_kontribusi')
                        ->where('id', $tabungan->id)
                        ->update($update);
                } else {
                    DB::table('tabungan_kontribusi')->insert([
                        'jamaah_id' => $validated['jamaah_id'],
                        'master_kontribusi_id' => $kontribusi->id,
                        'saldo' => $validated['total_pembayaran'],
                        'target_id' => $validated['target_id'] ?? null,
                        'status' => !empty($validated['target_id']) ? 'TARGET_DIPILIH' : 'AKTIF',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

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

            $waResult = null;
            if (config('whatsapp.auto_send')) {
                $waResult = $this->sendBillToWhatsappInternal($transaksiId);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat',
                'data' => [
                    'transaksi_id' => $transaksiId,
                    'kode_transaksi' => $kodeTransaksi,
                    'whatsapp' => $waResult
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

    public function printBill($transaksiId)
    {
        $data = $this->buildBillData($transaksiId);
        if (!$data) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $pdf = Pdf::loadView('admin.ku.kelompok.transaksi_bill', $data);
        $filename = 'bill-' . $data['transaksi']->kode_transaksi . '.pdf';

        return $pdf->stream($filename);
    }

    public function sendBillWhatsapp($transaksiId)
    {
        $result = $this->sendBillToWhatsappInternal($transaksiId);

        if ($result['success'] ?? false) {
            return response()->json([
                'success' => true,
                'message' => 'Bill berhasil dikirim via WhatsApp',
                'data' => $result['data'] ?? null
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Gagal mengirim bill via WhatsApp'
        ], 500);
    }

    private function sendBillToWhatsappInternal(string $transaksiId): array
    {
        $data = $this->buildBillData($transaksiId);
        if (!$data) {
            return ['success' => false, 'message' => 'Transaksi tidak ditemukan'];
        }

        $phone = $this->normalizePhone($data['transaksi']->telepon ?? '');
        if (empty($phone)) {
            return ['success' => false, 'message' => 'Nomor WhatsApp jamaah tidak tersedia'];
        }

        $pdf = Pdf::loadView('admin.ku.kelompok.transaksi_bill', $data);
        $filename = 'bill-' . $data['transaksi']->kode_transaksi . '.pdf';
        $relativePath = 'whatsapp/' . $filename;

        Storage::disk('local')->put($relativePath, $pdf->output());
        $fullPath = storage_path('app/' . $relativePath);

        $caption = 'Bill pembayaran ' . $data['transaksi']->kode_transaksi;
        $service = new WhatsAppService();
        $result = $service->sendDocument($phone, $fullPath, $filename, $caption);

        Storage::disk('local')->delete($relativePath);

        return $result;
    }

    private function buildBillData(string $transaksiId): ?array
    {
        $transaksi = DB::table('transaksi as t')
            ->join('jamaah as j', 't.jamaah_id', '=', 'j.jamaah_id')
            ->leftJoin('master_kontribusi as mk', 't.kode_kontribusi', '=', 'mk.kode_kontribusi')
            ->select(
                't.*',
                'j.nama_lengkap',
                'j.telepon',
                'j.alamat',
                'mk.nama_kontribusi'
            )
            ->where('t.transaksi_id', $transaksiId)
            ->first();

        if (!$transaksi) {
            return null;
        }

        $details = DB::table('transaksi_detail as td')
            ->leftJoin('sub_kontribusi as sk', 'td.sub_kontribusi_id', '=', 'sk.id')
            ->select('td.*', 'sk.nama_kontribusi as sub_nama', 'sk.level')
            ->where('td.transaksi_id', $transaksiId)
            ->orderBy('td.id')
            ->get();

        $total = $details->sum('jumlah');

        $infoKelompok = null;
        try {
            $user = session()->get('user');
            $kelompokId = $user['wilayah_id'] ?? null;
            if ($kelompokId) {
                $infoKelompok = DB::table('master_kelompok')
                    ->where('kelompok_id', $kelompokId)
                    ->first();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to load info kelompok for bill: ' . $e->getMessage());
        }

        return [
            'transaksi' => $transaksi,
            'details' => $details,
            'total' => $total,
            'infoKelompok' => $infoKelompok
        ];
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (empty($digits)) {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $country = config('whatsapp.default_country_code', '62');
            $digits = $country . substr($digits, 1);
        }

        return $digits;
    }
}
