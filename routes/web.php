<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\Kelompok\DashboardController;
use App\Http\Controllers\Admin\Kelompok\KeluargaController;
use App\Http\Controllers\Admin\Kelompok\JamaahController;
use App\Http\Controllers\Admin\Kelompok\AnggotaKeluargaController;
use App\Http\Controllers\Admin\Kelompok\MasterKontribusiController;
use App\Http\Controllers\Admin\Kelompok\MasterSubKontribusiController;
use App\Http\Controllers\Admin\Kelompok\TransaksiController;
use App\Http\Controllers\Admin\Kelompok\RiwayatTransaksiController;
use App\Http\Controllers\Admin\Kelompok\LaporanController;

use App\Http\Controllers\Master\MasterWilayahController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes - menggunakan session auth
Route::middleware(['auth.session'])->group(function () {

    // Dashboard berdasarkan role
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Kelompok
    Route::middleware(['role:RL004'])->group(function () {
        Route::prefix('admin/kelompok')->name('admin.kelompok.')->group(function () {
            // -------------------------------- Dashboard --------------------------------  //
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/api/stats', [DashboardController::class, 'getStats'])->name('api.stats');
            Route::get('/api/chart', [DashboardController::class, 'getChartData'])->name('api.chart');
            Route::get('/api/activities', [DashboardController::class, 'getActivities'])->name('api.activities');

            // -------------------------------- Data Keluarga --------------------------------  //
            Route::get('/admin/data-keluarga', [KeluargaController::class, 'index'])->name('data-keluarga.index');
            Route::get('/keluarga/print', [KeluargaController::class, 'print'])->name('data-keluarga.print');
            // API Routes for Keluarga
            Route::prefix('api/keluarga')->name('api.keluarga.')->group(function () {
                Route::get('/', [KeluargaController::class, 'getData'])->name('index');
                Route::get('/jamaah-options', [KeluargaController::class, 'getJamaahOptions'])->name('jamaah-options');
                Route::get('/fam', [KeluargaController::class, 'getJamaahFam'])->name('jamaah-fam');
                Route::get('/{id}', [KeluargaController::class, 'show'])->name('show');
                Route::post('/', [KeluargaController::class, 'store'])->name('store');
                Route::put('/{id}', [KeluargaController::class, 'update'])->name('update');
                Route::delete('/{id}', [KeluargaController::class, 'destroy'])->name('destroy');
            });

            // -------------------------------- Data Anggota Keluarga -------------------------------- //
            Route::get('/anggota-keluarga', [AnggotaKeluargaController::class, 'index'])->name('anggota-keluarga.index');
            // API Routes for Anggota Keluarga
            Route::prefix('api/anggota-keluarga')->name('api.anggota-keluarga.')->group(function () {
                Route::get('/', [AnggotaKeluargaController::class, 'getData'])->name('index');
                Route::post('/anggota-keluarga', [KeluargaController::class, 'insertAnggotaKeluarga'])->name('insert-anggota-keluarga');
                Route::get('/keluarga-options', [AnggotaKeluargaController::class, 'getKeluargaOptions'])->name('keluarga-options');
                Route::get('/jamaah-options', [AnggotaKeluargaController::class, 'getJamaahOptions'])->name('jamaah-options');
                Route::get('/{id}', [AnggotaKeluargaController::class, 'show'])->name('show');
                Route::post('/', [AnggotaKeluargaController::class, 'store'])->name('store');
                Route::put('/{id}', [AnggotaKeluargaController::class, 'update'])->name('update');
                Route::delete('/{id}', [AnggotaKeluargaController::class, 'destroy'])->name('destroy');
                Route::get('/keluarga/{keluargaId}', [AnggotaKeluargaController::class, 'getByKeluarga'])->name('by-keluarga');
            });

            // -------------------------------- Data Jamaah -------------------------------- //
            Route::get('/jamaah', [JamaahController::class, 'index'])->name('data-jamaah.index');
            Route::get('/jamaah/print', [JamaahController::class, 'print'])->name('data-jamaah.print');

            // API Routes for Jamaah
            Route::prefix('api/jamaah')->name('api.jamaah.')->group(function () {
                Route::get('/', [JamaahController::class, 'getData'])->name('index');
                Route::get('/keluarga-options', [JamaahController::class, 'getKeluargaOptions'])->name('keluarga-options');
                Route::get('/dapuan-options', [JamaahController::class, 'getDapuanOptions'])->name('dapuan-options');
                Route::get('/{id}', [JamaahController::class, 'show'])->name('show');
                Route::post('/', [JamaahController::class, 'store'])->name('store');
                Route::put('/{id}', [JamaahController::class, 'update'])->name('update');
                Route::delete('/{id}', [JamaahController::class, 'destroy'])->name('destroy');
            });

            // -------------------------------- Master Kontribusi -------------------------------- //
            Route::get('/master-kontribusi', [MasterKontribusiController::class, 'index'])->name('master-kontribusi.index');
            Route::prefix('api/master-kontribusi')->name('api.master-kontribusi.')->group(function () {
                Route::get('/', [MasterKontribusiController::class, 'getData'])->name('index');
                Route::get('/{id}', [MasterKontribusiController::class, 'show'])->name('show');
                Route::post('/', [MasterKontribusiController::class, 'store'])->name('store');
                Route::put('/{id}', [MasterKontribusiController::class, 'update'])->name('update');
                Route::delete('/{id}', [MasterKontribusiController::class, 'destroy'])->name('destroy');
            });

            // -------------------------------- Sub Kontribusi -------------------------------- //
            Route::get('/sub-kontribusi', [MasterSubKontribusiController::class, 'index'])->name('sub-kontribusi.index');

            // API Routes for Sub Kontribusi
            Route::prefix('api/sub-kontribusi')->name('api.sub-kontribusi.')->group(function () {
                Route::get('/', [MasterSubKontribusiController::class, 'getData'])->name('index');
                Route::get('/master-options', [MasterSubKontribusiController::class, 'getMasterOptions'])->name('master-options');
                Route::get('/{id}', [MasterSubKontribusiController::class, 'show'])->name('show');
                Route::post('/', [MasterSubKontribusiController::class, 'store'])->name('store');
                Route::put('/{id}', [MasterSubKontribusiController::class, 'update'])->name('update');
                Route::delete('/{id}', [MasterSubKontribusiController::class, 'destroy'])->name('destroy');
                Route::get('/by-master/{masterId}', [MasterSubKontribusiController::class, 'getByMaster'])->name('by-master');
            });

            // -------------------------------- Transaksi -------------------------------- //
            Route::get('/transaksi', [TransaksiController::class, 'index'])->name('input-pembayaran.index');
            Route::get('/input-pembayaran', [TransaksiController::class, 'create'])->name('input-pembayaran.create');
            Route::post('/input-pembayaran', [TransaksiController::class, 'store'])->name('input-pembayaran.store');

            // API Routes for Input Pembayaran
            Route::prefix('api/input-pembayaran')->name('api.input-pembayaran.')->group(function () {
                Route::get('/jamaah-options', [TransaksiController::class, 'getJamaahOptions'])->name('jamaah-options');
                Route::get('/kontribusi-options', [TransaksiController::class, 'getKontribusiOptions'])->name('kontribusi-options');
                Route::get('/sub-kontribusi-options/{masterKontribusiId}', [TransaksiController::class, 'getSubKontribusiOptions'])->name('sub-kontribusi-options');
            });

            // -------------------------------- Riwayat Transaksi -------------------------------- //
            Route::prefix('/riwayat-transaksi')->name('riwayat-transaksi.')->group(function () {
                Route::get('/', [RiwayatTransaksiController::class, 'index'])->name('index');
                Route::get('/print', [RiwayatTransaksiController::class, 'print'])->name('print');
                Route::get('/export', [RiwayatTransaksiController::class, 'export'])->name('export');

                // API Routes
                Route::prefix('api')->name('api.')->group(function () {
                    Route::get('/', [RiwayatTransaksiController::class, 'getData'])->name('index');
                    Route::get('/{id}', [RiwayatTransaksiController::class, 'show'])->name('show');
                    Route::put('/{id}/status', [RiwayatTransaksiController::class, 'updateStatus'])->name('update-status');
                });
            });

            // routes/web.php
            Route::prefix('/laporan')->name('laporan.')->group(function () {
                Route::get('/', [LaporanController::class, 'index'])->name('index');
                Route::prefix('api')->name('api.')->group(function () {
                    Route::get('/', [LaporanController::class, 'getData'])->name('index');
                    Route::post('/', [LaporanController::class, 'store'])->name('store');
                    Route::get('/{id}', [LaporanController::class, 'show'])->name('show');
                    Route::get('/detail/{id}', [LaporanController::class, 'getDetail'])->name('detail');
                    Route::put('/settle/{id}', [LaporanController::class, 'settle'])->name('settle');
                    Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('destroy');
                    Route::get('/export/{id}', [LaporanController::class, 'export'])->name('export');
                    Route::get('/preview', [LaporanController::class, 'preview'])->name('preview');
                });

                // Export & Print
                Route::get('/print/{id}', [LaporanController::class, 'print'])->name('print');
            });
        });

        // Kelompok (ini buat desa)
        Route::get('/admin/master/wilayah-kelompok', [MasterWilayahController::class, 'wilayahKelompok'])->name('admin.master.wilayah-kelompok');
        Route::get('/api/wilayah-kelompok', [MasterWilayahController::class, 'getWilayahKelompok'])->name('api.wilayah-kelompok.index');
        Route::post('/api/wilayah-kelompok', [MasterWilayahController::class, 'storeWilayahKelompok'])->name('api.wilayah-kelompok.store');
        Route::get('/api/wilayah-kelompok/{id}', [MasterWilayahController::class, 'showWilayahKelompok'])->name('api.wilayah-kelompok.show');
        Route::put('/api/wilayah-kelompok/{id}', [MasterWilayahController::class, 'updateWilayahKelompok'])->name('api.wilayah-kelompok.update');
        Route::delete('/api/wilayah-kelompok/{id}', [MasterWilayahController::class, 'destroyWilayahKelompok'])->name('api.wilayah-kelompok.destroy');

        // API untuk data dropdown
        Route::get('/api/dapuan-options', [JamaahController::class, 'getDapuanOptions'])->name('api.dapuan-options');
        Route::get('/api/keluarga-options', [JamaahController::class, 'getKeluargaOptions'])->name('api.keluarga-options');

        // Data Ruyah (View Only)
        Route::get('/admin/ruyah', [MasterWilayahController::class, 'ruyah'])->name('admin.master.ruyah');
        Route::get('/api/ruyah', [MasterWilayahController::class, 'getRuyah'])->name('api.ruyah');

        // dropdown options API
        Route::get('/api/dropdown-options', [MasterWilayahController::class, 'getDropdownOptions']);
    });
});
