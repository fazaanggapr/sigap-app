<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Report extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'image',
        'status'
    ];
=======
    protected $guarded = [];
>>>>>>> 5849706b2745db8582b7a3c6ac5e436c5eab8469
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