<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Report extends Model
{
    use HasFactory;
    // IBARATNYA: "Ini daftar kolom yang BOLEH diisi manual oleh user" 
    protected $guarded = ['id'];
    // Relasi Kebalikan: Laporan ini milik siapa? 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}