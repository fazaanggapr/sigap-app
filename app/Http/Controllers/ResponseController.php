<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'report_id'     => 'required|exists:reports,id',
            'response_text' => 'required',
        ]);

        // Simpan tanggapan
        Response::create([
            'report_id'     => $request->report_id,
            'user_id'       => Auth::id(), // Admin yang memberi tanggapan
            'response_text' => $request->response_text,
        ]);

        return back()->with('success', 'Tanggapan berhasil dikirim!');
    }
}
