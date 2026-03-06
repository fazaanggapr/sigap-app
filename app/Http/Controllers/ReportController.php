<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * 1. Menampilkan Form Lapor
     */
    public function index()
    {
        // Query: "Tampilkan laporan milik SAYA saja" 
        // Logika: WHERE user_id = ID Saya yang sedang login 
        // eager-load responses to avoid N+1 and ensure view can access them
        $reports = Report::with('responses')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('user.lapor', compact('reports'));
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
        // NOTE: current migrations don't include latitude/longitude and 'location'
        // might be non-nullable. Use a safe default to avoid SQL errors.
        Report::create([
            'user_id' => Auth::id(),
            'category'    => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location ?? '',
            'image' => $imagePath,
            'status' => '0',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim!');
    }

    // 3. Menampilkan Detail Laporan (Langkah 2) 
    public function show(Report $report)
    {
        // Mengambil data detail laporan beserta User (pelapor)  
// dan Responses (tanggapan) jika ada. 
// Konsep: Route Model Binding (Otomatis cari ID) 
        $report->load(['user', 'responses.user']);
        return view('admin.detail', compact('report'));
    }
    // 4. Update Status Laporan (Langkah 4 - Kita masukkan sekarang biaraman) 
    public function update(Request $request, Report $report)
    {
        // Validasi input status 
        $data = $request->validate([
            'status' => 'required|in:0,proses,selesai',
        ]);
        // Update data di database 
        $report->update($data);
        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }
    // 5 Fungsi Cetak PDF 
    public function exportPdf()
    {
        // Ambil semua data laporan 
        $reports = Report::all();
        // Load View khusus PDF (nanti kita buat) 
        $pdf = Pdf::loadView('admin.print', ['reports' => $reports]);
        // Download file 
        return $pdf->download('laporan-pengaduan.pdf');
    }
}

