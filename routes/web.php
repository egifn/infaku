<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ConfirmatorController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Master\WilayahController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['role:adm-01'])->group(function () {
    Route::get('/admin/dashboard', [WilayahController::class, 'dashboard'])->name('admin.dashboard');
    // master
    Route::get('/admin/master/wilayah', [WilayahController::class, 'masterWilayah'])->name('admin.master.wilayah');

     // Kota
    Route::get('/kota/data', [WilayahController::class, 'getKotaData'])->name('wilayah.kota.data');
    Route::post('/kota/insert', [WilayahController::class, 'insertKota'])->name('wilayah.kota.insert');
    Route::post('/kota/update', [WilayahController::class, 'updateKota'])->name('wilayah.kota.update');
    Route::post('/kota/delete', [WilayahController::class, 'deleteKota'])->name('wilayah.kota.delete');
    Route::get('/kota/options', [WilayahController::class, 'getKotaOptions'])->name('wilayah.kota.options');
    
    // Daerah
    Route::get('/daerah/data', [WilayahController::class, 'getDaerahData'])->name('wilayah.daerah.data');
    Route::post('/daerah/insert', [WilayahController::class, 'insertDaerah'])->name('wilayah.daerah.insert');
    Route::post('/daerah/update', [WilayahController::class, 'updateDaerah'])->name('wilayah.daerah.update');
    Route::post('/daerah/delete', [WilayahController::class, 'deleteDaerah'])->name('wilayah.daerah.delete');
    
    // Desa
    Route::get('/desa/data', [WilayahController::class, 'getDesaData'])->name('wilayah.desa.data');
    Route::post('/desa/insert', [WilayahController::class, 'insertDesa'])->name('wilayah.desa.insert');
    Route::post('/desa/update', [WilayahController::class, 'updateDesa'])->name('wilayah.desa.update');
    Route::post('/desa/delete', [WilayahController::class, 'deleteDesa'])->name('wilayah.desa.delete');
    Route::get('/desa/options', [WilayahController::class, 'getDesaOptions'])->name('wilayah.desa.options');
    
    // Kelompok
    Route::get('/kelompok/data', [WilayahController::class, 'getKelompokData'])->name('wilayah.kelompok.data');
    Route::post('/kelompok/insert', [WilayahController::class, 'insertKelompok'])->name('wilayah.kelompok.insert');
    Route::post('/kelompok/update', [WilayahController::class, 'updateKelompok'])->name('wilayah.kelompok.update');
    Route::post('/kelompok/delete', [WilayahController::class, 'deleteKelompok'])->name('wilayah.kelompok.delete');
   
});

Route::middleware(['role:cashier'])->group(function () {
    Route::get('/cashier', [CashierController::class, 'dashboard']);
    Route::get('/cashier/bookings', [CashierController::class, 'dailyBookings']);
    Route::post('/cashier/payment', [CashierController::class, 'finalizePayment']);
});

Route::middleware(['role:confirmator'])->group(function () {
    Route::get('/confirmator', [ConfirmatorController::class, 'pendingBookings']);
    Route::post('/confirmator/verify/{id}', [ConfirmatorController::class, 'verifyPayment']);
});

Route::middleware(['role:member'])->group(function () {
    Route::get('/member', [MemberController::class, 'dashboard']);
    Route::get('/member/book', [MemberController::class, 'showBookingForm']);
    Route::post('/member/book', [MemberController::class, 'storeBooking']);
    Route::get('/member/history', [MemberController::class, 'bookingHistory']);
});

Route::get('/api/bookings', function() {
    $bookings = DB::table('bookings')
        ->join('courts', 'bookings.court_id', '=', 'courts.id')
        ->select('bookings.date as start', 'courts.name as title', 'bookings.start_time', 'bookings.end_time')
        ->where('booking_status', 'confirmed')
        ->get();

    return response()->json($bookings);
});
