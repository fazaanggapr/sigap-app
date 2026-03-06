<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category',
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'image',
        'status'
    ];
    // Relasi ke User (Pelapor) 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // --- TAMBAHKAN BAGIAN INI --- 
// Relasi ke Response (Tanggapan) 
// Satu laporan bisa memiliki banyak tanggapan 
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}