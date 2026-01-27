<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * 1. Menampilkan Form Lapor
     */
    public function index()
    {
        // LOGIKA: Ambil laporan DIMANA (Where) id pemiliknya == ID saya yang sedang Flogin 
        $myReports = Report::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('user.lapor', compact('myReports'));
    }

    /**
     * 2. Memproses Data & Foto
     */
    public function store(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'location' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ]);

        // B. Upload Foto (jika ada)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');
        }

        // C. Simpan ke Database
        Report::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'image' => $imagePath,
            'status' => '0', // pending

        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim!');
    }
}
