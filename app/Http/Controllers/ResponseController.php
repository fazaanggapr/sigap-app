<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'report_id'     => 'required',
            'response_text' => 'required',
            'image'         => 'nullable|image|max:2048', // Gambar max 2MB
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            // Simpan file ke storage/app/public/responses
            $imagePath = $request->file('image')->store(
                'responses',
                'public'
            );
        }

        Response::create([
            'report_id'     => $request->report_id,
            'user_id'       => Auth::id(),
            'response_text' => $request->response_text,
            'image'         => $imagePath, // Simpan path ke database
        ]);

        return back()->with('success', 'Tanggapan & bukti terkirim!');
    }
}
