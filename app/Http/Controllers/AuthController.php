<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6'
        ], [
            'email.exists' => 'Email tidak terdaftar.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        // Ambil user dari database
        $user = DB::table('users')->where('email', $request->email)->first();

        // Cek apakah user ditemukan dan password cocok
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        // Simpan user ke session
        session(['user' => $user]);

        // Redirect berdasarkan role
        return match ($user->role) {
            'adm-01' => redirect('/admin/dashboard'),
            'cashier' => redirect('/cashier'),
            'confirmator' => redirect('/confirmator'),
            'member' => redirect('/member'),
            default => redirect('/login')->withErrors(['access' => 'Role tidak dikenali.'])
        };
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'phone' => 'required'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/login')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
