<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MasterKontribusiController extends Controller
{
    // ============================================
    // SECTION 1: VIEW METHODS
    // ============================================

    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        $infoKelompok = DB::table('master_kelompok')
            ->where('kelompok_id', $kelompokId)
            ->first();

        return view('admin.ku.kelompok.master_kontribusi', [
            'user' => $user,
            'info_kelompok' => $infoKelompok
        ]);
    }

    // ============================================
    // SECTION 2: API METHODS
    // ============================================

    public function getData(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $search = $request->get('search', '');
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            // Pastikan page dan per_page integer
            $page = (int) $page;
            $perPage = (int) $perPage;
            if ($page < 1) $page = 1;
            if ($perPage < 1) $perPage = 10;

            // Hitung offset
            $offset = ($page - 1) * $perPage;

            // Build query
            $query = DB::table('master_kontribusi')
                ->join('master_kelompok', 'master_kontribusi.created_by', '=', 'master_kelompok.id')
                ->select(
                    'master_kontribusi.*',
                    'master_kelompok.nama_kelompok'
                )
                ->where('master_kontribusi.created_by', $kelompokId);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('master_kontribusi.nama_kontribusi', 'like', "%{$search}%")
                        ->orWhere('master_kontribusi.kode_kontribusi', 'like', "%{$search}%")
                        ->orWhere('master_kontribusi.keterangan', 'like', "%{$search}%");
                });
            }


            // Get total count
            $total = $query->count();

            // Hitung total pages
            $totalPages = ceil($total / $perPage);
            if ($totalPages < 1) $totalPages = 1;

            // Jika page > totalPages, reset ke page = 1
            if ($page > $totalPages) {
                $page = 1;
                $offset = 0;
            }

            // Get paginated data
            $data = $query->orderBy('master_kontribusi.id', 'asc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            // Debug log
            Log::info('Master Kontribusi Pagination Debug', [
                'page' => $page,
                'per_page' => $perPage,
                'offset' => $offset,
                'total' => $total,
                'total_pages' => $totalPages,
                'data_count' => $data->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'current_page' => $page,
                'last_page' => $totalPages,
                'total' => $total,
                'per_page' => $perPage,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting master kontribusi data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data master kontribusi'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $kontribusi = DB::table('master_kontribusi')
                ->where('id', $id)
                ->first();

            if (!$kontribusi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master kontribusi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $kontribusi
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing master kontribusi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data master kontribusi'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'nama_kontribusi' => 'required|string|max:100',
                'keterangan' => 'nullable|string',
                'is_aktif' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            // Get kelompok data
            $kelompok = DB::table('master_kelompok')
                ->where('id', $kelompokId)
                ->first();

            if (!$kelompok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelompok tidak ditemukan'
                ], 404);
            }

            // Hitung nomor urut
            $urut = DB::table('master_kontribusi')
                ->where('created_by', $kelompokId)
                ->count() + 1;

            // Ambil bulan & tahun sekarang
            $bulanRomawi = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII'
            ];

            $bulan = $bulanRomawi[date('n')]; // contoh: IV
            $tahun = date('Y');               // contoh: 2026

            // Nama kelompok dirapikan (spasi jadi - / uppercase)
            $namaKelompok = strtoupper(str_replace(' ', '-', $kelompok->nama_kelompok));

            // Generate nomor kontribusi (format dokumen)
            $nomorKontribusi = str_pad($urut, 3, '0', STR_PAD_LEFT)
                . '/' . $namaKelompok
                . '/' . $bulan
                . '/' . $tahun;

            // Prepare data
            $data = [
                'kode_kontribusi' => strtoupper($nomorKontribusi),
                'nama_kontribusi' => strtoupper($request->nama_kontribusi),
                'keterangan' => $request->keterangan,
                'created_by' => $kelompokId,
                'is_aktif' => $request->is_aktif,
                'created_at' => DB::raw('NOW()')
            ];

            // Insert data
            $masterId = DB::table('master_kontribusi')->insertGetId($data);

            return response()->json([
                'success' => true,
                'message' => 'Master kontribusi berhasil ditambahkan',
                'data' => [
                    'id' => $masterId,
                    'kode_kontribusi' => strtoupper($nomorKontribusi)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing master kontribusi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan master kontribusi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'nama_kontribusi' => 'required|string|max:100',
                'keterangan' => 'nullable|string',
                'is_aktif' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare update data
            $data = [
                'nama_kontribusi' => strtoupper($request->nama_kontribusi),
                'keterangan' => $request->keterangan,
                'is_aktif' => $request->is_aktif,
                'updated_at' => DB::raw('NOW()')
            ];

            // Update data
            $affected = DB::table('master_kontribusi')
                ->where('id', $id)
                ->update($data);

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master kontribusi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Master kontribusi berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating master kontribusi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate master kontribusi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Check if master kontribusi has sub kontribusi
            $hasSubKontribusi = DB::table('sub_kontribusi')
                ->where('kode_kontribusi', $id)
                ->exists();

            if ($hasSubKontribusi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus master kontribusi yang memiliki sub kontribusi'
                ], 400);
            }

            // Check if master kontribusi is used in transactions
            $isUsed = DB::table('transaksi')
                ->whereExists(function ($query) use ($id) {
                    $query->select(DB::raw(1))
                        ->from('sub_kontribusi')
                        ->whereColumn('transaksi.sub_kontribusi_id', 'sub_kontribusi.id')
                        ->where('sub_kontribusi.kode_kontribusi', $id);
                })
                ->exists();

            if ($isUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus master kontribusi yang sudah digunakan dalam transaksi'
                ], 400);
            }

            // Delete master kontribusi
            $affected = DB::table('master_kontribusi')
                ->where('id', $id)
                ->delete();

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master kontribusi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Master kontribusi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting master kontribusi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus master kontribusi'
            ], 500);
        }
    }
}
