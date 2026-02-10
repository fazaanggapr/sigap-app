<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;
class AuthController extends Controller
{
    // 1. Menampilkan Form Daftar 
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    // 2. Proses Simpan Warga Baru 
    public function register(Request $request)
    {
        // VALIDASI: Pastikan NIK & Username belum pernah dipakai 
        $data = $request->validate([
            'nik' => 'required|numeric|unique:users',
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'telp' => 'required|numeric',
        ]);
        // CREATE: Simpan ke Database 
        User::create([
            'nik' => $data['nik'],
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']), // Enkripsi Wajib! 
            'telp' => $data['telp'],
            'role' => 'masyarakat', // Default role otomatis Masyarakat 
        ]);
        return redirect()->route('login')->with('success', 'Akun berhasil 
        dibuat! Silakan login.');
    }
    // 1. Tampilkan Form Login 
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Data Login (Si Satpam) 
    public function login(Request $request)
    {
        // Validasi input dulu 
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Cek ke Database (Satpam Bekerja) 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Buat Session Baru (Gelang Tiket) 

            // Cek Role: Jika Admin ke Dashboard, Jika Warga ke Laporan 
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.lapor'); // Nanti kita buat 

            }
        }

        // Jika Gagal Login 
        return back()->withErrors([
            'username' => 'Username atau Password salah!',
        ]);
    }

    // 3. Proses Logout 
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Di dalam AuthController atau route admin 
    public function dashboard()
    {
        // LOGIKA: Ambil SEMUA laporan, urutkan dari yang terbaru 
        $reports = Report::orderBy('created_at', 'desc')->get();
        // Kirim data '$reports' ke View 
        return view('admin.dashboard', compact('reports'));
    }
}