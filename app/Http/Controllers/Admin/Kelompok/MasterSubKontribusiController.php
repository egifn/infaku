<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterSubKontribusiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        $infoKelompok = DB::table('master_kelompok')
            ->where('kelompok_id', $kelompokId)
            ->first();

        return view('admin.ku.kelompok.sub_kontribusi', [
            'user' => $user,
            'info_kelompok' => $infoKelompok
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $masterId = $request->get('master_id');
            $search = $request->get('search', '');
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $query = DB::table('sub_kontribusi')
                ->join('master_kontribusi', 'sub_kontribusi.kode_kontribusi', '=', 'master_kontribusi.id')
                ->where('sub_kontribusi.level', 'kelompok')
                ->where(function ($q) use ($kelompokId) {
                    $q->where('sub_kontribusi.created_by', $kelompokId)
                        ->orWhereNull('sub_kontribusi.created_by');
                });

            if (!empty($masterId)) {
                $query->where('sub_kontribusi.kode_kontribusi', $masterId);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('sub_kontribusi.nama_kontribusi', 'like', "%{$search}%")
                        ->orWhere('master_kontribusi.nama_kontribusi', 'like', "%{$search}%")
                        ->orWhere('sub_kontribusi.keterangan', 'like', "%{$search}%");
                });
            }

            $total = $query->count();
            $data = $query->select(
                'sub_kontribusi.*',
                'master_kontribusi.nama_kontribusi',
                'master_kontribusi.id as master_kontribusi_id'
            )
                ->orderBy('master_kontribusi.nama_kontribusi')
                ->orderBy('sub_kontribusi.id')
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
            Log::error('Error getting sub kontribusi data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data sub kontribusi'
            ], 500);
        }
    }

    public function getMasterOptions(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];
        try {
            $masterKontribusi = DB::table('master_kontribusi')
                ->where('is_aktif', true)
                ->where('created_by', $kelompokId)
                ->select('id', 'nama_kontribusi', 'kode_kontribusi')
                ->orderBy('nama_kontribusi')
                ->get();
            // dd($masterKontribusi);

            return response()->json([
                'success' => true,
                'data' => $masterKontribusi
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting master options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data master kategori'
            ], 500);
        }
    }

    public function getByMaster($masterId)
    {
        try {
            $user = request()->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $subKontribusi = DB::table('sub_kontribusi')
                ->join('master_kontribusi', 'sub_kontribusi.kode_kontribusi', '=', 'master_kontribusi.id')
                ->where('sub_kontribusi.kode_kontribusi', $masterId)
                ->where('sub_kontribusi.level', 'kelompok')
                ->where(function ($q) use ($kelompokId) {
                    $q->where('sub_kontribusi.created_by', $kelompokId)
                        ->orWhereNull('sub_kontribusi.created_by');
                })
                ->select(
                    'sub_kontribusi.*',
                    'master_kontribusi.nama_kontribusi',
                    'master_kontribusi.id'
                )
                ->orderBy('sub_kontribusi.nama_kontribusi')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $subKontribusi
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub kontribusi by master: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data sub kontribusi'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $kontribusi = DB::table('sub_kontribusi')
                ->join('master_kontribusi', 'sub_kontribusi.kode_kontribusi', '=', 'master_kontribusi.id')
                ->where('sub_kontribusi.id', $id)
                ->select(
                    'sub_kontribusi.*',
                    'master_kontribusi.nama_kontribusi',
                    'master_kontribusi.id'
                )
                ->first();

            if (!$kontribusi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sub kontribusi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $kontribusi
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing sub kontribusi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data sub kontribusi'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $validated = $request->validate([
                'master_kontribusi_id' => 'required|exists:master_kontribusi,id',
                'nama_kontribusi'     => 'required|string|max:150',
                'value'               => 'required|numeric|min:0',
                'jenis'               => 'required|in:percentage,nominal',
                'keterangan'          => 'nullable|string|max:255',
                'is_active'           => 'required|boolean'
            ]);

            $data = [
                'kode_kontribusi'     => $validated['master_kontribusi_id'],
                'nama_kontribusi'     => $validated['nama_kontribusi'],
                'value'               => $validated['value'],
                'jenis'               => $validated['jenis'],
                'keterangan'          => $validated['keterangan'] ?? null,
                'is_active'           => $validated['is_active'],
                'level'               => 'kelompok',
                'created_by'          => $kelompokId,
                'created_at'          => now()
            ];

            $subKontribusiId = DB::table('sub_kontribusi')->insertGetId($data);

            return response()->json([
                'success' => true,
                'message' => 'Sub kontribusi berhasil ditambahkan',
                'data' => ['sub_kat_id' => $subKontribusiId]
            ]);
        } catch (\Exception $e) {

            Log::error('Error storing sub kontribusi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan sub kontribusi: ' . $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'master_kontribusi_id'  => 'required|exists:master_kontribusi,id',
                'nama_kontribusi'       => 'required|string|max:150',
                'value'                 => 'required|numeric|min:0',
                'jenis'                 => 'required|in:percentage,nominal',
                'keterangan'            => 'nullable|string|max:255',
                'is_active'             => 'required|boolean'
            ]);

            $validated['updated_at'] = now();

            $affected = DB::table('sub_kontribusi')
                ->where('id', $id)
                ->update($validated);

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sub kontribusi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sub kontribusi berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating sub kontribusi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate sub kontribusi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Check if sub kontribusi is used in transactions
            $isUsed = DB::table('transaksi')
                ->where('sub_kategori_id', $id)
                ->exists();

            if ($isUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus sub kontribusi yang sudah digunakan dalam transaksi'
                ], 400);
            }

            $affected = DB::table('sub_kontribusi')
                ->where('sub_kat_id', $id)
                ->delete();

            if ($affected === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sub kontribusi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sub kontribusi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting sub kontribusi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sub kontribusi: ' . $e->getMessage()
            ], 500);
        }
    }
}
