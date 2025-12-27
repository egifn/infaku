<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JamaahController extends Controller
{
    // ============================================
    // SECTION 1: VIEW METHODS
    // ============================================

    /**
     * Display main jamaah view
     */
    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['wilayah_id'];

        $infoKelompok = DB::table('master_kelompok')
            ->where('kelompok_id', $kelompokId)
            ->first();

        return view('admin.ku.kelompok.data_jamaah', [
            'user' => $user,
            'info_kelompok' => $infoKelompok
        ]);
    }

    /**
     * Display print view
     */
    public function print(Request $request)
    {
        $user = $request->session()->get('user');
        $kelompokId = $user['id'];

        $jamaahs = DB::table('jamaah')
            ->leftJoin('keluarga', 'jamaah.jamaah_id', '=', 'keluarga.kepala_keluarga_id')
            ->leftJoin('master_dapuan', 'jamaah.dapuan_id', '=', 'master_dapuan.id')
            ->where('jamaah.id', $kelompokId)
            ->select(
                'jamaah.*',
                'keluarga.nama_keluarga',
                'master_dapuan.nama_dapuan'
            )
            ->orderBy('jamaah.nama_lengkap')
            ->get();

        $infoKelompok = DB::table('master_kelompok')
            ->where('id', $kelompokId)
            ->first();

        return view('admin.ku.kelompok.data_jamaah_print', [
            'jamaahs' => $jamaahs,
            'info_kelompok' => $infoKelompok,
            'user' => $user
        ]);
    }

    // ============================================
    // SECTION 2: API METHODS
    // ============================================

    /**
     * Get paginated jamaah data
     */
    public function getData(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            // Get query parameters
            $search = $request->get('search', '');
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $isAktif = $request->get('is_aktif');

            // Build query
            $query = DB::table('jamaah')
                ->leftJoin('keluarga', 'jamaah.jamaah_id', '=', 'keluarga.kepala_keluarga_id')
                ->Join('master_dapuan', 'jamaah.dapuan_id', '=', 'master_dapuan.id')
                ->where('jamaah.kelompok_id', $kelompokId);

            // Apply search
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->Where('jamaah.nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('jamaah.telepon', 'like', "%{$search}%")
                        ->orWhere('jamaah.alamat', 'like', "%{$search}%")
                        ->orWhere('keluarga.nama_keluarga', 'like', "%{$search}%")
                        ->orWhere('master_dapuan.nama_dapuan', 'like', "%{$search}%");
                });
            }

            // Apply status filter
            if ($isAktif !== null) {
                $query->where('jamaah.is_aktif', $isAktif);
            }

            // Get total count
            $total = $query->count();

            // Get paginated data
            $data = $query->select(
                'jamaah.*',
                'keluarga.nama_keluarga',
                'master_dapuan.nama_dapuan'
            )
                ->orderBy('jamaah.nama_lengkap', 'asc')
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
            Log::error('Error getting jamaah data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data'
            ], 500);
        }
    }

    /**
     * Get single jamaah data
     */
    public function show($id)
    {
        try {
            $jamaah = DB::table('jamaah')
                ->leftJoin('keluarga', 'jamaah.id', '=', 'keluarga.kepala_keluarga_id')
                ->join('master_dapuan', 'jamaah.dapuan_id', '=', 'master_dapuan.id')
                ->where('jamaah.jamaah_id', $id)
                ->join('users', 'jamaah.id', '=', 'users.jamaah_id')
                ->select(
                    'jamaah.*',
                    'users.email',
                    'keluarga.nama_keluarga',
                    'master_dapuan.nama_dapuan',
                    'master_dapuan.id as dapuan_id'
                )
                ->first();

            if (!$jamaah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jamaah tidak ditemukan'
                ], 404);
            }

            // Format response data
            $data = $this->formatJamaahResponse($jamaah);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing jamaah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jamaah'
            ], 500);
        }
    }

    /**
     * Store new jamaah
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'nama_lengkap' => 'required|string|max:255',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'required|in:L,P',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:100',
                'pekerjaan' => 'nullable|string|max:100',
                'status_menikah' => 'required|in:BELUM_MENIKAH,MENIKAH,JANDA,DUDA',
                'golongan_darah' => 'nullable|in:A,B,AB,O,-',
                'dapuan_id' => 'required|exists:master_dapuan,dapuan_id',
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
            $kelompokId = $user['id'];

            // Generate jamaah ID
            $jamaahId = $this->generateJamaahId($kelompokId);

            // Prepare data
            $data = $request->all();
            $data['jamaah_id'] = $jamaahId;
            $data['kelompok_id'] = $kelompokId;
            $data['created_at'] = now();

            // Insert data
            DB::table('jamaah')->insert($data);

            return response()->json([
                'success' => true,
                'message' => 'Data jamaah berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing jamaah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data jamaah'
            ], 500);
        }
    }

    /**
     * Update jamaah data
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'nama_lengkap' => 'required|string|max:255',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'required|in:L,P',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:100',
                'pekerjaan' => 'nullable|string|max:100',
                'status_menikah' => 'required|in:BELUM_MENIKAH,MENIKAH,JANDA,DUDA',
                'golongan_darah' => 'nullable|in:A,B,AB,O,-',
                'dapuan_id' => 'nullable|exists:master_dapuan,dapuan_id',
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
            $data = $request->all();
            $data['updated_at'] = now();

            // Update data
            DB::table('jamaah')
                ->where('id', $id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data jamaah berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating jamaah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data jamaah'
            ], 500);
        }
    }

    /**
     * Delete jamaah data
     */
    public function destroy($id)
    {
        try {
            // Check if jamaah is kepala keluarga
            $isKepalaKeluarga = DB::table('keluarga')
                ->where('kepala_keluarga_id', $id)
                ->exists();

            if ($isKepalaKeluarga) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus jamaah yang menjadi kepala keluarga'
                ], 400);
            }

            // Check if jamaah has transactions
            $hasTransactions = DB::table('transaksi')
                ->where('jamaah_id', $id)
                ->exists();

            if ($hasTransactions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus jamaah yang memiliki riwayat transaksi'
                ], 400);
            }

            // Delete from related tables
            DB::table('anggota_keluarga')
                ->where('jamaah_id', $id)
                ->delete();

            // Delete jamaah
            DB::table('jamaah')
                ->where('jamaah_id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data jamaah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting jamaah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jamaah'
            ], 500);
        }
    }

    // ============================================
    // SECTION 3: OPTION METHODS
    // ============================================

    /**
     * Get dapuan options
     */
    public function getDapuanOptions(Request $request)
    {
        try {
            $dapuans = DB::table('master_dapuan')
                ->select('id', 'nama_dapuan')
                ->orderBy('kode_dapuan')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $dapuans
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting dapuan options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data dapuan'
            ], 500);
        }
    }

    /**
     * Get keluarga options
     */
    public function getKeluargaOptions(Request $request)
    {
        try {
            $user = $request->session()->get('user');
            $kelompokId = $user['wilayah_id'];

            $keluargas = DB::table('keluarga')
                ->where('kelompok_id', $kelompokId)
                ->select('keluarga_id', 'nama_keluarga')
                ->orderBy('nama_keluarga')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $keluargas
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting keluarga options: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data keluarga'
            ], 500);
        }
    }

    // ============================================
    // SECTION 4: HELPER METHODS
    // ============================================

    /**
     * Format jamaah response
     */
    private function formatJamaahResponse($jamaah)
    {
        // Format tanggal_lahir
        $tanggal_lahir = ($jamaah->tanggal_lahir && $jamaah->tanggal_lahir !== '0000-01-01')
            ? date('Y-m-d', strtotime($jamaah->tanggal_lahir))
            : null;

        return [
            'id' => $jamaah->id,
            'jamaah_id' => $jamaah->jamaah_id,
            'nik' => $jamaah->nik,
            'nama_lengkap' => $jamaah->nama_lengkap,
            'tempat_lahir' => $jamaah->tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'jenis_kelamin' => $jamaah->jenis_kelamin,
            'alamat' => $jamaah->alamat,
            'telepon' => $jamaah->telepon,
            'email' => $jamaah->email,
            'pekerjaan' => $jamaah->pekerjaan,
            'status_menikah' => $jamaah->status_menikah,
            'golongan_darah' => $jamaah->golongan_darah,
            'dapuan_id' => isset($jamaah->dapuan_id) ? (string)$jamaah->dapuan_id : null,
            'nama_dapuan' => $jamaah->nama_dapuan,
            'nama_keluarga' => $jamaah->nama_keluarga,
            'is_aktif' => (bool)$jamaah->is_aktif,
            'created_at' => $jamaah->created_at,
            'updated_at' => $jamaah->updated_at,
            'kelompok_id' => $jamaah->kelompok_id,
            'foto_profil' => $jamaah->foto_profil,
        ];
    }

    /**
     * Generate unique jamaah ID
     */
    private function generateJamaahId($kelompokId)
    {
        // Get kelompok info
        $kodeKelompok = DB::table('master_kelompok')
            ->where('kelompok_id', $kelompokId)
            ->value('nama_kelompok');

        $kodeKelompok = $this->generateKodeKelompok($kodeKelompok);

        // Get last jamaah number
        $lastJamaah = DB::table('jamaah')
            ->where('kelompok_id', $kelompokId)
            ->count();

        $nextNumber = $lastJamaah + 1;

        return $kodeKelompok . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode kelompok
     */
    private function generateKodeKelompok(string $nama, int $limit = 4): string
    {
        $kode = '';
        $ambil = 0;

        for ($i = 0; $i < mb_strlen($nama); $i++) {
            if ($i % 2 === 0) {
                $kode .= mb_substr($nama, $i, 1);
                $ambil++;
                if ($ambil === $limit) break;
            }
        }

        return mb_strtoupper($kode);
    }
}
