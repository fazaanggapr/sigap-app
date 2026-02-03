<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user (){
        return $this->belongsTo(User::class);
    }

    public function report () {
        return $this->belongsTo(Report::class);
    }
}
