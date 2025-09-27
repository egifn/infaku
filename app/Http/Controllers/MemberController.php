<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function showBookingForm() {
        $sports = DB::table('sports')->get();
        $courts = DB::table('courts')->get();
        return view('bookings.create', compact('sports', 'courts'));
    }

    public function storeBooking(Request $request) {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'facilities' => 'nullable|array'
        ]);

        $court = DB::table('courts')->where('id', $request->court_id)->first();
        $duration = now()->parse($request->start_time)->diffInHours(now()->parse($request->end_time));
        
        $isWeekend = in_array(date('w', strtotime($request->date)), [0,6]);
        $hourlyRate = $isWeekend ? $court->hourly_rate_weekend : $court->hourly_rate_weekday;
        $total = $duration * $hourlyRate;

        // Tambah fasilitas
        $facilityTotal = 0;
        if ($request->facilities) {
            $facilities = DB::table('facilities')->whereIn('id', $request->facilities)->pluck('price');
            $facilityTotal = $facilities->sum();
        }

        $total += $facilityTotal;
        $dp = $total * 0.3;

        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => session('user')->id,
            'court_id' => $request->court_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_amount' => $total,
            'dp_amount' => $dp,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/member/history')->with('success', 'Booking berhasil! Silakan upload DP.');
    }
}
